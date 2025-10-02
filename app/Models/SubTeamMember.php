<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\EmployeeAssignmentHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class SubTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_team_id',
        'employee_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // 🔁 Relación con subequipo
    public function subTeam(): BelongsTo
    {
        return $this->belongsTo(SubTeam::class);
    }

    // 🔁 Relación con colaborador
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ✅ Validaciones automáticas al guardar
    protected static function booted(): void
    {
        static::saving(function ($item) {
            $date = $item->subTeam->work_date instanceof Carbon
                ? $item->subTeam->work_date
                : Carbon::parse($item->subTeam->work_date);

            $badge = EmployeeAssignmentHelper::getDetailedStatusForDate($item->employee_id, $date);

            // Si está ausente, no permitir
            if ($badge['status'] === 'ausente') {
                $motivo = is_array($badge['motivo']) ? ($badge['motivo']['label'] ?? 'Indisponível') : ($badge['motivo'] ?? 'Indisponível');
                throw ValidationException::withMessages([
                    'employee_id' => 'Este colaborador não está disponível nessa data (' . $motivo . ').',
                ]);
            }

            // Si está asignado, permitir si es el mismo miembro que se está editando
            if ($badge['status'] === 'asignado') {
                if ($item->exists && isset($badge['member_id']) && $badge['member_id'] == $item->id) {
                    return;
                }

                if ($item->sub_team_id && isset($badge['equipo']) && isset($badge['role'])) {
                    $subequipo = $item->subTeam;
                    if ($subequipo && $subequipo->id == $item->sub_team_id) {
                        return;
                    }
                }

                throw ValidationException::withMessages([
                    'employee_id' => 'Este colaborador já está atribuído a outro grupo nessa data.',
                ]);
            }
        });
    }
}
