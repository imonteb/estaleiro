<?php

namespace App\Models;

use App\Enums\Sex;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'employee_number',
        'last_name',
        'sex',
        'phone',
        'position_id',
        'active',
        'status_type_id',
        'image_url',
    ];

    protected $with = ['user'];

    protected $casts = [
        'active' => 'boolean',
        'sex'    => Sex::class,
    ];



    // 🔁 Relación con usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 🔁 Relación con cargo o posición
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, Position::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->employee_number} {$this->user->name} {$this->last_name}";
    }

    // 🔁 Historial de estados
    public function statuses(): HasMany
    {
        return $this->hasMany(EmployeeStatus::class);
    }


    // Delegadores a EmployeeAssignmentHelper
    public function isAbsentOn($date): bool
    {
        return \App\Helpers\EmployeeAssignmentHelper::isAbsentOn($this->id, $date);
    }

    

    /**
     * Genera el label para selects, permitiendo pasar parámetros de exclusión y modo plantilla.
     *
     * @param string|Carbon $date
     * @param bool $withBadge
     * @param bool|null $isTemplate
     * @param int|null $excludeTeamId
     * @param int|null $excludeMemberId
     * @return string
     */
    public function getLabelParaSelect($date, $withBadge = true, $isTemplate = null, $excludeTeamId = null, $excludeMemberId = null): string
    {
        return \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
            $this->id,
            $date,
            $withBadge,
            $isTemplate,
            $excludeTeamId,
            $excludeMemberId
        );
    }

    // 🔍 Scope para empleados ativos
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // 🔍 Verifica si está disponível
    public function isAvailableOn($date): bool
    {
        return $this->active && !$this->isAbsentOn($date);
    }

    // Relación con DailyTeamMember
    public function dailyTeamMembers(): HasMany
    {
        return $this->hasMany(DailyTeamMember::class);
    }

    // Relación con StatusType
    public function statusType(): BelongsTo
    {
        return $this->belongsTo(StatusType::class);
    }
}
