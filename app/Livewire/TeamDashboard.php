<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;

class TeamDashboard extends Component
{
    public $selectedDate;
    public $teams = [];
public $showTemplateModal = false;
public $templateDate;
public $availableTemplateDates = [];
public $newDateForTemplate;

public function mount()
{
    $this->selectedDate = dTeams::max('work_date');
    $this->loadTeams();
    $this->availableTemplateDates = Team::distinct()->pluck('work_date')->sortDesc()->values()->toArray();
}

public function createFromTemplate()
{
    $templateTeams = Team::with(['employees', 'vehicles'])
        ->where('work_date', $this->templateDate)
        ->get();

    foreach ($templateTeams as $team) {
        $newTeam = $team->replicate();
        $newTeam->work_date = $this->newDateForTemplate;
        $newTeam->save();

        $newTeam->employees()->sync($team->employees->pluck('id'));
        $newTeam->vehicles()->sync($team->vehicles->pluck('id'));
    }

    $this->selectedDate = $this->newDateForTemplate;
    $this->loadTeams();
    $this->showTemplateModal = false;
    session()->flash('message', 'Equipos creados desde plantilla correctamente.');
}


    public function updatedSelectedDate()
    {
        $this->loadTeams();
    }

    public function loadTeams()
    {
        $this->teams = Team::with(['pep', 'employees', 'teamleader', 'vehicles'])
            ->where('work_date', $this->selectedDate)
            ->get();
    }
    public function render()
    {
        return view('livewire.team-dashboard');
    }
}
