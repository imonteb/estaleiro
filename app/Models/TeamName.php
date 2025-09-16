<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamName extends Model
{
    use HasFactory;
    protected $table = 'teams_names_tables';
    protected $fillable = ['name'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function dailyTeams()
    {
        return $this->hasMany(DailyTeam::class, 'team_name_id');
    }
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

}
