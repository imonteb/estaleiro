<?php

namespace App\Livewire\DailyTeams;

use Livewire\Component;
use Filament\Forms;
use App\Models\Employee;
use App\Models\TeamName;
use App\Models\Pep;
use App\Models\Vehicle;
use App\Models\SubTeamName;



class TeamFormModal extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $teamId = null;
    public $showModal = false;

        public function mount($teamId = null)
    {
        $this->teamId = $teamId;
        if ($teamId) {
            $team = \App\Models\DailyTeam::find($teamId);
            $this->form->fill($team ? $team->toArray() : []);
        } else {
            $this->form->fill([]);
        }
    }


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('team_name_id')
                ->label('Nome da Equipa')
                ->options(TeamName::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('pep_id')
                ->label('Código PEP')
                ->options(Pep::all()->pluck('code', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('work_type')
                ->label('Tipo de Trabalho')
                ->required(),
            Forms\Components\TextInput::make('location')
                ->label('Localização')
                ->required(),
            Forms\Components\Select::make('leader_id')
                ->label('Líder')
                ->options(Employee::all()->pluck('full_name', 'id'))
                ->searchable()
                ->required(),

            // Miembros principales
            Forms\Components\Repeater::make('dailyTeamMembers')
                ->label('Colaboradores')
                ->schema([
                    Forms\Components\Select::make('employee_id')
                        ->label('Colaborador')
                        ->options(Employee::all()->pluck('full_name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->minItems(0)
                ->columns(1),

            // Vehículos principales
            Forms\Components\Repeater::make('dailyTeamVehicles')
                ->label('Vehículos')
                ->schema([
                    Forms\Components\Select::make('vehicle_id')
                        ->label('Vehículo')
                        ->options(Vehicle::all()->pluck('car_plate', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->minItems(0)
                ->columns(1),

            // Subequipos
            Forms\Components\Repeater::make('subTeams')
                ->label('Subequipos')
                ->schema([
                    Forms\Components\Select::make('sub_team_name_id')
                        ->label('Nome subequipa')
                        ->options(SubTeamName::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('leader_id')
                        ->label('Líder')
                        ->options(Employee::all()->pluck('full_name', 'id'))
                        ->searchable()
                        ->required(),
                    // Miembros del subequipo
                    Forms\Components\Repeater::make('members')
                        ->label('Miembros')
                        ->schema([
                            Forms\Components\Select::make('employee_id')
                                ->label('Colaborador')
                                ->options(Employee::all()->pluck('full_name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->minItems(0)
                        ->columns(1),
                    // Vehículos del subequipo
                    Forms\Components\Repeater::make('vehicles')
                        ->label('Vehículos')
                        ->schema([
                            Forms\Components\Select::make('vehicle_id')
                                ->label('Vehículo')
                                ->options(Vehicle::all()->pluck('car_plate', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->minItems(0)
                        ->columns(1),
                ])
                ->minItems(0)
                ->columns(1),
        ];
    }

    protected $listeners = ['showTeamFormModal'];

    public function showTeamFormModal($teamId = null)
    {
        $this->teamId = $teamId;
        if ($teamId) {
            $team = \App\Models\DailyTeam::find($teamId);
            $this->form->fill($team ? $team->toArray() : []);
        } else {
            $this->form->fill([]);
        }
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->form->getState();
        if ($this->teamId) {
            $team = \App\Models\DailyTeam::find($this->teamId);
            $team->update($data);
        } else {
            \App\Models\DailyTeam::create($data);
        }
        $this->showModal = false;
        $this->emitUp('refreshTeams');
    }

    public function render()
    {
        return view('livewire.daily-teams.team-form-modal');
    }
}
