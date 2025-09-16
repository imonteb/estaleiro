<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;

use App\Models\DailyTeam;

class PublishedDailyTeams extends Component
{
    public $date;
    public $teams = [];

    public function mount($date = null)
    {
        if ($date) {
            $this->date = $date;
        } else {
            // Get the latest published day from the table
            $published = \App\Models\PublishedOperationsDay::orderByDesc('date')->first();
            $this->date = $published?->date;
        }
        $this->loadTeams();
    }

    public function loadTeams()
    {
        $this->teams = DailyTeam::with([
            'teamname',
            'pep',
            'leader',
            'dailyTeamMembers.employee',
            'dailyTeamVehicles.vehicle.vehicleBrand',
            'subTeams.subTeamName',
            'subTeams.leader',
            'subTeams.members.employee',
            'subTeams.vehicles.vehicle'
        ])->where('work_date', $this->date)->get();
    }

    public function render()
    {
        return view('livewire.operaciones.published-daily-teams');
    }
}
