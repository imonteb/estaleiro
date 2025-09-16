<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTeamVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_team_id',
        'vehicle_id',
        'status',
    ];

    public function subTeam(): BelongsTo
    {
        return $this->belongsTo(SubTeam::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }


}
 