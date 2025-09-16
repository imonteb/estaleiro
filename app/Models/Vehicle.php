<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{

    protected $fillable = [
        'car_plate',
        'vehicle_brand_id',
        'model',
        'type',
        'fuel_card_number',
        'fuel_card_pin',
        'insurance_name',
        'insurance_validity_date',
        'last_vehicle_inspection_date',
        'vehicle_condition',
        'assigned',
        'image_url',
    ];

    protected $casts = [
        'assigned' => 'boolean',
        'insurance_validity_date' => 'date',
        'last_vehicle_inspection_date' => 'date',
    ];

    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class);
    }




    public function dailyTeamVehicles(): HasMany
    {
        return $this->hasMany(DailyTeamVehicles::class);
    }

    public function maintenances()
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    public function incidents()
    {
        return $this->hasMany(VehicleIncident::class);
    }
    public function isAvailableOn($date): bool
    {
        return !$this->dailyTeamVehicles()
            ->whereHas(
                'dailyTeam',
                fn($q) =>
                $q->whereDate('work_date', $date)
            )
            ->exists();
    }



    public function requiereIPO($date): bool
    {
        $date = \Carbon\Carbon::parse($date);

        if (!$this->last_vehicle_inspection_date) {
            return true;
        }

        $nextIPO = $this->last_vehicle_inspection_date->copy()->addYear();

        return $date->greaterThanOrEqualTo($nextIPO);
    }

    public function isEnMantenimiento($date): bool
    {
        return $this->maintenances()
            ->whereDate('fecha', $date)
            ->exists();
    }

    public function isAveriado($date): bool
    {
        return $this->incidents()
            ->whereDate('fecha', $date)
            ->where('tipo', 'avería')
            ->exists();
    }
    public function getLabelParaSelect($date): string
    {
        $date = $date instanceof Carbon
            ? $date->startOfDay()
            : Carbon::parse($date)->startOfDay();

        $estado = $this->getEstadoParaFecha($date);

        // Mostrar matricula + marca y estado
        return "{$estado['emoji']} {$this->car_plate} ({$this->vehicleBrand->name}) — {$estado['label']}";
    }


    public function subTeamVehicles()
    {
        return $this->hasMany(\App\Models\SubTeamVehicle::class);
    }

    public function getEstadoParaFecha($date): array
    {
        $date = $date instanceof Carbon
            ? $date->startOfDay()
            : Carbon::parse($date)->startOfDay();

        // Buscar si el vehículo está asignado en un equipo principal
        $assignment = $this->dailyTeamVehicles()
            ->whereHas(
                'dailyTeam',
                fn($query) =>
                $query->whereDate('work_date', $date) // usa el campo correcto de la tabla daily_teams
            )
            ->with('dailyTeam.teamName')
            ->first();


        if ($assignment) {
            $teamName = optional($assignment->dailyTeam->teamName)->name ?? 'equipa';
            return [
                'emoji' => '🔴',
                'color' => 'danger',
                'label' => " {$teamName}",
            ];
        }

        // Buscar si está en un subequipo
        $subAssignment = $this->subTeamVehicles()
            ->whereHas(
                'subTeam',
                fn($query) =>
                $query->whereDate('work_date', $date) // usa el campo correcto de la tabla sub_teams
            )
            ->with('subTeam.subTeamName')
            ->first();

        if ($subAssignment) {
            $subTeamName = optional($subAssignment->subTeam->subTeamName)->name ?? 'subequipa';
            return [
                'emoji' => '🔴',
                'color' => 'danger',
                'label' => " {$subTeamName}",
            ];
        }

        return [
            'emoji' => '🟢',
            'color' => 'success',
            'label' => 'disponível',
        ];
    }
}
