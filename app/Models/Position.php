<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Position extends Model
{
    use HasFactory, Notifiable;


    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'positions';
    protected $fillable = [
        'department_id',
        'name',
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
