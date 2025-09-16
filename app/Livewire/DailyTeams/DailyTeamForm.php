<?php
namespace App\Livewire\DailyTeams;

use Livewire\Component;
use App\Models\TeamName;
use App\Models\Employee;
use App\Models\Pep;
use App\Models\Vehicle;
use App\Models\SubTeamName;

class DailyTeamForm extends Component
{
    public $editingTeamId = null;
    public $date = null;
    public $teamNames = [];
    public $team_name_id = null;
    public $showNewTeamNameModal = false;
    public $showEditTeamNameModal = false;
    public $showDeleteTeamNameModal = false;
    public $employees = [];
    public $peps = [];
    public $vehicles = [];
    public $subTeamNames = [];
    public $work_type = '';
    public $location = '';
    public $leader_id = null;
    public $pep_id = null;
    public $dailyTeamMembers = [];
    public $dailyTeamVehicles = [];
    public $subTeams = [];
    public $collaborators = [];
    public $subGroups = [];
    public $showNewPepInput = false;


    public $description = '';
    public $selectedVehicles = [];

    public $showDeleteDailyTeamModal = false;
    public $deleteDailyTeamId = null;
    public $editTeamNameId = null;
    public $editTeamNameValue = '';
    public $deleteTeamNameId = null;
    public $newPepCode = '';
    public $newPepDescription = '';
    public $showNewSubTeamNameInput = [];
    public $newSubTeamName = '';
    public $showNewTeamNameInput = false;
    public $newTeamName = '';




public function importLastWorkDay($targetDate = null)
    {
        if (!$targetDate) {
            $targetDate = $this->getNextBusinessDay();
        }
        $existing = \App\Models\DailyTeam::where('work_date', $targetDate)->where('is_template', false)->count();
        if ($existing > 0) {
            $this->dispatch('closeSlideover');
            $this->dispatch('refreshTeams');
            session()->flash('error', 'Já existem equipas no dia selecionado. A importação foi cancelada.');
            return;
        }
        // Buscar el último día trabajado con equipos
        $lastTeams = \App\Models\DailyTeam::where('is_template', false)
            ->where('work_date', '<', $targetDate)
            ->orderBy('work_date', 'desc')
            ->get();
        $imported = 0;
        foreach ($lastTeams as $template) {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $template->team_name_id,
                'work_type' => $template->work_type,
                'location' => $template->location,
                'leader_id' => $template->leader_id,
                'pep_id' => $template->pep_id,
                'is_template' => false,
                'published' => $template->published,
                'work_date' => $targetDate,
            ]);
            foreach ($template->dailyTeamMembers as $member) {
                if ($member->employee_id) {
                    $team->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                }
            }
            foreach ($template->dailyTeamVehicles as $vehicle) {
                if ($vehicle->vehicle_id) {
                    $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                }
            }
            foreach ($template->subTeams as $sub) {
                $subTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $sub->sub_team_name_id,
                    'leader_id' => $sub->leader_id,
                    'work_date' => $targetDate,
                ]);
                foreach ($sub->members as $member) {
                    if ($member->employee_id) {
                        $subTeam->members()->create(['employee_id' => $member->employee_id]);
                    }
                }
                foreach ($sub->vehicles as $vehicle) {
                    if ($vehicle->vehicle_id) {
                        $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                    }
                }
            }
            $imported++;
        }
        if ($imported > 0) {
            session()->flash('success', "$imported equipa(s) importadas para $targetDate!");
        } else {
            session()->flash('error', 'Nenhum dia anterior encontrado para importar.');
        }
        $this->dispatch('closeSlideover');
        $this->dispatch('refreshTeams');
    }


    public function openDeleteDailyTeamModal($id)
    {
        $this->deleteDailyTeamId = $id;
        $this->showDeleteDailyTeamModal = true;
    }

    public function closeDeleteDailyTeamModal()
    {
        $this->deleteDailyTeamId = null;
        $this->showDeleteDailyTeamModal = false;
    }
