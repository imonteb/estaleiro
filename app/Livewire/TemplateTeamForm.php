<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;


class TemplateTeamForm extends Component
{
    public $team_name_id;
    public $work_type;
    public $location;
    public $leader_id;
    public $pep_id;
    public $published = false;

    public $teamNames = [];
    public $employees = [];
    public $peps = [];
    public $vehicles = [];
    public $subTeamNames = [];

    public $dailyTeamMembers = [];
    public $dailyTeamVehicles = [];
    public $subTeams = [];

    public $editingTeamId = null;
    public $showDeleteTemplateModal = false;
    public $deleteTemplateId = null;
    public $showNewPepInput = false;
    public $newPepCode = '';
    public $newPepDescription = '';

    public $showNewSubTeamNameInput = [];
    public $newSubTeamName = '';

    public $showNewTeamNameInput = false;
    public $newTeamName = '';

    public $showNewTeamNameModal = false;

    public $vehicleSearches = [];

    public function openDeleteTemplateModal($id)
    {
        $this->deleteTemplateId = $id;
        $this->showDeleteTemplateModal = true;
    }

    public function closeDeleteTemplateModal()
    {
        $this->deleteTemplateId = null;
        $this->showDeleteTemplateModal = false;
    }

    public function deleteTemplate()
    {
        $team = \App\Models\DailyTeam::findOrFail($this->deleteTemplateId);
        // Eliminar todos los relacionados
        $team->dailyTeamMembers()->delete();
        $team->dailyTeamVehicles()->delete();
        foreach ($team->subTeams as $subTeam) {
            $subTeam->members()->delete();
            $subTeam->vehicles()->delete();
            $subTeam->delete();
        }
        $team->delete();
        $this->closeDeleteTemplateModal();
        $this->reloadEmployees();
        $this->dispatch('closeSlideover');
        session()->flash('success', 'A equipa modelo foi eliminada permanentemente.');
    }
    /**
     * Recarga la lista de empleados para el modal de edición de plantilla
     */
    public function reloadEmployees()
    {
        $this->employees = \App\Models\Employee::active()->get();
    }

    // Para edición y borrado de nombres de equipo
    public $showEditTeamNameModal = false;
    public $editTeamNameId = null;
    public $editTeamNameValue = '';
    public $showDeleteTeamNameModal = false;
    public $deleteTeamNameId = null;

    public function openEditTeamNameModal($id)
    {
        $team = \App\Models\TeamName::findOrFail($id);
        $this->editTeamNameId = $id;
        $this->editTeamNameValue = $team->name;
        $this->showEditTeamNameModal = true;
    }

    public function closeEditTeamNameModal()
    {
        $this->showEditTeamNameModal = false;
        $this->editTeamNameId = null;
        $this->editTeamNameValue = '';
    }

    public function saveEditTeamName()
    {
        $this->validate([
            'editTeamNameValue' => 'required|string|max:255|unique:teams_names_tables,name,' . $this->editTeamNameId,
        ]);
        $team = \App\Models\TeamName::findOrFail($this->editTeamNameId);
        $team->update(['name' => $this->editTeamNameValue]);
        $this->teamNames = \App\Models\TeamName::all();
        $this->closeEditTeamNameModal();
        session()->flash('success', 'Nome de equipa editado!');
    }

    public function openDeleteTeamNameModal($id)
    {
        $this->deleteTeamNameId = $id;
        $this->showDeleteTeamNameModal = true;
    }

    public function closeDeleteTeamNameModal()
    {
        $this->deleteTeamNameId = null;
        $this->showDeleteTeamNameModal = false;
    }

    public function deleteTeamName()
    {
        $team = \App\Models\TeamName::findOrFail($this->deleteTeamNameId);
        $team->delete();
        $this->teamNames = \App\Models\TeamName::all();
        $this->closeDeleteTeamNameModal();
        session()->flash('success', 'Nome de equipa eliminado!');
    }



