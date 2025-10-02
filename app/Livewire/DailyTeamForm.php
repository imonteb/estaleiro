<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DailyTeam;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Pep;
use App\Models\TeamName;
use App\Models\SubTeamName;
use Illuminate\Support\Collection;

class DailyTeamForm extends Component
{
    public $teamId; // Id del equipo que se edita, null si es creación
    public $dailyTeam;

    public $team_name_id;
    public $pep_id;
    public $work_type;
    public $location;
    public $leader_id;

    public $dailyTeamMembers = [];
    public $dailyTeamVehicles = [];
    public $subTeams = [];

    public function mount($teamId = null)
    {
        $this->teamId = $teamId;

        if ($teamId) {
            $this->dailyTeam = DailyTeam::with(['dailyTeamMembers.employee', 'dailyTeamVehicles.vehicle', 'subTeams.members.employee', 'subTeams.vehicles.vehicle'])->find($teamId);

            $this->team_name_id = $this->dailyTeam->team_name_id;
            $this->pep_id = $this->dailyTeam->pep_id;
            $this->work_type = $this->dailyTeam->work_type;
            $this->location = $this->dailyTeam->location;
            $this->leader_id = $this->dailyTeam->leader_id;

            $this->dailyTeamMembers = $this->dailyTeam->dailyTeamMembers->map(fn($m) => ['employee_id' => $m->employee_id])->toArray();
            $this->dailyTeamVehicles = $this->dailyTeam->dailyTeamVehicles->map(fn($v) => ['vehicle_id' => $v->vehicle_id])->toArray();
            $this->subTeams = $this->dailyTeam->subTeams->map(function($sub){
                return [
                    'sub_team_name_id' => $sub->sub_team_name_id,
                    'leader_id' => $sub->leader_id,
                    'members' => $sub->members->map(fn($m) => ['employee_id' => $m->employee_id])->toArray(),
                    'vehicles' => $sub->vehicles->map(fn($v) => ['vehicle_id' => $v->vehicle_id])->toArray(),
                ];
            })->toArray();
        } else {
            $this->dailyTeam = new DailyTeam();
        }
    }

    public function save()
    {
        $this->validate([
            'team_name_id' => 'required|exists:team_names,id',
            'pep_id' => 'required|exists:peps,id',
            'leader_id' => 'nullable|exists:employees,id',
            'dailyTeamMembers.*.employee_id' => 'required|exists:employees,id',
            'dailyTeamVehicles.*.vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $team = $this->dailyTeam ?? new DailyTeam();
        $team->team_name_id = $this->team_name_id;
        $team->pep_id = $this->pep_id;
        $team->work_type = $this->work_type;
        $team->location = $this->location;
        $team->leader_id = $this->leader_id;
        $team->is_template = false;
        $team->work_date = now(); // o pasar desde parent si quieres fecha dinámica
        $team->save();

        // Sync miembros
        $team->dailyTeamMembers()->delete();
        foreach ($this->dailyTeamMembers as $m) {
            $team->dailyTeamMembers()->create($m);
        }

        // Sync vehículos
        $team->dailyTeamVehicles()->delete();
        foreach ($this->dailyTeamVehicles as $v) {
            $team->dailyTeamVehicles()->create($v);
        }

        // Sync subgrupos
        $team->subTeams()->delete();
        foreach ($this->subTeams as $sub) {
            $subModel = $team->subTeams()->create([
                'sub_team_name_id' => $sub['sub_team_name_id'],
                'leader_id' => $sub['leader_id'],
                'work_date' => $team->work_date,
            ]);

            foreach ($sub['members'] as $m) {
                $subModel->members()->create($m);
            }
            foreach ($sub['vehicles'] as $v) {
                $subModel->vehicles()->create($v);
            }
        }

        $this->emitUp('refreshTeams'); // Para que el padre recargue la lista
        $this->dispatchBrowserEvent('closeSlideover'); // Cierra el Slideover
    }

    public function render()
    {
        return view('livewire.daily-teams.daily-team-form', [
            'teamNames' => TeamName::all(),
            'peps' => Pep::all(),
            'employees' => Employee::all(),
            'vehicles' => Vehicle::all(),
            'subTeamNames' => SubTeamName::all(),
        ]);
    }
}