//////////////////
    public function deleteDailyTeam()
    {
        $team = \App\Models\DailyTeam::findOrFail($this->deleteDailyTeamId);
        $team->dailyTeamMembers()->delete();
        $team->dailyTeamVehicles()->delete();
        foreach ($team->subTeams as $subTeam) {
            $subTeam->members()->delete();
            $subTeam->vehicles()->delete();
            $subTeam->delete();
        }
    $team->delete();
    $this->closeDeleteDailyTeamModal();
    $this->reloadEmployees();
    $this->dispatch('closeSlideover');
    $this->dispatch('employeesUpdated');
    session()->flash('success', 'A equipa diária foi eliminada permanentemente.');
}

    /**
     * Recarga la lista de empleados para el modal de edición
     */
    public function reloadEmployees()
    {
        $this->employees = \App\Models\Employee::all();
    }


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

    public $published = false;

    public function mount($editingTeamId = null, $date = null)
    {
        $this->teamNames = TeamName::all();
        $this->reloadEmployees();
        $this->peps = Pep::all();
        $this->subTeamNames = SubTeamName::all();
        $this->vehicles = Vehicle::all();
        $this->editingTeamId = $editingTeamId;
        // Usar el parámetro $date si se recibe
        if ($date) {
            $this->date = $date;
        }
        if ($editingTeamId) {
            $team = \App\Models\DailyTeam::with([
                'dailyTeamMembers.employee',
                'dailyTeamVehicles',
                'subTeams.members.employee',
                'subTeams.leader',
                'subTeams.subTeamName',
                'subTeams.vehicles'
            ])->find($editingTeamId);
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

    public function addDailyTeamVehicle()
    {
        $this->dailyTeamVehicles[] = ['vehicle_id' => null];
    }
    public function removeDailyTeamVehicle($i)
    {
        array_splice($this->dailyTeamVehicles, $i, 1);
    }

    public function addSubTeamVehicle($s)
    {
        $this->subTeams[$s]['vehicles'][] = ['vehicle_id' => null];
    }
    public function removeSubTeamVehicle($s, $v)
    {
        array_splice($this->subTeams[$s]['vehicles'], $v, 1);
    }

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
                new \App\Rules\UniqueTeamNamePerDate($this->date ?? date('Y-m-d'), $this->editingTeamId),
            ],
            'work_type' => 'required|string',
            'location' => 'required|string',
            'leader_id' => [
                'required',
                'exists:employees,id',
                new \App\Rules\EmployeeUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, null, true)
            ],
            'pep_id' => 'required|exists:peps,id',
        ];

        // Validar miembros del equipo principal
        foreach ($this->dailyTeamMembers as $i => $member) {
            $rules["dailyTeamMembers.$i.employee_id"] = [
                'required',
                'exists:employees,id',
                new \App\Rules\EmployeeUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, null, false)
            ];
        }

        // Validar vehículos del equipo principal
        foreach ($this->dailyTeamVehicles as $i => $vehicle) {
            $rules["dailyTeamVehicles.$i.vehicle_id"] = [
                'required',
                'exists:vehicles,id',
                new \App\Rules\VehicleUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, null)
            ];
        }

        // Validar subequipos: líder y miembros
        foreach ($this->subTeams as $s => $sub) {
            $rules["subTeams.$s.leader_id"] = [
                'required',
                'exists:employees,id',
                new \App\Rules\EmployeeUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, $s, true)
            ];
            if (isset($sub['members'])) {
                foreach ($sub['members'] as $m => $member) {
                    $rules["subTeams.$s.members.$m.employee_id"] = [
                        'required',
                        'exists:employees,id',
                        new \App\Rules\EmployeeUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, $s, false)
                    ];
                }
            }
            if (isset($sub['vehicles'])) {
                foreach ($sub['vehicles'] as $v => $vehicle) {
                    $rules["subTeams.$s.vehicles.$v.vehicle_id"] = [
                        'required',
                        'exists:vehicles,id',
                        new \App\Rules\VehicleUniqueForDate($this->date ?? date('Y-m-d'), $this->editingTeamId, null)
                    ];
                }
            }
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
                $team->dailyTeamMembers()->delete();
                foreach ($this->dailyTeamMembers as $member) {
                    if ($member['employee_id']) {
                        $team->dailyTeamMembers()->create(['employee_id' => $member['employee_id']]);
                    }
                }
                $team->dailyTeamVehicles()->delete();
                foreach ($this->dailyTeamVehicles as $vehicle) {
                    if ($vehicle['vehicle_id']) {
                        $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
                    }
                }
                $team->subTeams()->delete();
                    foreach ($this->subTeams as $sub) {
                        $subTeam = $team->subTeams()->create([
                            'sub_team_name_id' => $sub['sub_team_name_id'],
                            'leader_id' => $sub['leader_id'],
                            'work_date' => $this->date ?? date('Y-m-d'),
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
                session()->flash('success', 'Equipa diária atualizada com sucesso!');
            }
        } else {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $this->team_name_id,
                'work_type' => $this->work_type,
                'location' => $this->location,
                'leader_id' => $this->leader_id,
                'pep_id' => $this->pep_id,
                'is_template' => false,
                'published' => $this->published,
                'work_date' => $this->date ?? date('Y-m-d'),
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
                    'work_date' => $this->date ?? date('Y-m-d'),
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
            session()->flash('success', 'Equipa diária criada com sucesso!');
        }
        $this->reset(['team_name_id', 'work_type', 'location', 'leader_id', 'pep_id', 'published', 'dailyTeamMembers', 'dailyTeamVehicles', 'subTeams', 'date']);
        $this->dispatch('closeSlideover');
        $this->dispatch('refreshTeams');
    }

    public function cancel()
    {
        $this->reset(['team_name_id', 'work_type', 'location', 'leader_id', 'pep_id', 'published', 'dailyTeamMembers', 'dailyTeamVehicles', 'subTeams', 'date']);
        $this->dispatch('closeSlideover');
    }

    protected $listeners = [
        'teamNamesUpdated' => 'refreshTeamNames',
        'pepsUpdated' => 'refreshPeps',
        'subTeamNamesUpdated' => 'refreshSubTeamNames',
    ];

    public function refreshTeamNames() {
        $this->teamNames = TeamName::all();
    }
    public function refreshPeps() {
        $this->peps = Pep::all();
    }
    public function refreshSubTeamNames() {
        $this->subTeamNames = SubTeamName::all();
    }

    public function importTemplate($templateDate = null)
    {
        // Calcular el próximo día útil si no se envía fecha
        if (!$templateDate) {
            $templateDate = $this->getNextBusinessDay();
        }
        // Verificar si ya existen equipos en la fecha destino
        $existing = \App\Models\DailyTeam::where('work_date', $templateDate)->where('is_template', false)->count();
        if ($existing > 0) {
            session()->flash('error', 'Já existem equipas no dia selecionado. A importação foi cancelada.');
            return;
        }
        // Obtener todos los equipos plantilla
        $templates = \App\Models\DailyTeam::where('is_template', true)->get();
        $imported = 0;
        foreach ($templates as $template) {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $template->team_name_id,
                'work_type' => $template->work_type,
                'location' => $template->location,
                'leader_id' => $template->leader_id,
                'pep_id' => $template->pep_id,
                'is_template' => false,
                'published' => $template->published,
                'work_date' => $templateDate,
            ]);
            foreach ($template->dailyTeamMembers as $member) {
                if ($member->employee_id) {
                    $team->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                }
            }
            foreach ($template->dailyTeamVehicles as $vehicle) {
                if ($vehicle->vehicle_id) {
                    $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                }
            }
            foreach ($template->subTeams as $sub) {
                $subTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $sub->sub_team_name_id,
                    'leader_id' => $sub->leader_id,
                    'work_date' => $templateDate,
                ]);
                foreach ($sub->members as $member) {
                    if ($member->employee_id) {
                        $subTeam->members()->create(['employee_id' => $member->employee_id]);
                    }
                }
                foreach ($sub->vehicles as $vehicle) {
                    if ($vehicle->vehicle_id) {
                        $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                    }
                }
            }
            $imported++;
        }
        if ($imported > 0) {
            session()->flash('success', "$imported equipa(s) importadas para $templateDate!");
        } else {
            session()->flash('error', 'Nenhum template encontrado para importar.');
        }
    }

    // Utilidad para calcular el próximo día útil
    public function getNextBusinessDay()
    {
        $date = date('Y-m-d', strtotime('+1 day'));
        // Si es sábado o domingo, avanzar hasta lunes
        while (date('N', strtotime($date)) >= 6) {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        return $date;
    }

    public function render()
    {
        return view('livewire.daily-teams.daily-team-form', [
            'teamNames' => $this->teamNames,
            'employees' => $this->employees,
            'peps' => $this->peps,
            'subTeamNames' => $this->subTeamNames,
            'vehicles' => $this->vehicles,
            'editingTeamId' => $this->editingTeamId,
            'date' => $this->date,
        ]);
    }
}