    public function openNewTeamNameModal()
    {
        $this->showNewTeamNameModal = true;
        $this->newTeamName = '';
    }

    public function closeNewTeamNameModal()
    {
        $this->showNewTeamNameModal = false;
        $this->newTeamName = '';
    }


    public function showNewPepInput()
    {
        $this->showNewPepInput = true;
    }
    public function hideNewPepInput()
    {
        $this->showNewPepInput = false;
        $this->newPepCode = '';
        $this->newPepDescription = '';
    }
    public function saveNewPep()
    {
        $this->validate([
            'newPepCode' => 'required|string|max:50|unique:peps,code',
            'newPepDescription' => 'required|string|max:255',
        ]);
        $pep = \App\Models\Pep::create(['code' => $this->newPepCode, 'description' => $this->newPepDescription]);
        $this->peps = \App\Models\Pep::active()->get();
        $this->pep_id = $pep->id;
        $this->hideNewPepInput();
    }

    public function showNewSubTeamNameInput($index)
    {
        $this->showNewSubTeamNameInput[$index] = true;
    }
    public function hideNewSubTeamNameInput($index)
    {
        $this->showNewSubTeamNameInput[$index] = false;
        $this->newSubTeamName = '';
    }
    public function saveNewSubTeamName($index)
    {
        $this->validate([
            'newSubTeamName' => 'required|string|max:255|unique:sub_team_names,name',
        ]);
        $subTeamName = \App\Models\SubTeamName::create(['name' => $this->newSubTeamName]);
        $this->subTeamNames = \App\Models\SubTeamName::all();
        $this->subTeams[$index]['sub_team_name_id'] = $subTeamName->id;
        $this->hideNewSubTeamNameInput($index);
    }




    public function showNewTeamNameInput()
    {
        $this->showNewTeamNameInput = true;
    }

    public function hideNewTeamNameInput()
    {
        $this->showNewTeamNameInput = false;
        $this->newTeamName = '';
    }



    public function mount($editingTeamId = null)
    {

        $this->teamNames = \App\Models\TeamName::all();
        $this->employees = \App\Models\Employee::active()->get();
        $this->peps = \App\Models\Pep::active()->get();
        $this->subTeamNames = \App\Models\SubTeamName::all();
        $this->vehicles = \App\Models\Vehicle::all();
        $this->editingTeamId = $editingTeamId;
        if ($editingTeamId) {
            $team = \App\Models\DailyTeam::with([
                'dailyTeamMembers.employee',
                'dailyTeamVehicles',
                'subTeams.members.employee',
                'subTeams.leader',
                'subTeams.subTeamName',
                'subTeams.vehicles'
            ])->where('is_template', true)->find($editingTeamId);
            if ($team) {
                $this->team_name_id = $team->team_name_id;
                $this->work_type = $team->work_type;
                $this->location = $team->location;
                $this->leader_id = $team->leader_id;
                $this->pep_id = $team->pep_id;
                $this->published = $team->published;
                $this->dailyTeamMembers = $team->dailyTeamMembers->map(function ($m) {
                    return ['employee_id' => $m->employee_id];
                })->toArray();
                $this->dailyTeamVehicles = $team->dailyTeamVehicles->map(function ($v) {
                    return ['vehicle_id' => $v->vehicle_id];
                })->toArray();
                $this->subTeams = $team->subTeams->map(function ($sub) {
                    return [
                        'sub_team_name_id' => $sub->sub_team_name_id,
                        'leader_id' => $sub->leader_id,
                        'members' => $sub->members->map(function ($m) {
                            return ['employee_id' => $m->employee_id];
                        })->toArray(),
                        'vehicles' => $sub->vehicles->map(function ($v) {
                            return ['vehicle_id' => $v->vehicle_id];
                        })->toArray(),
                    ];
                })->toArray();
            }
        }
    }
    // Métodos para vehículos del equipo principal
    public function addDailyTeamVehicle()
    {
        $this->dailyTeamVehicles[] = ['vehicle_id' => null];
    }
    public function removeDailyTeamVehicle($i)
    {
        array_splice($this->dailyTeamVehicles, $i, 1);
    }

