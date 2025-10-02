<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyTeamResource\Pages;
use App\Models\DailyTeam;
use App\Models\DailyTeamVehicles;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Pep;
use App\Models\TeamName;
use App\Models\SubTeamName;
use App\Helpers\VehicleAssignmentHelper;
use App\Helpers\EmployeeAssignmentHelper;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class DailyTeamResource extends Resource
{


    protected static ?string $model = DailyTeam::class;
    protected static ?string $navigationGroup = 'Gestão de Equipas';
    protected static ?string $label = 'Equipa Diária';
    protected static ?string $pluralLabel = 'Equipas Diárias';
    protected static ?string $title = 'Equipas Diárias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('id')->dehydrated(),
            Forms\Components\Hidden::make('is_template')->default(true)->dehydrated(),

            // Si es plantilla, work_date oculto y fijo; si no, editable y default próximo día laborable
            Forms\Components\DatePicker::make('work_date')
                ->label('Data do Trabalho')
                ->required()
                ->default(fn($record) => $record?->work_date ?? \App\Helpers\WorkdayHelper::getNextBusinessDay())
                // Si es plantilla, fuerza la fecha fija
                ->dehydrateStateUsing(fn($state, $get) => $get('is_template') ? '2000-01-01' : $state)
                ->dehydrated(true),

            Section::make('Informações da Equipa')
                ->description('Informações básicas sobre a equipa')
                ->schema([
                    Select::make('team_name_id')
                        ->label('Nome da Equipa*')
                        ->placeholder('Seleciona a Equipa')
                        ->required()
                        ->relationship('teamname', 'name')
                        ->searchable(true)
                        ->dehydrated(true)
                        ->disabled(fn($get) => !$get('is_template'))
                        ->dehydrateStateUsing(fn($state, $record) => $state ?? $record?->team_name_id)
                        ->rules([
                            fn($get) => [new \App\Rules\UniqueTeamNamePerDate(
                                $get('work_date'),
                                $get('id')
                            )]
                        ])
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nome da Equipa')
                                ->required()
                                ->maxLength(255)
                                ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                        ]),

                    Select::make('pep_id')
                        ->relationship('pep', 'code')
                        ->label('Código PEP')
                        ->searchable(true)
                        ->dehydrated(true)
                        ->placeholder('Seleciona o Código PEP')
                        ->createOptionForm(
                            fn(Forms\Form $form) => $form
                                ->schema([
                                    Forms\Components\TextInput::make('code')
                                        ->label('Código PEP')
                                        ->placeholder('P.000.000/000/1')
                                        ->required()
                                        ->maxLength(20)
                                        ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                                    Forms\Components\TextInput::make('description')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Toggle::make('active')
                                        ->required()
                                        ->default(true),
                                ])
                        ),

                    TextInput::make('work_type')
                        ->label('Tipo de Trabalho')
                        ->dehydrated(true),
                    TextInput::make('location')
                        ->label('Localização')
                        ->dehydrated(true),
                    //--------Lider
                    Select::make('leader_id')
                        ->label('Líder')
                        ->placeholder('Seleciona o Líder')
                        ->searchable()
                        ->options(fn(callable $get, $record) => self::getEmployeeOptions($get, $record))
                        ->rules([
                            fn(callable $get, $record) => [
                                new \App\Rules\EmployeeUniqueForDate(
                                    $get('work_date') ?? $record?->work_date,
                                    $get('id'),
                                    $record?->id
                                ),
                            ],
                        ])
                        ->columnSpan(2),
                ])->columns(3)
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'fi-section-header-bg daily-team-section-header',
                ]),
            //--------Membros e Veículos
            Section::make('Membros e Veículos')
                ->description('Membros da equipa direta e veículos')
                ->schema([
                    //--------Membros
                    TableRepeater::make('dailyTeamMembers')
                        ->headers([Header::make('Membros'), Header::make('Ações')])
                        ->relationship()
                        ->label('Membros Diretos')
                        ->reactive()
                        ->schema([
                            Select::make('employee_id')
                                ->label('Colaborador')
                                ->placeholder('Seleciona o Colaborador')
                                ->required()
                                ->dehydrated()
                                ->searchable()
                                ->options(fn(callable $get, $record) => self::getEmployeeOptions($get, $record, '../../'))
                                ->rules([
                                    fn(callable $get, $record) => [
                                        new \App\Rules\EmployeeUniqueForDate(
                                            $get('../../work_date') ?? $record?->dailyTeam?->work_date,
                                            $get('../../id') ?? $record?->daily_team_id,
                                            $record?->id
                                        ),
                                    ],
                                ])
                                ->live(debounce: 500)
                                ->reactive(),
                        ])
                        ->columns(1),

                    TableRepeater::make('dailyTeamVehicles')
                        ->headers([Header::make('Veículos'), Header::make('Ações')])
                        ->relationship()
                        ->label('Veículos Diretos')
                        ->schema([
                            Select::make('vehicle_id')
                                ->label('Veículo')
                                ->placeholder('Seleciona o Veículo')
                                ->required()
                                ->searchable(true)
                                ->options(fn(callable $get, $record) => self::getVehicleOptions($get, $record, '../../'))
                                ->rules([
                                    fn(callable $get, $record) => [new \App\Rules\VehicleUniqueForDate(
                                        $get('../../work_date') ?? $record?->dailyTeam?->work_date,
                                        $get('../../id') ?? $record?->daily_team_id,
                                        $record?->id
                                    )]
                                ])
                                ->dehydrated(true),
                        ])
                        ->columns(1)
                ])
                ->columns(2)
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'fi-section-header-bg daily-team-section-header',
                ]),
            //--------Subgrupos
            Section::make('Subgrupos')
                ->description('Membros e Veículos do subgrupo')
                ->schema([
                    Repeater::make('subTeams')
                        ->label('Subgrupos')
                        ->relationship('subTeams')
                        ->schema([
                            Forms\Components\Hidden::make('work_date')
                                ->default(fn($get) => $get('../../work_date'))
                                ->dehydrated(true),
                            Select::make('sub_team_name_id')
                                ->label('Nome do Subgrupo*')
                                ->placeholder('Seleciona o Subgrupo')
                                ->options(
                                    SubTeamName::all()->mapWithKeys(fn($s) => [
                                        $s->id => $s->name ?: '—'
                                    ])->toArray()
                                )
                                ->searchable(true)
                                ->required(),
                            //--------Subgrupos - leader_id
                            Select::make('leader_id')
                                ->label('Líder do Subgrupo')
                                ->placeholder('Seleciona o Líder do Subgrupo')
                                ->searchable()
                                ->dehydrated(true)
                                ->required()
                                ->options(fn(callable $get, $record) => self::getEmployeeOptions($get, $record, '../../'))
                                ->rules([
                                    fn(callable $get, $record) => [
                                        new \App\Rules\EmployeeUniqueForDate(
                                            $get('../../work_date') ?? $record?->dailyTeam?->work_date,
                                            $get('../../id') ?? $record?->daily_team_id,
                                            $record?->id,
                                            \App\Models\DailyTeam::find($get('../../id'))?->is_template ?? false
                                        ),
                                    ],
                                ])
                                ->live(debounce: 500)
                                ->reactive(),

                            TableRepeater::make('members')
                                ->headers([Header::make('Membros'), Header::make('Ações')])
                                ->relationship()
                                ->label('Membros do Subgrupo')
                                ->schema([
                                    Select::make('employee_id')
                                        ->label('Colaborador*')
                                        ->searchable()
                                        ->options(fn(callable $get, $record) => self::getEmployeeOptions($get, $record, '../../../../'))
                                        ->rules([
                                            fn(callable $get, $record) => [
                                                new \App\Rules\EmployeeUniqueForDate(
                                                    $get('../../../../work_date') ?? $record?->subTeam?->dailyTeam?->work_date,
                                                    $get('../../../../id') ?? $record?->subTeam?->daily_team_id,
                                                    $record?->id,
                                                    \App\Models\DailyTeam::find($get('../../../../id'))?->is_template ?? false
                                                ),
                                            ],
                                        ])
                                        ->live(debounce: 500)
                                        ->reactive()
                                        ->dehydrated(true)
                                        ->required()
                                ])
                                ->columns(1),

                            TableRepeater::make('vehicles')
                                ->headers([Header::make('Veículos'), Header::make('Ações')])
                                ->relationship()
                                ->label('Veículos do Subgrupo')
                                ->schema([
                                    Forms\Components\Hidden::make('work_date')
                                        ->default(fn($get) => $get('../../../work_date'))
                                        ->dehydrated(true),

                                    Select::make('vehicle_id')
                                        ->label('Veículo*')
                                        ->searchable(true)
                                        ->options(fn(callable $get, $record) => self::getVehicleOptions($get, $record, '../../../../'))
                                        ->rules([
                                            fn(callable $get, $record) => [new \App\Rules\VehicleUniqueForDate(
                                                $get('../../../../work_date') ?? $record?->subTeam?->dailyTeam?->work_date,
                                                $get('../../../../id') ?? $record?->subTeam?->daily_team_id,
                                                $record?->id
                                            )]
                                        ])
                                        ->dehydrated(true)
                                        ->required(),
                                ])
                                ->columns(1)
                                ->extraAttributes([]),
                        ])
                        ->columns(2)
                        ->collapsible(),
                ])->columnSpanFull()
                ->extraAttributes([
                    'class' => 'fi-section-header-bg daily-team-section-header',
                ]),
        ]);
    }



    public static function table(Table $table): Table
    {
        $table->modifyQueryUsing(function ($query) {
            return $query->with([
                'dailyTeamMembers.employee',
                'dailyTeamVehicles.vehicle',
                'teamname',
                'pep',
                'leader.user',
                'subTeams.members.employee',
                'subTeams.vehicles.vehicle',
                'subTeams.leader.user',
            ]);
        });
        return $table
            ->columns([
                View::make('filament.resources.daily-teams.cards')
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->defaultSort('team_name_id')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->modalHeading('Editar Equipa Diária')
                    ->mutateFormDataUsing(function (array $data, Model $record): array {
                        // Asegurar que tenemos los datos correctos del template
                        if ($data['work_date'] === '2000-01-01') {
                            $data['is_template'] = true;
                        } else {
                            $data['is_template'] = false;
                        }
                        return $data;
                    })
                    ->mutateRecordDataUsing(function (array $data, Model $record): array {
                        // Cargar las relaciones necesarias para el formulario
                        $record->load([
                            'dailyTeamMembers.employee.user',
                            'dailyTeamVehicles.vehicle',
                            'subTeams.members.employee.user',
                            'subTeams.vehicles.vehicle',
                            'subTeams.subTeamName',
                            'teamname',
                            'leader.user',
                            'pep'
                        ]);
                        
                        return $data;
                    })
                    ->successNotificationTitle('Equipa atualizada com sucesso!')
                    ->modalSubmitActionLabel('Guardar Alterações'),
            ])
            ->filters([
                Tables\Filters\Filter::make('work_date')
                    ->form([
                        /* Forms\Components\Toggle::make('show_templates')
                            ->label('Mostrar solo plantillas'), */
                        DatePicker::make('work_date')
                            ->label('Data do Trabalho')
                            ->default(fn($get) => $get('show_templates') ? Carbon::create(2000, 1, 1) : \App\Models\DailyTeam::where('is_template', false)->max('work_date'))
                            ->disabled(fn($get) => $get('show_templates')),
                    ])
                    /* ->query(function ($query, array $data) {
                        if (!empty($data['show_templates'])) {
                            $query->where('is_template', true)
                                ->whereDate('work_date', '2000-01-01');
                        } else {
                            $query->where('is_template', false);
                            if (!empty($data['work_date'])) {
                                $query->whereDate('work_date', $data['work_date']);
                            }
                        }
                    }) */

                    ->query(function ($query, array $data) {
                        $query->where('is_template', false);
                        if (!empty($data['work_date'])) {
                            $query->whereDate('work_date', $data['work_date']);
                        }
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Criar Equipa')
                    ->slideOver(),
                Tables\Actions\Action::make('importarModelosParaDia')
                    ->label('Importar Modelos para um Dia')
                    ->icon('heroicon-o-document-arrow-down')
                    ->form([
                        DatePicker::make('work_date')
                            ->label('Data de Trabalho')
                            ->default(fn() => \App\Helpers\WorkdayHelper::getNextBusinessDay())
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Importar todos os modelos como equipas diárias para a data selecionada
                        $plantillas = \App\Models\DailyTeam::where('is_template', true)->get();
                        foreach ($plantillas as $record) {
                            $newTeam = $record->replicate();
                            $newTeam->is_template = false;
                            $newTeam->work_date = $data['work_date'];
                            $newTeam->published = false;
                            $newTeam->save();
                            foreach ($record->dailyTeamMembers as $member) {
                                $newTeam->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                            }
                            foreach ($record->dailyTeamVehicles as $vehicle) {
                                $conflict = VehicleAssignmentHelper::getDetailedStatusForDate(
                                    $vehicle->vehicle_id,
                                    $data['work_date'],
                                    null,
                                    null
                                );
                                if ($conflict['status'] === 'asignado') {
                                    throw new \Exception("Veículo já atribuído à equipa {$conflict['team_name']} em {$conflict['date']} (importação de modelo).");
                                }
                                $newTeam->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                            }
                            foreach ($record->subTeams as $sub) {
                                $subTeam = $newTeam->subTeams()->create([
                                    'sub_team_name_id' => $sub->sub_team_name_id,
                                    'leader_id' => $sub->leader_id,
                                    'work_date' => $data['work_date'], // Propagar la fecha
                                ]);
                                foreach ($sub->members as $member) {
                                    $subTeam->members()->create(['employee_id' => $member->employee_id]);
                                }
                                foreach ($sub->vehicles as $vehicle) {
                                    $conflict = \App\Helpers\VehicleAssignmentHelper::getDetailedStatusForDate(
                                        $vehicle->vehicle_id,
                                        $data['work_date'],
                                        null,
                                        null
                                    );
                                    if ($conflict['status'] === 'asignado') {
                                        throw new \Exception("Veículo já atribuído ao {$conflict['context']} {$conflict['team_name']} em {$conflict['date']} (importação de sub-equipa).");
                                    }
                                    $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                                }
                            }
                        }
                    }),
                Tables\Actions\Action::make('duplicarEquipasDiaAnterior')
                    ->label('Duplicar Equipas do Dia Anterior')
                    ->icon('heroicon-o-document-duplicate')
                    ->form([
                        DatePicker::make('source_date')
                            ->label('Dia a duplicar')
                            ->default(fn() => \App\Helpers\WorkdayHelper::getLastBusinessDay())
                            ->required(),
                        DatePicker::make('target_date')
                            ->label('Novo dia')
                            ->default(fn() => \App\Helpers\WorkdayHelper::getNextBusinessDay())
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $equipos = \App\Models\DailyTeam::whereDate('work_date', $data['source_date'])
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
                                $conflict = VehicleAssignmentHelper::getDetailedStatusForDate(
                                    $vehicle->vehicle_id,
                                    $data['target_date'],
                                    null,
                                    null
                                );
                                if ($conflict['status'] === 'asignado') {
                                    throw new \Exception("Veículo já atribuído à equipa {$conflict['team_name']} em {$conflict['date']} (duplicação de dia).");
                                }
                                $newTeam->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                            }
                            foreach ($record->subTeams as $sub) {
                                $subTeam = $newTeam->subTeams()->create([
                                    'sub_team_name_id' => $sub->sub_team_name_id,
                                    'leader_id' => $sub->leader_id,
                                    'work_date' => $data['target_date'], // Propagar la fecha también en duplicação
                                ]);
                                foreach ($sub->members as $member) {
                                    $subTeam->members()->create(['employee_id' => $member->employee_id]);
                                }
                                foreach ($sub->vehicles as $vehicle) {
                                    $conflict = \App\Helpers\VehicleAssignmentHelper::getDetailedStatusForDate(
                                        $vehicle->vehicle_id,
                                        $data['target_date'],
                                        null,
                                        null
                                    );
                                    if ($conflict['status'] === 'asignado') {
                                        throw new \Exception("Veículo já atribuído ao {$conflict['context']} {$conflict['team_name']} em {$conflict['date']} (duplicação de sub-equipa).");
                                    }
                                    $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                                }
                            }
                        }
                    }),
                Tables\Actions\Action::make('publicarDia')
                    ->label('Publicar Dia de Trabalho')
                    ->icon('heroicon-o-check')
                    ->form([
                        DatePicker::make('work_date')
                            ->label('Data do Trabalho')
                            ->default(fn() => \App\Models\DailyTeam::where('is_template', false)->max('work_date'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        \App\Models\PublishedOperationsDay::firstOrCreate([
                            'date' => $data['work_date'],
                        ]);
                        Notification::make()
                            ->title('Dia publicado!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('success'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyTeams::route('/'),
            'create' => Pages\CreateDailyTeam::route('/create'),
        ];
    }

    /**
     * Gera as opções para os selects de colaboradores com o seu estado.
     */
    private static function getEmployeeOptions(callable $get, $record, string $pathPrefix = ''): array
    {
        static $employeeCache = [];

        $workDate = $get($pathPrefix . 'work_date') ?? $record?->dailyTeam?->work_date ?? now();
        $teamId = $get($pathPrefix . 'id') ?? $record?->daily_team_id;
        $excludeMemberId = $record?->id ?? null;

        $cacheKey = md5($workDate . '_' . $teamId . '_' . $excludeMemberId);

        if (!isset($employeeCache[$cacheKey])) {
            $employeeCache[$cacheKey] = Employee::all()->mapWithKeys(function ($employee) use ($workDate, $teamId, $excludeMemberId) {
                return [
                    $employee->id => EmployeeAssignmentHelper::getLabelParaSelect(
                        $employee->id,
                        $workDate,
                        $teamId,
                        $excludeMemberId
                    ),
                ];
            })->toArray();
        }

        return $employeeCache[$cacheKey];
    }

    /**
     * Gera as opções para os selects de veículos com o seu estado.
     */
    private static function getVehicleOptions(callable $get, $record, string $pathPrefix = ''): array
    {
        static $vehicleCache = [];

        $date = $get($pathPrefix . 'work_date') ?? $record?->dailyTeam?->work_date ?? $record?->subTeam?->dailyTeam?->work_date ?? now();
        $teamId = $get($pathPrefix . 'id') ?? $record?->daily_team_id ?? $record?->subTeam?->daily_team_id;
        $excludeVehicleId = $record?->id ?? null;
        $isTemplate = \App\Models\DailyTeam::find($teamId)?->is_template ?? false;

        $cacheKey = md5($date . '_' . $teamId . '_' . $excludeVehicleId . '_' . $isTemplate);

        if (!isset($vehicleCache[$cacheKey])) {
            $vehicleCache[$cacheKey] = Vehicle::get()->mapWithKeys(fn($v) => [
                $v->id => VehicleAssignmentHelper::getLabelParaSelect($v->id, $date, $teamId, $excludeVehicleId, $isTemplate),
            ])->toArray();
        }

        return $vehicleCache[$cacheKey];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_template', false)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
