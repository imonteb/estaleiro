<?php

namespace App\Filament\Resources;

use App\Enums\Sex;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\StatusType;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column as ExcelColumn;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class EmployeeResource extends Resource
{
    
    protected static string $resource = EmployeeResource::class;
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Colaboradores';
    protected static ?string $navigationBadge = '0';
    protected static ?string $navigationBadgeColor = 'info';
    protected static ?string $navigationSortGroup = 'Colaboradores';
    protected static ?string $navigationSortIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Colaborador';
    protected static ?string $pluralModelLabel = 'Colaboradores';
    protected static ?string $navigationLabel = 'Colaboradores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User ID')
                    ->required()
                    ->unique(Employee::class, 'user_id', ignoreRecord: true)
                    ->searchable()
                    ->preload()
                    ->relationship('user', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn(string $state): string => ucfirst(strtolower($state))),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn(string $state): string => strtolower($state)),
                        Forms\Components\DateTimePicker::make('email_verified_at'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('employee_number')
                    ->required()
                    ->unique(Employee::class, 'employee_number', ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('sex')
                    ->options(
                        collect(Sex::cases())
                            ->mapWithKeys(fn($sex) => [$sex->value => $sex->label()])
                            ->toArray()
                    )
                    ->required(),

                PhoneInput::make('phone')
                    ->placeholder('Enter phone number'),
                Forms\Components\Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a position')
                    ->createOptionForm(
                        fn(Forms\Form $form) => $form
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select a department')
                                    ->createOptionForm(
                                        fn(Forms\Form $form) => $form
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255),
                                            ])
                                    ),
                                Forms\Components\TextInput::make('name')
                                    ->maxLength(255),
                            ])
                    ),
                Forms\Components\Toggle::make('active')
                    ->label('Is Active')
                    ->default(true)
                    ->required(),



                Forms\Components\FileUpload::make('image_url')
                    ->label('Colaborador Image')
                    ->image()
                    ->maxSize(4096) // 4MB
                    ->avatar()
                    ->optimize('webp')
                    ->disk('public')
                    ->directory('employees')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->imageEditor(),

                // Repeater para los períodos de estado
                Forms\Components\Repeater::make('statuses')
                    ->label('Períodos de Estado')
                    ->relationship('statuses') // relación en Employee.php
                    ->schema([
                        Forms\Components\Select::make('status_type_id')
                            ->label('Tipo de Estado')
                            ->options(
                                \App\Models\StatusType::all()->mapWithKeys(fn($s) => [
                                    $s->id => $s->name ?: '—'
                                ])->toArray()
                            )
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Início')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Fim')
                            ->afterOrEqual('start_date'),

                        Forms\Components\TextInput::make('reason')
                            ->label('Motivo')
                            ->placeholder('Opcional')
                            ->maxLength(255),
                    ])
                    ->columns(3)
                    ->defaultItems(0)
                    ->addActionLabel('Adicionar período')
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sex')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                PhoneColumn::make('phone')
                    ->displayFormat(PhoneInputNumberType::NATIONAL)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->formatStateUsing(
                        fn($state) =>
                        preg_replace('/(\d{3})(\d{3})(\d{3})/', '$1 $2 $3', preg_replace('/\D/', '', $state))
                    ),

                Tables\Columns\TextColumn::make('position.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('Estado')
                    ->getStateUsing(fn($record) => $record->currentStatus()?->statusType?->name ?? 'Sem Estado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->withFilename('Colaboradores_' . date('Y-m-d'))
                            ->only([
                                'user.name',
                                'last_name',
                                'employee_number',
                                'phone',
                                'position.name',
                            ])
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
