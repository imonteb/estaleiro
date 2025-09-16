<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubTeamName extends Model
{
    use HasFactory;

    protected $table = 'sub_team_names';

    protected $fillable = [
        'name',
        'active',
    ];

    public function subTeams(): HasMany
    {
        return $this->hasMany(SubTeam::class);
    }


}
