<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    use HasFactory;
    protected $table = 'vehicle_maintenances';
    protected $fillable = [
        'vehicle_id',
        'fecha',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }


}
