<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use Filament\Resources\Pages\Page;
use App\Models\DailyTeam;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use App\Helpers\EstaleiroHelper;
use App\Models\Employee;
use App\Rules\EmployeeUniqueForDate;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class TeamCard extends Page
{

    protected static string $resource = DailyTeamResource::class;
    protected static string $view = 'filament.resources.daily-team-resource.pages.team-card';
    protected static ?string $title = 'Conformação de Equipas';

    public $sourceDate;
    public $showEditModal = false;
    public $editTeamId = null;
    public $editTeamData = [
        'team_name_id' => null,
        'pep_id' => null,
        'work_type' => null,
        'location' => null,
        'leader_id' => null,
        'work_date' => null,
        'dailyTeamMembers' => [],
        'dailyTeamVehicles' => [],
        'subTeams' => [],
    ];
    public $showDeleteModal = false;
    public $teamIdToDelete = null;

    public  function getHeaderActions(): array
    {
        return [
            Action::make('importarPlantillasParaDia')
                ->label('Importar todas as equipas modelo para um dia')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    DatePicker::make('work_date')
                        ->label('Data de Trabalho')
                        ->default(fn() => now()->addDay()->format('Y-m-d'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $existe = \App\Models\DailyTeam::where('is_template', false)
                        ->whereDate('work_date', $data['work_date'])
                        ->exists();
                    if ($existe) {
                        Notification::make()
                            ->title('Já existe uma equipa para esse dia')
                            ->danger()
                            ->send();
                        return;
                    }
                    $modelos = \App\Models\DailyTeam::where('is_template', true)->get();
                    foreach ($modelos as $record) {
                        $newTeam = $record->replicate();
                        $newTeam->is_template = false;
                        $newTeam->work_date = $data['work_date'];
                        $newTeam->published = false;
                        $newTeam->save();
                        foreach ($record->dailyTeamMembers as $member) {
                            $newTeam->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                        }
                        foreach ($record->dailyTeamVehicles as $vehicle) {
                            $newTeam->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                        }
                        foreach ($record->subTeams as $sub) {
                            $subTeam = $newTeam->subTeams()->create([
                                'sub_team_name_id' => $sub->sub_team_name_id,
                                'leader_id' => $sub->leader_id,
                            ]);
                            foreach ($sub->members as $member) {
                                $subTeam->members()->create(['employee_id' => $member->employee_id]);
                            }
                            foreach ($sub->vehicles as $vehicle) {
                                $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                            }
                        }
                    }
                }),
            Action::make('duplicarEquiposDiaAnterior')
                ->label('Duplicar todos los equipos del día anterior')
                ->icon('heroicon-o-document-duplicate')
                ->form([
                    DatePicker::make('source_date')
                        ->label('Día a duplicar')
                        ->default(fn() => \App\Filament\Resources\DailyTeamResource\Pages\TeamCard::getLastBusinessDay())
                        ->required(),
                    DatePicker::make('target_date')
                        ->label('Nuevo día')
                        ->default(fn() => \App\Filament\Resources\DailyTeamResource\Pages\TeamCard::getNextBusinessDay())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $equipos = \App\Models\DailyTeam::where('is_template', false)
                        ->whereDate('work_date', $data['source_date'])
                        ->get();
                    foreach ($equipos as $record) {
                        $newTeam = $record->replicate();
                        $newTeam->work_date = $data['target_date'];
                        $newTeam->published = false;
                        $newTeam->save();
                        foreach ($record->dailyTeamMembers as $member) {
                            $newTeam->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                        }
                        foreach ($record->dailyTeamVehicles as $vehicle) {
                            $newTeam->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                        }
                        foreach ($record->subTeams as $sub) {
                            $subTeam = $newTeam->subTeams()->create([
                                'sub_team_name_id' => $sub->sub_team_name_id,
                                'leader_id' => $sub->leader_id,
                            ]);
                            foreach ($sub->members as $member) {
                                $subTeam->members()->create(['employee_id' => $member->employee_id]);
                            }
                            foreach ($sub->vehicles as $vehicle) {
                                $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                            }
                        }
                    }
                }),
        ];
    }


    public function addVehicle()
    {
        $this->editTeamData['dailyTeamVehicles'][] = ['vehicle_id' => null];
    }
    public function removeVehicle($index)
    {
        unset($this->editTeamData['dailyTeamVehicles'][$index]);
        $this->editTeamData['dailyTeamVehicles'] = array_values($this->editTeamData['dailyTeamVehicles']);
    }
    public function addSubTeam()
    {
        $this->editTeamData['subTeams'][] = [
            'sub_team_name_id' => null,
            'leader_id' => null,
            'members' => [],
            'vehicles' => [],
        ];
    }
    public function removeSubTeam($index)
    {
        unset($this->editTeamData['subTeams'][$index]);
        $this->editTeamData['subTeams'] = array_values($this->editTeamData['subTeams']);
    }
    public function addSubMember($subIndex)
    {
        $this->editTeamData['subTeams'][$subIndex]['members'][] = ['employee_id' => null];
    }
    public function removeSubMember($subIndex, $memberIndex)
    {
        unset($this->editTeamData['subTeams'][$subIndex]['members'][$memberIndex]);
        $this->editTeamData['subTeams'][$subIndex]['members'] = array_values($this->editTeamData['subTeams'][$subIndex]['members']);
    }
    public function addSubVehicle($subIndex)
    {
        $this->editTeamData['subTeams'][$subIndex]['vehicles'][] = ['vehicle_id' => null];
    }
    public function removeSubVehicle($subIndex, $vehicleIndex)
    {
        unset($this->editTeamData['subTeams'][$subIndex]['vehicles'][$vehicleIndex]);
        $this->editTeamData['subTeams'][$subIndex]['vehicles'] = array_values($this->editTeamData['subTeams'][$subIndex]['vehicles']);
    }


    // Helpers
    public function getTeamsProperty()
    {
        return DailyTeam::with([
            'teamname',
            'pep',
            'leader',
            'dailyTeamMembers.employee',
            'dailyTeamVehicles.vehicle',
            'subTeams.subTeamName',
            'subTeams.leader',
            'subTeams.members.employee',
            'subTeams.vehicles.vehicle',
        ])
            ->whereDate('work_date', $this->sourceDate)
            ->leftJoin('teams_names_tables', 'daily_teams.team_name_id', '=', 'teams_names_tables.id')
            ->orderBy('teams_names_tables.name')
            ->select('daily_teams.*')
            ->get();
    }

    public function mount()
    {
        if (!$this->sourceDate) {
            $last = \App\Models\DailyTeam::orderByDesc('work_date')->first();
            $this->sourceDate = $last ? \Carbon\Carbon::parse($last->work_date)->format('Y-m-d') : now()->format('Y-m-d');
        } else {
            $this->sourceDate = \Carbon\Carbon::parse($this->sourceDate)->format('Y-m-d');
        }
    }

    public function updatedSourceDate($value)
    {
        $this->sourceDate = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function confirmDeleteTeam($teamId)
    {
        $this->teamIdToDelete = $teamId;
        $this->showDeleteModal = true;
    }

    public function deleteTeam()
    {
        $team = \App\Models\DailyTeam::find($this->teamIdToDelete);
        if ($team) {
            $team->delete();
            Notification::make()
                ->title('Equipo eliminado correctamente')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('No se encontró el equipo')
                ->danger()
                ->send();
        }
        $this->showDeleteModal = false;
        $this->teamIdToDelete = null;
    }

    public function getTeamActions($teamId): array
    {
        return [
            Action::make('deleteTeam')
                ->label('Eliminar')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () use ($teamId) {
                    $team = \App\Models\DailyTeam::find($teamId);
                    if ($team) {
                        $team->delete();
                        Notification::make()
                            ->title('Equipo eliminado corretamente')
                            ->success()
                            ->send();
                        // Refrescar la página o la lista si es necesario
                        $this->redirect(request()->header('Referer'));
                    } else {
                        Notification::make()
                            ->title('No se encontró el equipo')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
    // Devuelve el último día hábil (anterior a hoy, saltando fines de semana)
    public static function getLastBusinessDay(): string
    {
        $date = now()->subDay();
        while (in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            $date->subDay();
        }
        return $date->format('Y-m-d');
    }

    // Devuelve el próximo día hábil (después de hoy, saltando fines de semana)
    public static function getNextBusinessDay(): string
    {
        $date = now()->addDay();
        while (in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            $date->addDay();
        }
        return $date->format('Y-m-d');
    }

    public function openEditModal($teamId = null)
    {
        $this->editTeamId = $teamId;
        $team = \App\Models\DailyTeam::with([
            'dailyTeamMembers',
            'dailyTeamVehicles',
            'subTeams.members',
            'subTeams.vehicles',
        ])->find($teamId);

        if ($team) {
            $this->editTeamData = [
                'team_name_id' => $team->team_name_id,
                'pep_id' => $team->pep_id,
                'work_type' => $team->work_type,
                'location' => $team->location,
                'leader_id' => $team->leader_id,
                'work_date' => $team->work_date,
                'dailyTeamMembers' => $team->dailyTeamMembers->map(fn($m) => ['employee_id' => $m->employee_id])->toArray(),
                'dailyTeamVehicles' => $team->dailyTeamVehicles->map(fn($v) => ['vehicle_id' => $v->vehicle_id])->toArray(),
                'subTeams' => $team->subTeams->map(function($sub) {
                    return [
                        'sub_team_name_id' => $sub->sub_team_name_id,
                        'leader_id' => $sub->leader_id,
                        'members' => $sub->members->map(fn($m) => ['employee_id' => $m->employee_id])->toArray(),
                        'vehicles' => $sub->vehicles->map(fn($v) => ['vehicle_id' => $v->vehicle_id])->toArray(),
                    ];
                })->toArray(),
            ];
        }

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    /**
     * Guarda la edición del equipo con validación robusta (empleados y vehículos, incluyendo subgrupos).
     */
    public function saveEditTeam()
    {
        // Validación básica de campos principales
        $this->validate([
            'editTeamData.team_name_id' => ['required', 'exists:team_names,id'],
            'editTeamData.pep_id' => ['required', 'exists:peps,id'],
            'editTeamData.leader_id' => [
                'required',
                'exists:employees,id',
                new \App\Rules\EmployeeUniqueForDate(
                    $this->editTeamData['work_date'],
                    $this->editTeamId,
                    null,
                    false // Cambia a true si es plantilla
                ),
            ],
            'editTeamData.work_date' => ['required', 'date'],
        ]);

        // Validar miembros directos
        foreach ($this->editTeamData['dailyTeamMembers'] as $idx => $member) {
            $this->validate([
                "editTeamData.dailyTeamMembers.$idx.employee_id" => [
                    'required',
                    'exists:employees,id',
                    new \App\Rules\EmployeeUniqueForDate(
                        $this->editTeamData['work_date'],
                        $this->editTeamId,
                        null,
                        false
                    ),
                ],
            ]);
        }

        // Validar vehículos directos
        foreach ($this->editTeamData['dailyTeamVehicles'] as $idx => $vehicle) {
            $this->validate([
                "editTeamData.dailyTeamVehicles.$idx.vehicle_id" => [
                    'required',
                    'exists:vehicles,id',
                    new \App\Rules\VehicleUniqueForDate(
                        $this->editTeamData['work_date'],
                        $this->editTeamId,
                        null
                    ),
                ],
            ]);
        }

        // Validar subgrupos (líder, miembros, vehículos)
        foreach ($this->editTeamData['subTeams'] as $subIdx => $sub) {
            // Líder del subgrupo
            if (!empty($sub['leader_id'])) {
                $this->validate([
                    "editTeamData.subTeams.$subIdx.leader_id" => [
                        'exists:employees,id',
                        new \App\Rules\EmployeeUniqueForDate(
                            $this->editTeamData['work_date'],
                            $this->editTeamId,
                            null,
                            false
                        ),
                    ],
                ]);
            }
            // Miembros del subgrupo
            foreach (($sub['members'] ?? []) as $mIdx => $member) {
                $this->validate([
                    "editTeamData.subTeams.$subIdx.members.$mIdx.employee_id" => [
                        'required',
                        'exists:employees,id',
                        new \App\Rules\EmployeeUniqueForDate(
                            $this->editTeamData['work_date'],
                            $this->editTeamId,
                            null,
                            false
                        ),
                    ],
                ]);
            }
            // Vehículos del subgrupo
            foreach (($sub['vehicles'] ?? []) as $vIdx => $vehicle) {
                $this->validate([
                    "editTeamData.subTeams.$subIdx.vehicles.$vIdx.vehicle_id" => [
                        'required',
                        'exists:vehicles,id',
                        new \App\Rules\VehicleUniqueForDate(
                            $this->editTeamData['work_date'],
                            $this->editTeamId,
                            null
                        ),
                    ],
                ]);
            }
        }

        // Guardar los datos editados en la base de datos
        $team = DailyTeam::find($this->editTeamId);
        if (!$team) {
            Notification::make()
                ->title('Equipo no encontrado')
                ->danger()
                ->send();
            return;
        }

        $team->update([
            'team_name_id' => $this->editTeamData['team_name_id'],
            'pep_id' => $this->editTeamData['pep_id'],
            'work_type' => $this->editTeamData['work_type'],
            'location' => $this->editTeamData['location'],
            'leader_id' => $this->editTeamData['leader_id'],
            'work_date' => $this->editTeamData['work_date'],
        ]);

        // Sincronizar miembros directos
        $team->dailyTeamMembers()->delete();
        foreach ($this->editTeamData['dailyTeamMembers'] as $member) {
            $team->dailyTeamMembers()->create(['employee_id' => $member['employee_id']]);
        }

        // Sincronizar vehículos directos
        $team->dailyTeamVehicles()->delete();
        foreach ($this->editTeamData['dailyTeamVehicles'] as $vehicle) {
            $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
        }

        // Sincronizar subgrupos
        $team->subTeams()->delete();
        foreach ($this->editTeamData['subTeams'] as $sub) {
            $subTeam = $team->subTeams()->create([
                'sub_team_name_id' => $sub['sub_team_name_id'] ?? null,
                'leader_id' => $sub['leader_id'] ?? null,
                'work_date' => $this->editTeamData['work_date'],
            ]);
            // Miembros
            foreach (($sub['members'] ?? []) as $member) {
                $subTeam->members()->create(['employee_id' => $member['employee_id']]);
            }
            // Vehículos
            foreach (($sub['vehicles'] ?? []) as $vehicle) {
                $subTeam->vehicles()->create(['vehicle_id' => $vehicle['vehicle_id']]);
            }
        }

        Notification::make()
            ->title('Equipo actualizado corretamente')
            ->success()
            ->send();

        $this->closeEditModal();
    }
}
