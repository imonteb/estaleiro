<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubTeam extends Model
{
    use HasFactory;

    protected $fillable = [  
        'daily_team_id',
        'sub_team_name_id',
        'leader_id',
        'pep_id',
        'work_date',
        'active',
    ];

    // 🔁 Relación con equipo padre
    public function dailyTeam(): BelongsTo
    {
        return $this->belongsTo(DailyTeam::class, 'daily_team_id');
    }


    // 🔁 Relación con nombre de subequipo
    public function subTeamName(): BelongsTo
    {
        return $this->belongsTo(SubTeamName::class);
    }

    // 🔁 Relación con líder
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'leader_id');
    }

    // 🔁 Relación con PEP
    public function pep(): BelongsTo
    {
        return $this->belongsTo(Pep::class);
    }

    // 🔁 Membros del subequipo
    public function members(): HasMany
    {
        return $this->hasMany(SubTeamMember::class);
    }

    // 🔁 Vehículos del subequipo
    public function vehicles(): HasMany
    {
        return $this->hasMany(SubTeamVehicle::class);
    }
     public function subTeamVehicles(): HasMany
    {
        return $this->hasMany(SubTeamVehicle::class);
    }
     public function subTeamMembers(): HasMany
    {
        return $this->hasMany(SubTeamMember::class);
    }
    // app/Models/SubTeam.php
    public function teamname(): BelongsTo
    {
        return $this->belongsTo(TeamName::class, 'team_name_id');
    }
    protected static function booted()
    {
        static::creating(function ($subTeam) {
            /* if (empty($subTeam->work_date) && $subTeam->dailyTeam) {
                $subTeam->work_date = $subTeam->dailyTeam->work_date;
            }
            if ($subTeam->team_name_id && $subTeam->work_date) {
                $dailyTeam = \App\Models\DailyTeam::where('team_name_id', $subTeam->team_name_id)
                    ->whereDate('work_date', $subTeam->work_date)
                    ->first();

                if ($dailyTeam) {
                    $subTeam->pep_id = $dailyTeam->pep_id;
                }
            }
 */
            if (empty($subTeam->work_date) && $subTeam->dailyTeam) {
                $subTeam->work_date = $subTeam->dailyTeam->work_date;
            }
            if (empty($subTeam->pep_id) && $subTeam->dailyTeam) {
                $subTeam->pep_id = $subTeam->dailyTeam->pep_id;
            }
        });
    }
}
