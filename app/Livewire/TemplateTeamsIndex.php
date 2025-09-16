<?php

namespace App\Livewire;

use Livewire\Component;

class TemplateTeamsIndex extends Component
{
    public $employees = [];
    public $peps = [];
    public $teams;
    public $showSlideover = false;
    public $editingTeamId = null;

    public function mount()
    {
        // Cargar todos los equipos plantilla
        $this->teams = \App\Models\DailyTeam::where('is_template', true)->get();
        $this->peps = \App\Models\Pep::active()->get();
        $this->employees = \App\Models\Employee::active()->get();
    }
    public function updateWorkType($teamId, $workType)
    {
        $team = \App\Models\DailyTeam::find($teamId);
        if ($team && $team->is_template) {
            $team->work_type = $workType;
            $team->save();
            $this->refreshTeams();
        }
    }

    public function updateLocation($teamId, $location)
    {
        $team = \App\Models\DailyTeam::find($teamId);
        if ($team && $team->is_template) {
            $team->location = $location;
            $team->save();
            $this->refreshTeams();
        }
    }

    public function updateLeader($teamId, $leaderId)
    {
        $team = \App\Models\DailyTeam::find($teamId);
        if ($team && $team->is_template) {
            $team->leader_id = $leaderId;
            $team->save();
            $this->refreshTeams();
        }
    }
    public function updatePep($teamId, $pepId)
    {
        $team = \App\Models\DailyTeam::find($teamId);
        if ($team && $team->is_template) {
            $team->pep_id = $pepId;
            $team->save();
            $this->refreshTeams();
        }
    }

    public function createCard()
    {
        $this->showSlideover = true;
        $this->editingTeamId = null;
        $this->dispatch('openSlideover');
    }

    public function editTeam($teamId)
    {
        $this->editingTeamId = $teamId;
        $this->showSlideover = true;
        $this->dispatch('openSlideover');
    }

    public function closeSlideover()
    {
        $this->showSlideover = false;
        $this->editingTeamId = null;
    }

    protected $listeners = ['refreshTeams' => 'refreshTeams', 'closeSlideover' => 'closeSlideover'];

    public function refreshTeams()
    {
        $this->teams = \App\Models\DailyTeam::where('is_template', true)->get();
        $this->showSlideover = false;
        $this->editingTeamId = null;
    }

    public function render()
    {
        // Renderizar la vista con los equipos plantilla
        return view('livewire.template-teams-index', ['teams' => $this->teams, 'showSlideover' => $this->showSlideover]);
    }
}
