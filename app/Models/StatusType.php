<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusType extends Model
{
    use HasFactory;

protected $table = 'status_types';
    protected $fillable = ['name', 'color', 'is_default'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(EmployeeStatus::class);
    }

}
