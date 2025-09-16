<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class VehicleBrand extends Model
{
    use HasFactory;

     /**
      * The table associated with the model.
      *
      * @var string
      */
    protected $table = 'vehicle_brands';

     protected $fillable = ['name'];


    /**
     * Get the vehicles for the vehicle brand.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

}
