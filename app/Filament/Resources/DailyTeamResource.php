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
use Illuminate\Support\Carbon;

class DailyTeamResource extends Resource
{


    protected static ?string $model = DailyTeam::class;
    protected static ?string $navigationGroup = 'Gestão de Equipas';
    protected static ?string $label = 'Equipa Diária';
    protected static ?string $pluralLabel = 'Equipas Diárias';
    protected static ?string $title = 'Equipas Diárias';

    // Para Plantillas
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('is_template', true);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('id')->dehydrated(),
            Forms\Components\Hidden::make('is_template')->default(true)->dehydrated(),

            // Si es plantilla, work_date oculto y fijo; si no, editable y default próximo día laborable
            Forms\Components\Hidden::make('work_date')
                ->default('2000-01-01')
                ->dehydrated()
                ->visible(fn($get) => $get('is_template')),
            Forms\Components\DatePicker::make('work_date')
                ->label('Data do Trabalho')
                ->required()
                ->default(fn() => \App\Filament\Resources\DailyTeamResource\Pages\CreateDailyTeam::getNextWorkday())
                ->visible(fn($get) => !$get('is_template')),
            Section::make('Informações da Equipa')
                ->description('Informações básicas sobre a equipa')
                ->schema([
                    Select::make('team_name_id')
                        ->label('Nome da Equipa*')
                        ->placeholder('Seleciona a Equipa')
                        ->required()
                        ->relationship('teamname', 'name')
                        ->searchable(true)
                        ->preload()
                        ->dehydrated(true)
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
                        ])->extraAttributes([
                            'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                        ]),

                    Select::make('pep_id')
                        ->relationship('pep', 'code')
                        ->label('Código PEP')
                        ->searchable(true)
                        ->required()
                        ->preload()
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
                        )->extraAttributes([
                            'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                        ]),

                    TextInput::make('work_type')
                        ->label('Tipo de Trabalho')
                        ->dehydrated(true)
                        ->extraAttributes([
                            'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                        ]),
                    TextInput::make('location')
                        ->label('Localização')
                        ->dehydrated(true)
                        ->extraAttributes([
                            'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                        ]),
                    //--------Lider
                    Select::make('leader_id')
                        ->label('Líder')
                        ->placeholder('Seleciona o Líder')
                        ->searchable()
                        ->options(function (callable $get) {
                            // work_date y teamId deben venir del contexto principal para DailyTeam
                            $workDate = $get('work_date') ?? $get('../../work_date') ?? now();
                            $teamId = $get('id') ?? $get('../id');
                            $isTemplate = \App\Models\DailyTeam::find($teamId)?->is_template ?? false;
                            return \App\Models\Employee::all()->mapWithKeys(function ($e) use ($workDate, $isTemplate, $teamId) {
                                return [
                                    $e->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                                        $e->id,
                                        $workDate,
                                        $teamId,
                                        null
                                    ),
                                ];
                            })->toArray();
                        })
                        ->rules([
                            fn(callable $get, $record) => [
                                new \App\Rules\EmployeeUniqueForDate(
                                    $get('../../work_date') ?? '2000-01-01',
                                    $get('id'),
                                    $record?->id
                                ),
                            ],
                        ])
                        ->columnSpan(2)
                        ->extraAttributes([
                            'style' => 'font-size: 0.85em;',
                        ]),
                ])->columns(3)
                ->columnSpanFull()
                ->extraAttributes([
                    'style' => '
                                background-color: #595fcf;
                                font-size: small;
                                @media (prefers-color-scheme: dark) {
                                background-color: #333}'
                ])->extraAttributes([
                    'style' => '
                                background-color: #1119ad;
                                color: #222;
                                font-size: small;
                                @media (prefers-color-scheme: dark) {
                                    background-color: #333;
                                    color: #fff;
                                }
                            '
                ]),
            //--------Membros e Veículos
            Section::make('Membros e Veículos')
                ->description('Membros da equipa direta e veículos')
                ->schema([
                    //--------Membros
                    TableRepeater::make('dailyTeamMembers')
                        ->headers([
                            Header::make('Membros '),
                            Header::make('Apagar'),
                        ])
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
                                ->options(function (callable $get) {
                                    // work_date y teamId deben venir del contexto principal para DailyTeamMember
                                    $workDate = $get('work_date') ?? $get('../../work_date') ?? now();
                                    $teamId = $get('../../id');
                                    $isTemplate = \App\Models\DailyTeam::find($teamId)?->is_template ?? false;
                                    return \App\Models\Employee::all()->mapWithKeys(function ($e) use ($workDate, $isTemplate, $teamId) {
                                        return [
                                            $e->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                                                $e->id,
                                                $workDate,
                                                $teamId,
                                                null
                                            ),
                                        ];
                                    })->toArray();
                                })
                                ->rules([
                                    fn(callable $get, $record) => [
                                        new \App\Rules\EmployeeUniqueForDate(
                                            $get('../../work_date') ?? '2000-01-01',
                                            $get('id'),
                                            $record?->id
                                        ),
                                    ],
                                ])
                                ->live(debounce: 500)
                                ->reactive()
                                ->extraAttributes([
                                    'style' => 'font-size: 0.85em;',
                                ]),
                        ])
                        ->columns(1),

                    TableRepeater::make('dailyTeamVehicles')
                        ->headers([
                            Header::make('Veiculos '),
                            Header::make('Apagar'),
                        ])
                        ->relationship()
                        ->label('Veículos Diretos')
                        ->schema([
                            Select::make('vehicle_id')
                                ->label('Veículo')
                                ->placeholder('Seleciona o Veículo')
                                ->required()
                                ->searchable(true)
                                ->options(function ($get) {
                                    $date = $get('../../work_date') ?? now();
                                    return \App\Models\Vehicle::all()
                                        ->mapWithKeys(fn($v) => [
                                            $v->id => $v->getLabelParaSelect($date),
                                        ]);
                                })
                                ->rules([
                                    // Cambiado: Propagar correctamente la fecha de trabajo para la validación
                                    fn(callable $get, $record) => [new \App\Rules\VehicleUniqueForDate(
                                        $get('../../work_date'), // <- subir dos niveles
                                        $get('id'),
                                        $record?->id
                                    )]
                                ])
                                ->dehydrated(true)
                                ->extraAttributes([
                                    'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                                ])
                        ])
                    ->columns(1)
                ])
                ->columns(2)
                ->columnSpanFull()
                ->extraAttributes([
                    'style' => '
                                background-color: #1119ad;
                                color: #222;
                                font-size: small;
                                @media (prefers-color-scheme: dark) {
                                    background-color: #333;
                                    color: #fff;
                                }
                                '
                ])->extraAttributes([
                    'style' => '
                                background-color: #1119ad;
                                color: #222;
                                font-size: small;
                                @media (prefers-color-scheme: dark) {
                                    background-color: #333;
                                    color: #fff;
                                }
                                '
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
                                ->preload()
                                ->dehydrated(true)
                                ->required()
                                ->options(function (callable $get) {
                                    // work_date y teamId deben venir del contexto del subgrupo
                                    $workDate = $get('work_date') ?? $get('../../work_date') ?? now();
                                    $teamId = $get('id');
                                    $isTemplate = \App\Models\DailyTeam::find($teamId)?->is_template ?? false;
                                    return \App\Models\Employee::all()->mapWithKeys(function ($e) use ($workDate, $isTemplate, $teamId) {
                                        return [
                                            $e->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                                                $e->id,
                                                $workDate,
                                                $teamId,
                                                null
                                            ),
                                        ];
                                    })->toArray();
                                })
                                ->rules([
                                    fn(callable $get, $record) => [
                                        new \App\Rules\EmployeeUniqueForDate(
                                            $get('../../work_date') ?? '2000-01-01',
                                            $get('id'),
                                            $record?->id,
                                            \App\Models\DailyTeam::find($get('../id'))?->is_template ?? false
                                        ),
                                    ],
                                ])
                                ->live(debounce: 500)
                                ->reactive()
                                ->extraAttributes([
                                    'style' => 'font-size: 0.85em;', // o 'font-size: small;'
                                ]),

                            TableRepeater::make('members')
                                ->headers([
                                    Header::make('Membros '),
                                    Header::make('Apagar'),
                                ])
                                ->relationship()
                                ->label('Membros do Subgrupo')
                                ->schema([
                                    Select::make('employee_id')
                                        ->label('Colaborador*')
                                        ->placeholder('Seleciona o Colaborador')
                                        ->searchable()
                                        ->options(function (callable $get) {
                                            // work_date y teamId deben venir del contexto del subgrupo
                                            $workDate = $get('work_date') ?? $get('../../work_date') ?? now();
                                            $teamId = $get('id');
                                            $isTemplate = \App\Models\DailyTeam::find($teamId)?->is_template ?? false;
                                            return \App\Models\Employee::all()->mapWithKeys(function ($e) use ($workDate, $isTemplate, $teamId) {
                                                return [
                                                    $e->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                                                        $e->id,
                                                        $workDate,
                                                        $teamId,
                                                        null
                                                    ),
                                                ];
                                            })->toArray();
                                        })
                                        ->rules([
                                            fn(callable $get, $record) => [
                                                new \App\Rules\EmployeeUniqueForDate(
                                                    $get('../../work_date') ?? '2000-01-01',
                                                    $get('id'),
                                                    $record?->id,
                                                    \App\Models\DailyTeam::find($get('../id'))?->is_template ?? false
                                                ),
                                            ],
                                        ])
                                        ->live(debounce: 500)
                                        ->reactive()
                                        ->dehydrated(true)
                                        ->required()
                                        ->extraAttributes([
                                            'style' => 'font-size: 0.85em;',
                                        ])
                                ])
                                ->columns(1),

                            TableRepeater::make('vehicles')
                                ->headers([
                                    Header::make('Veiculos '),
                                    Header::make('Apagar'),
                                ])
                                ->relationship()
                                ->label('Veículos do Subgrupo')
                                ->schema([
                                    Forms\Components\Hidden::make('work_date')
                                        ->default(fn($get) => $get('../../../work_date'))
                                        ->dehydrated(true),
                                    Select::make('vehicle_id')
                                        ->label('Veículo*')
                                        ->searchable(true)
                                        ->placeholder('Selecionar Veículo')
                                        ->options(function ($get) {
                                            $date = $get('../../work_date') ?? now();
                                            return \App\Models\Vehicle::all()
                                                ->mapWithKeys(fn($v) => [
                                                    $v->id => $v->getLabelParaSelect($date),
                                                ]);
                                        })
                                        ->rules([
                                            fn(callable $get, $record) => [new \App\Rules\VehicleUniqueForDate(
                                                $get('../../work_date'),
                                                $get('id'),
                                                $record?->id
                                            )]
                                        ])
                                        ->dehydrated(true)
                                        ->required()
                                        ->extraAttributes([
                                            'style' => 'font-size: 0.85em;',
                                        ]),
                                ])
                                ->columns(1)
                                ->extraAttributes([]),
                        ])
                        ->columns(2)
                        ->collapsible(),
                ])->columnSpanFull()
                ->extraAttributes([
                    'style' => '
                                background-color: #1119ad;
                                color: #222;
                                font-size: small;
                                @media (prefers-color-scheme: dark) {
                                    background-color: #333;
                                    color: #fff;
                                }
                                '
                ]),
        ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\TextColumn::make('work_date')->label('Data da Equipa'),
                Tables\Columns\TextColumn::make('teamname.name')->label('Nome da Equipa'),
                Tables\Columns\TextColumn::make('pep.code')->label('Código PEP'),
                Tables\Columns\TextColumn::make('leader.user.name')->label('Líder'),
            ])
            ->defaultSort('team_name_id')
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('importarPlantillasParaDia')
                    ->label('Importar todas las plantillas para un día')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        DatePicker::make('work_date')
                            ->label('Data de Trabalho')
                            ->default(fn() => now()->addDay()->format('Y-m-d'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Importar todas las plantillas como equipos diarios para la fecha seleccionada
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
                                    throw new \Exception("Veículo já atribuído ao equipa {$conflict['team_name']} em {$conflict['date']} (importação de plantilla).");
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
                                        throw new \Exception("Veículo já atribuído ao {$conflict['context']} {$conflict['team_name']} em {$conflict['date']} (importação de subgrupo).");
                                    }
                                    $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                                }
                            }
                        }
                    }),
                Tables\Actions\Action::make('duplicarEquiposDiaAnterior')
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
                                $conflict = VehicleAssignmentHelper::getDetailedStatusForDate(
                                    $vehicle->vehicle_id,
                                    $data['target_date'],
                                    null,
                                    null
                                );
                                if ($conflict['status'] === 'asignado') {
                                    throw new \Exception("Veículo já atribuído ao equipa {$conflict['team_name']} em {$conflict['date']} (duplicação de dia).");
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
                                        throw new \Exception("Veículo já atribuído ao {$conflict['context']} {$conflict['team_name']} em {$conflict['date']} (duplicação de subgrupo).");
                                    }
                                    $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                                }
                            }
                        }
                    }),
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
            //'edit' => Pages\EditDailyTeam::route('/{record}/edit'),
            'team-card' => Pages\TeamCard::route('/team-card'),
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->label('Cards de Equipas Diárias')
                ->icon('heroicon-o-rectangle-stack')
                ->url(static::getUrl('team-card'))
                ->group('Gestão de Equipas'),

            NavigationItem::make()
                ->label('Plantillas de Equipas')
                ->icon('heroicon-o-clipboard-document-list')
                ->url(static::getUrl('index')) // Plantillas
                ->group('Gestão de Equipas'),


        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_template', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
