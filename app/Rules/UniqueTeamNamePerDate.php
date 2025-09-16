<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DailyTeam;
use Illuminate\Support\Facades\Log;

class UniqueTeamNamePerDate implements Rule
{
    protected $date;
    protected $ignoreId;

    public function __construct($date, $ignoreId = null)
    {
        $this->date = $date;
        $this->ignoreId = $ignoreId;
    }

    public function passes($attribute, $value)
    {
        
        $query = DailyTeam::whereDate('work_date', $this->date)
            ->where('team_name_id', $value);
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }
        return !$query->exists();
    }

    public function message()
    {

        return 'Já existe uma equipa com este nome para o dia selecionado.';
    }
}
