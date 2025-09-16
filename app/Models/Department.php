<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'departments';

    protected $fillable = [
        'name',
    ];

    public function position()
    {
        return $this->hasMany(Position::class);
    }
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, Position::class);
    }
}
