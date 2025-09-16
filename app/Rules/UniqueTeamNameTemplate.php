<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DailyTeam;

class UniqueTeamNameTemplate implements Rule
{
    public function passes($attribute, $value)
    {
        // Verifica se já existe uma plantilla com esse team_name_id
        return !DailyTeam::where('is_template', true)
            ->where('team_name_id', $value)
            ->exists();
    }

    public function message()
    {
        return 'Já existe uma plantilla para esta equipa.';
    }
}