    // Métodos para vehículos de subequipo
    public function addSubTeamVehicle($s)
    {
        $this->subTeams[$s]['vehicles'][] = ['vehicle_id' => null];
    }
    public function removeSubTeamVehicle($s, $v)
    {
        array_splice($this->subTeams[$s]['vehicles'], $v, 1);
    }

    // Métodos para edición total
    public function addDailyTeamMember()
    {
        $this->dailyTeamMembers[] = ['employee_id' => null];
    }
    public function removeDailyTeamMember($i)
    {
        array_splice($this->dailyTeamMembers, $i, 1);
    }
    public function addSubTeam()
    {
        $this->subTeams[] = [
            'sub_team_name_id' => null,
            'leader_id' => null,
            'members' => [],
        ];
    }
    public function removeSubTeam($i)
    {
        array_splice($this->subTeams, $i, 1);
    }
    public function addSubMember($s)
    {
        $this->subTeams[$s]['members'][] = ['employee_id' => null];
    }
    public function removeSubMember($s, $m)
    {
        array_splice($this->subTeams[$s]['members'], $m, 1);
    }

    public function save()
    {
        $rules = [
            'team_name_id' => [
                'required',
                'exists:teams_names_tables,id',
                new \App\Rules\UniqueTeamNamePerDate('2000-01-01', $this->editingTeamId),
            ],
            'work_type' => 'required|string',
            'location' => 'required|string',
            'leader_id' => [
                'required',
                'exists:employees,id',
                // Ignorar el equipo actual en edición
                new \App\Rules\EmployeeUniqueForDate('2000-01-01', $this->editingTeamId, null, true)
            ],
            'pep_id' => 'required|exists:peps,id',
        ];

        // Reglas dinámicas para vehículos
        foreach ($this->dailyTeamVehicles as $i => $vehicle) {
            $rules["dailyTeamVehicles.$i.vehicle_id"] = 'required|exists:vehicles,id';
        }
        foreach ($this->subTeams as $s => $sub) {
            if (isset($sub['vehicles'])) {
                foreach ($sub['vehicles'] as $v => $vehicle) {
                    $rules["subTeams.$s.vehicles.$v.vehicle_id"] = 'required|exists:vehicles,id';
                }
            }
        }


        // Validación extra: evitar duplicidad de vehículos entre equipo principal y subgrupos
        $vehiculosEquipo = collect($this->dailyTeamVehicles)->pluck('vehicle_id')->filter()->toArray();
        $vehiculosSubgrupos = collect($this->subTeams)->flatMap(function ($sub) {
            return collect($sub['vehicles'] ?? [])->pluck('vehicle_id')->filter()->toArray();
        })->toArray();
        $duplicados = array_intersect($vehiculosEquipo, $vehiculosSubgrupos);
        if (count($duplicados) > 0) {
            foreach ($duplicados as $vid) {
                $vehiculo = \App\Models\Vehicle::find($vid);
                $placa = $vehiculo ? $vehiculo->car_plate : $vid;
                $this->addError('duplicated_vehicle', "O veículo $placa está atribuído ao equipa principal e a um subgrupo. Remova a duplicidade.");
            }
            return;
        }
        $this->validate($rules);

        if ($this->editingTeamId) {
            $team = \App\Models\DailyTeam::find($this->editingTeamId);
            if ($team) {
                $team->update([
                    'team_name_id' => $this->team_name_id,
                    'work_type' => $this->work_type,
                    'location' => $this->location,
                    'leader_id' => $this->leader_id,
                    'pep_id' => $this->pep_id,
                    'published' => $this->published,
                ]);
                // Actualizar miembros directos
                $team->dailyTeamMembers()->delete();
                foreach ($this->dailyTeamMembers as $member) {
                    if ($member['employee_id']) {
                        $team->dailyTeamMembers()->create(['employee_id' => $member['employee_id']]);
                    }
                }
                // Actualizar vehículos directos
                $team->dailyTeamVehicles()->delete();
                foreach ($this->dailyTeamVehicles as $vehicle) {
                    if ($vehicle['vehicle_id']) {
                        $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
                    }
                }
                // Actualizar subgrupos
                $team->subTeams()->delete();
                foreach ($this->subTeams as $sub) {
                    $subTeam = $team->subTeams()->create([
                        'sub_team_name_id' => $sub['sub_team_name_id'],
                        'leader_id' => $sub['leader_id'],
                        'work_date' => '2000-01-01',
                    ]);
                    foreach ($sub['members'] as $member) {
                        if ($member['employee_id']) {
                            $subTeam->members()->create(['employee_id' => $member['employee_id']]);
                        }
                    }
                    // Actualizar vehículos del subgrupo
                    if (isset($sub['vehicles'])) {
                        foreach ($sub['vehicles'] as $vehicle) {
                            if ($vehicle['vehicle_id']) {
                                $subTeam->vehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
                            }
                        }
                    }
                }
                session()->flash('success', 'Equipa modelo atualizada com sucesso!');
            }
        } else {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $this->team_name_id,
                'work_type' => $this->work_type,
                'location' => $this->location,
                'leader_id' => $this->leader_id,
                'pep_id' => $this->pep_id,
                'is_template' => true,
                'published' => $this->published,
                'work_date' => '2000-01-01',
            ]);
            foreach ($this->dailyTeamMembers as $member) {
                if ($member['employee_id']) {
                    $team->dailyTeamMembers()->create(['employee_id' => $member['employee_id']]);
                }
            }
            foreach ($this->dailyTeamVehicles as $vehicle) {
                if ($vehicle['vehicle_id']) {
                    $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
                }
            }
            foreach ($this->subTeams as $sub) {
                $subTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $sub['sub_team_name_id'],
                    'leader_id' => $sub['leader_id'],
                    'work_date' => '2000-01-01',
                ]);
                foreach ($sub['members'] as $member) {
                    if ($member['employee_id']) {
                        $subTeam->members()->create(['employee_id' => $member['employee_id']]);
                    }
                }
                if (isset($sub['vehicles'])) {
                    foreach ($sub['vehicles'] as $vehicle) {
                        if ($vehicle['vehicle_id']) {
                            $subTeam->vehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
                        }
                    }
                }
            }
            session()->flash('success', 'Equipa modelo criada com sucesso!');
        }
        $this->reset(['team_name_id', 'work_type', 'location', 'leader_id', 'pep_id', 'published', 'dailyTeamMembers', 'dailyTeamVehicles', 'subTeams']);
        $this->dispatch('closeSlideover');
        $this->dispatch('refreshTeams');
    }

    public function cancel()
    {
        $this->reset(['team_name_id', 'work_type', 'location', 'leader_id', 'pep_id', 'published', 'dailyTeamMembers', 'dailyTeamVehicles', 'subTeams']);
        $this->dispatch('closeSlideover');
    }

    protected $listeners = [
        'teamNamesUpdated' => 'refreshTeamNames',
        'pepsUpdated' => 'refreshPeps',
        'subTeamNamesUpdated' => 'refreshSubTeamNames',
    ];

    public function refreshTeamNames()
    {
        $this->teamNames = \App\Models\TeamName::all();
    }
    public function refreshPeps()
    {
        $this->peps = \App\Models\Pep::active()->get();
    }
    public function refreshSubTeamNames()
    {
        $this->subTeamNames = \App\Models\SubTeamName::all();
    }


    public function render()
    {
        return view('livewire.template-team-form', [
            'teamNames' => $this->teamNames,
            'employees' => $this->employees,
            'peps' => $this->peps,
            'subTeamNames' => $this->subTeamNames,
            'vehicles' => $this->vehicles,
            'editingTeamId' => $this->editingTeamId,
            'date' => '2000-01-01', // Fecha fija para plantillas
        ]);
    }
}
