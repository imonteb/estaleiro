<?php

namespace App\Helpers;

use App\Models\Employee;
use App\Models\DailyTeamMember;
use App\Models\SubTeamMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmployeeAssignmentHelper
{

    /**
     * Retorna a etiqueta completa para usar em selects.
     */
    public static function getLabelParaSelect(
        $employeeId,
        $date,
        $excludeTeamId = null,
        $excludeMemberId = null
    ): string {


        $date = $date instanceof Carbon ? $date->toDateString() : Carbon::parse($date)->toDateString();
        $employee = $employeeId instanceof Employee ? $employeeId : Employee::with('user')->find($employeeId);
        if (!$employee) {
            return '❓ Colaborador desconhecido';
        }

        $nome = $employee->employee_number . ' - ' . ($employee->user->name ?? '') . ' ' . ($employee->last_name ?? '');
        $status = self::getDetailedStatusForDate($employee, $date, $excludeTeamId, $excludeMemberId);

        if ($status['status'] === 'ausente') {
            $label = $status['reason'] ?: 'Ausente';
            $emoji = '❌';
            $result = "$emoji $nome (Ausente: $label)";
        } elseif ($status['status'] === 'asignado') {
            $role = $status['role'] ?? '';
            $teamName = $status['team_name'] ?? '';
            $label = "Designado como $role no $teamName";
            $emoji = '🔒';
            $result = "$emoji $nome ($label)";
        } elseif ($status['status'] === 'same_team') {
            $role = $status['role'] ?? '';
            $teamName = $status['team_name'] ?? '';
            $label = "Já está nesta equipa ($role";
            if ($teamName) {
                $label .= " no $teamName";
            }
            $label .= ")";
            $emoji = '✔️';
            $result = "$emoji $nome ($label)";
        } else {
            $emoji = '✅';
            $result = "$emoji $nome (Disponível)";
        }

        return $result;
    }

    /**
     * Retorna um estado detalhado de um colaborador na data fornecida.
     */
    public static function getDetailedStatusForDate($employee, $date, $excludeTeamId = null, $excludeMemberId = null): array
    {

        if (is_int($employee)) {
            $employee = \App\Models\Employee::find($employee);
        }
        if (!$employee) {
            $result = [
                'status' => 'ausente',
                'reason' => 'Colaborador não encontrado',
                'team_name' => null,
                'role'    => null,
            ];
            return $result;
        }

        // 1. Ausente
        $absence = self::isAbsentOn($employee, $date);
        if ($absence) {
            $motivo = $absence->statusType->name ?? $absence->motivo ?? $absence->type ?? 'Ausente';
            return [
                'status' => 'ausente',
                'reason' => $motivo,
                'team_name' => null,
                'role'    => null,
            ];
        }

        // 2. Buscar asignación (plantilla o equipo real)
        // Si $employee es string numérico o int, convertir a modelo
        if ((is_string($employee) && is_numeric($employee)) || is_int($employee)) {
            $employee = \App\Models\Employee::find((int)$employee);
        }
        if (!$employee || !($employee instanceof \App\Models\Employee)) {
            $result = [
                'status' => 'ausente',
                'reason' => 'Colaborador não encontrado',
                'team_name' => null,
                'role'    => null,
            ];
            return $result;
        }

        $asignacion = self::buscarAsignacion($employee->id, $date, $excludeTeamId, $excludeMemberId);

        if ($asignacion) {
            // Comparar correctamente el tipo de equipo
            $isSameTeam = false;
            if ($excludeTeamId && (isset($asignacion['team_id']) || isset($asignacion['daily_team_id']))) {
                // Si es líder o miembro de subgrupo, comparar el daily_team_id del subgrupo
                if ((strpos($asignacion['role'], 'subgrupo') !== false) && isset($asignacion['daily_team_id'])) {
                    if ($asignacion['daily_team_id'] == $excludeTeamId) {
                        $isSameTeam = true;
                    }
                } else if (isset($asignacion['team_id']) && $asignacion['team_id'] == $excludeTeamId) {
                    $isSameTeam = true;
                }
            }
            if ($isSameTeam) {
                return [
                    'status'    => 'same_team',
                    'reason'    => null,
                    'team_name' => $asignacion['team_name'],
                    'role'       => $asignacion['role'],
                    'member_id' => $asignacion['member_id'] ?? null,
                    'date'      => $date,
                ];
            }

            $result = [
                'status'    => 'asignado',
                'reason'    => null,
                'team_name' => $asignacion['team_name'],
                'role'       => $asignacion['role'],
                'member_id' => $asignacion['member_id'] ?? null,
                'date'      => $date,
            ];

            return $result;
        }

        $result = [
            'status' => null,
            'reason' => null,
            'team_name' => null,
            'role'    => null,
        ];

        return $result;
    }

    /**
     * Verifica se um colaborador está ausente numa determinada data.
     */
    public static function isAbsentOn($employee, $date)
    {
        if (is_string($employee) && is_numeric($employee)) {
            $employee = (int) $employee;
        }

        if (is_int($employee)) {
            $employee = \App\Models\Employee::find($employee);
        }

        if (!$employee || !($employee instanceof \App\Models\Employee)) {
            return null;
        }
        $dateObj = $date instanceof Carbon ? $date : Carbon::parse($date);
        $absenceTypes = [2, 3, 4];
        return $employee->statuses()->with('statusType')
            ->whereIn('status_type_id', $absenceTypes)
            ->whereDate('start_date', '<=', $dateObj)
            ->where(function ($q) use ($dateObj) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $dateObj);
            })
            ->first();
    }

    /**
     * Procura se o colaborador já está atribuído a uma equipa ou modelo na data.
     */
    public static function buscarAsignacion($employeeId, $date, $excludeTeamId = null, $excludeMemberId = null)
    {
        // 1. Buscar como líder de un equipo principal
        $liderEquipo = \App\Models\DailyTeam::with('teamname')
            ->where('leader_id', $employeeId)
            ->whereDate('work_date', $date)
            ->first();
        if ($liderEquipo) {
            return [
                'team_name' => $liderEquipo->teamname?->name ?? $liderEquipo->name ?? 'Equipa',
                'role'       => 'Líder',
                'member_id' => null,
                'team_id'   => $liderEquipo->id,
            ];
        }

        // 2. Buscar como líder de un sub-equipo
        $liderSubEquipo = \App\Models\SubTeam::with(['subTeamName', 'dailyTeam.teamname'])
            ->where('leader_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date) {
                $q->whereDate('work_date', $date);
            })
            ->first();
        if ($liderSubEquipo) {
            $teamName = $liderSubEquipo->subTeamName?->name ?? 'Sub-equipa';
            $mainTeamName = $liderSubEquipo->dailyTeam->teamname?->name;
            return [
                'team_name'     => $mainTeamName ? "$teamName (Equipa: $mainTeamName)" : $teamName,
                'role'           => 'Líder de subgrupo',
                'member_id'     => null,
                'team_id'       => $liderSubEquipo->id,
                'daily_team_id' => $liderSubEquipo->daily_team_id,
            ];
        }

        // 3. Buscar como miembro de un equipo principal
        $query = DailyTeamMember::where('employee_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date) {
                $q->whereDate('work_date', $date);
            });

        $member = $query->with('dailyTeam.teamname')->first();
        if ($member) {
            return [
                'team_name' => $member->dailyTeam->teamname?->name ?? 'Equipa',
                'role'       => 'Membro',
                'member_id' => $member->id,
                'team_id'   => $member->daily_team_id,
            ];
        }

        // 4. Buscar como miembro de un sub-equipo
        $subQuery = SubTeamMember::where('employee_id', $employeeId)
            ->whereHas('subTeam', function ($q) use ($date) {
                $q->whereHas('dailyTeam', function ($q2) use ($date) {
                    $q2->whereDate('work_date', $date);
                });
            });

        $subMember = $subQuery->with('subTeam')->first();
        if ($subMember) {
            $teamName = $subMember->subTeam->subTeamName?->name ?? 'Sub-equipa';
            $mainTeamName = $subMember->subTeam->dailyTeam->teamname?->name;
            return [
                'team_name'     => $mainTeamName ? "$teamName (Equipa: $mainTeamName)" : $teamName,
                'role'           => 'Membro de subgrupo',
                'member_id'     => $subMember->id,
                'team_id'       => $subMember->subTeam->id ?? null,
                'daily_team_id' => $subMember->subTeam->daily_team_id ?? null,
            ];
        }

        return null;
    }
}
