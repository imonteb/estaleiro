<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pep extends Model
{
    protected $table = 'peps';

    protected $fillable = [
        'code',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Mutador para garantizar que el código comience con "P." y esté en mayúsculas.
     */

    public function teams()
    {
        return $this->hasMany(TeamName::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    public function dayAssignments()
    {
        return $this->hasMany(PepDayAssignment::class);
    }
    public function setCodeAttribute($value)
    {
        $code = strtoupper(trim($value));
        $this->attributes['code'] = str_starts_with($code, 'P.') ? $code : 'P.' . $code;
    }
}
