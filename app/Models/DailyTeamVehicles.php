<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTeamVehicles extends Model
{
    use HasFactory;
    protected $table = 'daily_team_vehicles';
    protected $fillable = [
        'daily_team_id',
        'vehicle_id',
        
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function dailyTeam()
    {
        return $this->belongsTo(DailyTeam::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where('daily_team_id', $teamId);
    }
    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }
    public function getVehicleNameAttribute(): ?string
    {
        return $this->vehicle?->name ?: 'Sem veículo';
    }

    public function scopeInSubTeam($query, $subTeamId)
{
    return $query->where('sub_team_id', $subTeamId);
}
}
