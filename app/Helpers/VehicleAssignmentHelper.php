<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Log;

use App\Models\DailyTeamVehicles;
use App\Models\SubTeamVehicle;
use Carbon\Carbon;

class VehicleAssignmentHelper
{
    /**
     * Retorna a etiqueta completa para usar em selects de veículos, indicando a sua disponibilidade.
     */
    public static function getLabelParaSelect($vehicleId, $date, $excludeTeamId = null, $excludeVehicleId = null, $isTemplate = null): string
    {
        $date = $date instanceof \Carbon\Carbon ? $date->toDateString() : \Carbon\Carbon::parse($date)->toDateString();
        $vehicle = is_numeric($vehicleId) ? \App\Models\Vehicle::find($vehicleId) : $vehicleId;


        if (!$vehicle) {
            Log::warning('[VehicleAssignmentHelper] Veículo desconhecido', ['vehicleId' => $vehicleId]);
            return '❓ Veículo desconhecido';
        }

        $nome = $vehicle->car_plate . ' - ' . ($vehicle->vehicleBrand->name ?? '') . ' ' . ($vehicle->model ?? '');
        $status = self::getDetailedStatusForDate($vehicle->id, $date, $excludeTeamId, $excludeVehicleId, $isTemplate);


        if ($status['status'] === 'indisponivel') {
            $emoji = '❌';
            $result = "$emoji $nome ({$status['motivo']})";
        } elseif ($status['status'] === 'atribuido') {
            $context = $status['context'] === 'subgrupo' ? 'sub-equipa' : 'equipa';
            $nomeGrupo = $status['team_name'] ?? '';
            $label = "Atribuído à $context: $nomeGrupo";
            if ($status['context'] === 'subgrupo' && !empty($status['main_team_name'])) {
                $label .= " (Equipa: {$status['main_team_name']})";
            }
            // Cor conforme a equipa
            if (!empty($status['same_team']) && $status['same_team']) {
                $emoji = '✔️';
            } else {
                $emoji = '🔒';
            }
            $result = "$emoji $nome ($label)";
        } elseif ($status['status'] === 'livre') {
            $emoji = '✅'; // Livre
            $result = "$emoji $nome (Livre)";
        } else {
            $result = "❔ $nome (Estado desconhecido)";
        }

        return $result;
    }
    /**
     * Verifica o estado detalhado de um veículo para uma data específica.
     * Retorna se está livre, atribuído ou indisponível, juntamente com detalhes do conflito, se houver.
     */
    public static function getDetailedStatusForDate($vehicleId, $workDate, $teamId = null, $excludeVehicleId = null, $isTemplate = null)
    {
        

        // Verificar incidentes e manutenções
        $incident = \App\Models\VehicleIncident::where('vehicle_id', $vehicleId)
            ->whereDate('fecha', $workDate)->first();
        if ($incident) {

            return [
                'status' => 'indisponivel',
                'motivo' => 'Incidente: ' . ($incident->tipo ?? 'Indisponível'),
                'team_name' => null,
                'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string) $workDate,
                'context' => null,
            ];
        }
        $maintenance = \App\Models\VehicleMaintenance::where('vehicle_id', $vehicleId)
            ->whereDate('fecha', $workDate)->first();
        if ($maintenance) {

            return [
                'status' => 'indisponivel',
                'motivo' => 'Manutenção: ' . ($maintenance->motivo ?? 'Em manutenção'),
                'team_name' => null,
                'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string) $workDate,
                'context' => null,
            ];
        }

        // Buscar conflito em equipas principais
        $query = DailyTeamVehicles::whereHas('dailyTeam', function ($q) use ($workDate, $isTemplate) {
            $q->whereDate('work_date', $workDate);
            if (!is_null($isTemplate)) {
                $q->where('is_template', $isTemplate);
            }
        })->where('vehicle_id', $vehicleId);

        // Solo excluir el registro si realmente se está editando (excludeVehicleId > 0 y método PUT)
        if ($excludeVehicleId && request()->isMethod('PUT')) {
            $query->where('daily_team_vehicles.id', '!=', $excludeVehicleId);
        }

        $conflict = $query->with('dailyTeam.teamname')->first();

        if ($conflict) {
            $isSameTeam = false;
            if ($teamId && $conflict->dailyTeam && $conflict->dailyTeam->id == $teamId) {
                $isSameTeam = true;
            }

            return [
                'status' => 'atribuido',
                'team_name' => $conflict->dailyTeam->teamname->name ?? 'Equipa',
                'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string) $workDate,
                'context' => 'equipa',
                'same_team' => $isSameTeam,
            ];
        }

        // Buscar conflito em sub-equipas
        $subConflict = SubTeamVehicle::where('vehicle_id', $vehicleId)
            ->whereHas('subTeam', function ($q) use ($workDate, $isTemplate) {
                $q->where('work_date', $workDate);
                if (!is_null($isTemplate)) {
                    $q->whereHas('dailyTeam', function ($qq) use ($isTemplate) {
                        $qq->where('is_template', $isTemplate);
                    });
                }
            })
            // Solo excluir el registro si realmente se está editando (excludeVehicleId > 0)
            ->when($excludeVehicleId && request()->isMethod('PUT'), fn ($q) => $q->where('sub_team_vehicles.id', '!=', $excludeVehicleId))
            ->with(['subTeam.subTeamName', 'subTeam.dailyTeam.teamname'])
            ->first();


        if ($subConflict) {
            $mainTeamId = $subConflict->subTeam->dailyTeam->id ?? null;
            $mainTeamName = $subConflict->subTeam->dailyTeam->teamname->name ?? null;

            $isSameTeam = false;
            if ($teamId && $mainTeamId == $teamId) {
                $isSameTeam = true;
            }


            return [
                'status' => 'atribuido',
                'team_name' => $subConflict->subTeam->subTeamName->name ?? 'Sub-equipa',
                'main_team_name' => $mainTeamName,
                'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string) $workDate,
                'context' => 'subgrupo',
                'same_team' => $isSameTeam,
            ];
        }

        return [
            'status' => 'livre',
            'team_name' => null,
            'date' => $workDate instanceof Carbon ? $workDate->toDateString() : (string) $workDate,
            'context' => null,
        ];
    }
}
