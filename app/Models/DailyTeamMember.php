<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;
use App\Helpers\EmployeeAssignmentHelper;

class DailyTeamMember extends Model
{
    use HasFactory;

    protected $table = 'daily_team_members';

    protected $fillable = [
        'daily_team_id',
        'employee_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // 🔁 Relaciones
    public function dailyTeam(): BelongsTo
    {
        return $this->belongsTo(DailyTeam::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function subTeam(): BelongsTo
    {
        return $this->belongsTo(SubTeam::class);
    }

    // 📛 Nombre completo del colaborador
    public function getEmployeeNameAttribute(): ?string
    {
        $number = $this->employee?->employee_number ?? '';
        $first  = $this->employee?->user?->name ?? '';
        $last   = $this->employee?->last_name ?? '';

        return trim("$number $first $last") ?: 'Sem nome';
    }

    // 🔎 Scope para buscar membros por equipo
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('daily_team_id', $teamId);
    }

    // ✅ Validaciones en guardado
    protected static function booted(): void
    {
        static::saving(function ($item) {

            $date = $item->dailyTeam->work_date instanceof Carbon
                ? $item->dailyTeam->work_date
                : Carbon::parse($item->dailyTeam->work_date);

            // 🛡️ Verifica disponibilidad
            $badge = EmployeeAssignmentHelper::getDetailedStatusForDate($item->employee_id, $date);

            // Si está ausente, no permitir
            if ($badge['status'] === 'ausente') {
                $motivo = is_array($badge['motivo']) ? ($badge['motivo']['label'] ?? 'Indisponível') : ($badge['motivo'] ?? 'Indisponível');
                throw ValidationException::withMessages([
                    'employee_id' => 'Este colaborador não está disponível nessa data (' . $motivo . ').',
                ]);
            }
            // Si está asignado, permitir si es el mismo equipo (update)
            if ($badge['status'] === 'asignado') {
                if ($item->daily_team_id && isset($badge['equipo']) && isset($badge['rol'])) {
                    $equipo = $item->dailyTeam;
                    // Permitir si es el mismo equipo y el mismo rol
                    if ($equipo && $equipo->id == $item->daily_team_id) {
                        return;
                    }
                }
                throw ValidationException::withMessages([
                    'employee_id' => 'Este colaborador já está atribuído a outro grupo nessa data.',
                ]);
            }

            // 🔁 Verifica si ya está asignado como líder o membro en otro equipo
            $usedElsewhere = DailyTeam::whereDate('work_date', $date)
                ->where('id', '!=', $item->daily_team_id)
                ->where('leader_id', $item->employee_id)
                ->exists()
                ||
                DailyTeamMember::where('id', '!=', $item->id)
                ->where('employee_id', $item->employee_id)
                ->whereHas('dailyTeam', fn($q) => $q->whereDate('work_date', $date))
                ->exists();

            if ($usedElsewhere) {
                throw ValidationException::withMessages([
                    'employee_id' => 'Este colaborador já está atribuído a outro time nessa data.',
                ]);
            }
        });
    }


}
