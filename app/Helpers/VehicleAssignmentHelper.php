<?php

namespace App\Helpers;

use App\Models\DailyTeamVehicles;
use App\Models\SubTeamVehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VehicleAssignmentHelper
{
    /**
     * Retorna la etiqueta completa para usar en selects de vehículos.
     */
    public static function getLabelParaSelect($vehicleId, $date, $excludeTeamId = null, $excludeVehicleId = null, $isTemplate = null): string
    {
        $date = $date instanceof \Carbon\Carbon ? $date->toDateString() : \Carbon\Carbon::parse($date)->toDateString();
        $vehicle = $vehicleId;
        if (is_numeric($vehicleId)) {

    $vehicle = \App\Models\Vehicle::find($vehicleId);
        }
        if (!$vehicle) {
            return '❓ Veículo desconhecido';
        }

        $nome = $vehicle->car_plate . ' - ' . ($vehicle->brand ?? '') . ' ' . ($vehicle->model ?? '');
    $status = self::getDetailedStatusForDate($vehicle->id, $date, $excludeTeamId, $excludeVehicleId, $isTemplate);

        if ($status['status'] === 'indisponivel') {
            $emoji = '🚫';
            $result = "$emoji $nome ({$status['motivo']})";
        } elseif ($status['status'] === 'asignado') {
            $context = $status['context'] === 'subgrupo' ? 'Subgrupo' : 'Equipa';
            $nomeGrupo = $status['team_name'] ?? '';
            $label = "Atribuído ao $context: $nomeGrupo";
            if ($status['context'] === 'subgrupo' && !empty($status['main_team_name'])) {
                $label .= " (Equipa: {$status['main_team_name']})";
            }
            // Color según equipo
            if (!empty($status['same_team']) && $status['same_team']) {
                $emoji = '🟡';
            } else {
                $emoji = '🔴';
            }
            $result = "$emoji $nome ($label)";
        } elseif ($status['status'] === 'libre') {
            $emoji = '🟢';
            $result = "$emoji $nome (Livre)";
        } else {
            $result = "$nome";
        }

        return $result;
    }
    /**
     * Verifica si un vehículo ya está asignado a otro equipo en la misma fecha.
     * Devuelve información del conflicto si existe, o null si está libre.
     */
    public static function getDetailedStatusForDate($vehicleId, $workDate, $teamId = null, $excludeVehicleId = null, $isTemplate = null)
    {

            // Verificar incidentes y mantenimientos
            $incident = \App\Models\VehicleIncident::where('vehicle_id', $vehicleId)
                ->whereDate('fecha', $workDate)->first();
            if ($incident) {
                return [
                    'status' => 'indisponivel',
                    'motivo' => 'Incidente: ' . ($incident->tipo ?? 'Indisponível'),
                    'team_name' => null,
                    'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                    'context' => null,
                ];
            }
            $maintenance = \App\Models\VehicleMaintenance::where('vehicle_id', $vehicleId)
                ->whereDate('fecha', $workDate)->first();
            if ($maintenance) {
                return [
                    'status' => 'indisponivel',
                    'motivo' => 'Manutenção: ' . ($maintenance->motivo ?? 'Indisponível'),
                    'team_name' => null,
                    'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                    'context' => null,
                ];
            }

            // Buscar conflicto en equipos principales
            $query = DailyTeamVehicles::whereHas('dailyTeam', function ($q) use ($workDate, $isTemplate) {
                $q->where('work_date', $workDate);
                if (!is_null($isTemplate)) {
                    $q->where('is_template', $isTemplate);
                }
            })
                ->where('vehicle_id', $vehicleId);

            if ($excludeVehicleId) {
                $query->where('id', '!=', $excludeVehicleId);
            }
            // No excluir el equipo actual, así se detecta si el vehículo está en su propio equipo

            $conflict = $query->with('dailyTeam.teamname')->first();
            if ($conflict) {
                if ($teamId && $conflict->dailyTeam->id == $teamId) {
                    return [
                        'status' => 'asignado',
                        'team_name' => $conflict->dailyTeam->teamname->name ?? 'equipo',
                        'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                        'context' => 'equipo',
                        'same_team' => true,
                    ];
                } else {
                    return [
                        'status' => 'asignado',
                        'team_name' => $conflict->dailyTeam->teamname->name ?? 'equipo',
                        'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                        'context' => 'equipo',
                        'same_team' => false,
                    ];
                }
            }
            // Buscar conflicto en subgrupos
            $subConflict = SubTeamVehicle::where('vehicle_id', $vehicleId)
                ->whereHas('subTeam', function ($q) use ($workDate, $isTemplate) {
                    $q->where('work_date', $workDate);
                    if (!is_null($isTemplate)) {
                        $q->whereHas('dailyTeam', function($qq) use ($isTemplate) {
                            $qq->where('is_template', $isTemplate);
                        });
                    }
                })
                ->when($excludeVehicleId, fn($q) => $q->where('id', '!=', $excludeVehicleId))
                ->with(['subTeam.subTeamName', 'subTeam.dailyTeam.teamname'])
                ->first();
                // ...log eliminado...
            if ($subConflict) {
                $mainTeamId = $subConflict->subTeam->dailyTeam->id ?? null;
                $mainTeamName = $subConflict->subTeam->dailyTeam->teamname->name ?? null;
                if ($teamId && $mainTeamId == $teamId) {
                    return [
                        'status' => 'asignado',
                        'team_name' => $subConflict->subTeam->subTeamName->name ?? 'subgrupo',
                        'main_team_name' => $mainTeamName,
                        'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                        'context' => 'subgrupo',
                        'same_team' => true,
                    ];
                } else {
                    return [
                        'status' => 'asignado',
                        'team_name' => $subConflict->subTeam->subTeamName->name ?? 'subgrupo',
                        'main_team_name' => $mainTeamName,
                        'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                        'context' => 'subgrupo',
                        'same_team' => false,
                    ];
                }
            }
                // ...log eliminado...
            return [
                'status' => 'libre',
                'team_name' => null,
                'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string)$workDate,
                'context' => null,
            ];
        }
    }

