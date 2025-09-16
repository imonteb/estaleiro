<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleIncident extends Model
{
    use HasFactory;
    protected $table = 'vehicle_incidents';
    protected $fillable = [
        'vehicle_id',
        'fecha',
        'tipo',
        'descripcion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }


}
