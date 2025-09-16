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
     * Retorna la etiqueta completa para usar en selects.
     */
    public static function getLabelParaSelect($employeeId, $date, $excludeTeamId = null, $excludeMemberId = null): string
    {
        $date = $date instanceof Carbon ? $date->toDateString() : Carbon::parse($date)->toDateString();
        $employee = $employeeId instanceof Employee ? $employeeId : Employee::with('user')->find($employeeId);
        if (!$employee) {
            return '❓ Colaborador desconhecido';
        }

        $nome = $employee->employee_number . ' - ' . ($employee->user->name ?? '') . ' ' . ($employee->last_name ?? '');
    $status = self::getDetailedStatusForDate($employee, $date, $excludeTeamId, $excludeMemberId);

        if ($status['status'] === 'ausente') {
            $label = $status['motivo'] ?: 'Ausente';
            $emoji = '🚫';
            $result = "$emoji $nome (Ausente: $label)";
        } elseif ($status['status'] === 'asignado') {
            $rol = $status['rol'] ?? '';
            $nomeGrupo = $status['equipo'] ?? '';
            $label = "Designado como $rol no $nomeGrupo";
            $emoji = '🔴';
            $result = "$emoji $nome ($label)";
        } elseif ($status['status'] === 'same_team') {
            $rol = $status['rol'] ?? '';
            $nomeGrupo = $status['equipo'] ?? '';
            $label = "Já está neste equipo ($rol";
            if ($nomeGrupo) {
                $label .= " no $nomeGrupo";
            }
            $label .= ")";
            $emoji = '🟡';
            $result = "$emoji $nome ($label)";
        } else {
            $emoji = '🟢';
            $result = "$emoji $nome (Disponível)";
        }
        return $result;
    }

    /**
     * Retorna un status detallado de un empleado en la fecha dada.
     */
    public static function getDetailedStatusForDate($employee, $date, $excludeTeamId = null, $excludeMemberId = null): array
    {

        if (is_int($employee)) {
            $employee = \App\Models\Employee::find($employee);
        }
        if (!$employee) {
            $result = [
                'status' => 'ausente',
                'motivo' => 'Colaborador não encontrado',
                'equipo' => null,
                'rol'    => null,
            ];
            return $result;
        }

        // 1. Ausente
        $absence = self::isAbsentOn($employee, $date);
        if ($absence) {
            $motivo = $absence->statusType->name ?? $absence->motivo ?? $absence->type ?? 'Ausente';
            return [
                'status' => 'ausente',
                'motivo' => $motivo,
                'equipo' => null,
                'rol'    => null,
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
                'motivo' => 'Colaborador não encontrado',
                'equipo' => null,
                'rol'    => null,
            ];
            return $result;
        }

        $asignacion = self::buscarAsignacion($employee->id, $date, $excludeTeamId, $excludeMemberId);

        if ($asignacion) {
            // Comparar correctamente el tipo de equipo
            $isSameTeam = false;
            if ($excludeTeamId && (isset($asignacion['team_id']) || isset($asignacion['daily_team_id']))) {
                // Si es líder o miembro de subgrupo, comparar el daily_team_id del subgrupo
                if ((strpos($asignacion['rol'], 'subgrupo') !== false) && isset($asignacion['daily_team_id'])) {
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
                    'motivo'    => null,
                    'equipo'    => $asignacion['equipo'],
                    'rol'       => $asignacion['rol'],
                    'member_id' => $asignacion['member_id'] ?? null,
                    'date'      => $date,
                ];
            }

            $result = [
                'status'    => 'asignado',
                'motivo'    => null,
                'equipo'    => $asignacion['equipo'],
                'rol'       => $asignacion['rol'],
                'member_id' => $asignacion['member_id'] ?? null,
                'date'      => $date,
            ];

            return $result;
        }

        $result = [
            'status' => null,
            'motivo' => null,
            'equipo' => null,
            'rol'    => null,
        ];

        return $result;
    }

    /**
     * Verifica si un empleado está ausente en una fecha.
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
     * Busca si el empleado ya está asignado a un equipo o plantilla en la fecha.
     */
    public static function buscarAsignacion($employeeId, $date, $excludeTeamId = null, $excludeMemberId = null)
    {
        // Verificar si es líder en algún equipo/plantilla en la fecha
        $liderSubEquipo = \App\Models\SubTeam::with('dailyTeam.teamname')
            ->where('leader_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date) {
                $q->whereDate('work_date', $date);
            })
            ->first();
        if ($liderSubEquipo) {
            $nomeGrupo = $liderSubEquipo->dailyTeam->teamname?->name ?? $liderSubEquipo->name ?? 'Subequipe';
            return [
                'equipo'        => $nomeGrupo,
                'rol'           => 'Líder de subgrupo',
                'member_id'     => null,
                'team_id'       => $liderSubEquipo->id,
                'daily_team_id' => $liderSubEquipo->daily_team_id,
            ];
        }

        $liderEquipo = \App\Models\DailyTeam::with('teamname')
            ->where('leader_id', $employeeId)
            ->whereDate('work_date', $date)
            ->first();
        if ($liderEquipo) {
            return [
                'equipo'    => $liderEquipo->teamname?->name ?? $liderEquipo->name ?? 'Equipe',
                'rol'       => 'Líder',
                'member_id' => null,
                'team_id'   => $liderEquipo->id,
            ];
        }

        $query = DailyTeamMember::where('employee_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date) {
                $q->whereDate('work_date', $date);
            });

        $member = $query->with('dailyTeam.teamname')->first();
        if ($member) {
            return [
                'equipo'    => $member->dailyTeam->teamname?->name ?? $member->dailyTeam->name ?? 'Equipe',
                'rol'       => $member->role ?? 'Membro',
                'member_id' => $member->id,
                'team_id'   => $member->daily_team_id,
            ];
        }

        $subQuery = SubTeamMember::where('employee_id', $employeeId)
            ->whereHas('subTeam', function ($q) use ($date) {
                $q->whereHas('dailyTeam', function ($q2) use ($date) {
                    $q2->whereDate('work_date', $date);
                });
            });

        $subMember = $subQuery->with('subTeam')->first();
        if ($subMember) {
            $nomeGrupo = $subMember->subTeam->dailyTeam->teamname?->name ?? $subMember->subTeam->name ?? 'Subequipe';
            return [
                'equipo'        => $nomeGrupo,
                'rol'           => $subMember->role ?? 'Membro de subgrupo',
                'member_id'     => $subMember->id,
                'team_id'       => $subMember->subTeam->id ?? null,
                'daily_team_id' => $subMember->subTeam->daily_team_id ?? null,
            ];
        }

        return null;
    }
}
