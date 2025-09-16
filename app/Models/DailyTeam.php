<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyTeam extends Model
{
    use HasFactory;
    protected $table = 'daily_teams';

    protected $fillable = [
        'pep_id',
        'work_date',
        'leader_id',
        'work_type',
        'location',
        'created_by',
        'team_name_id',
        'is_template', // novo campo para template
        'published',      // novo campo para status (aberto/publicado)
    ];
    protected $casts = [
        'work_date' => 'date',
        'is_template' => 'boolean',
        'published' => 'boolean', // <-- agrega el cast para published
    ];

    public function teamname()
    {
        return $this->belongsTo(TeamName::class, 'team_name_id');
    }
    public function pep()
    {
        return $this->belongsTo(Pep::class);
    }

    public function leader()
    {
        return $this->belongsTo(Employee::class, 'leader_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }




    public function scopeForDate($query, $date)
    {
        return $query->where('work_date', $date);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('work_date', [$start, $end]);
    }


    public function scopeForPep($query, $pepId)
    {
        return $query->where('pep_id', $pepId);
    }

    public function scopeLedBy($query, $employeeId)
    {
        return $query->where('leader_id', $employeeId);
    }


    public function getLeaderNameAttribute(): ?string
    {
        $first = $this->leader?->user?->name ?? '';
        $last  = $this->leader?->last_name ?? '';

        return $first || $last ? trim("$first $last") : 'Sem Líder';
    }
    public function dailyTeamMembers(): HasMany
    {
        return $this->hasMany(DailyTeamMember::class);
    }

    public function dailyTeamVehicles(): HasMany
    {
        return $this->hasMany(DailyTeamVehicles::class);
    }

    public function subTeams(): HasMany
    {
        return $this->hasMany(SubTeam::class, 'daily_team_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = Employee::where('user_id', auth()->id())->value('id');
            }
        });
    }

    public function getTeamNameLabelAttribute(): string
    {
        return $this->teamname?->name ?? '—';
    }
}
