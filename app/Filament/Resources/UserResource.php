<?php

namespace App\Filament\Resources;

use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    
    protected static ?string $model = User::class;
    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Colaboradores';
    protected static ?string $navigationBadge = '0';
    protected static ?string $navigationBadgeColor = 'info';
    protected static ?string $navigationSortGroup = 'Colaboradores';
    protected static ?string $navigationSortIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->dehydrateStateUsing(fn(string $state): string => ucwords(strtolower($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                /* Forms\Components\TextInput::make('password')
                    ->label('Palavra-passe')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'), */
                Forms\Components\TextInput::make('password')
                    ->label('Palavra‑passe')
                    ->password()
                    // Sólo transforma el estado en hash si viene relleno:
                    ->dehydrateStateUsing(fn(?string $state) => filled($state) ? Hash::make($state) : null)
                    // Sólo envía al modelo si viene relleno:
                    ->dehydrated(fn(?string $state) => filled($state))
                    // Requerido únicamente al crear, no al editar:
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),

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
                        ExcelExport::make('table')->fromTable()
                            ->withFilename('User_table_' . date('Y-m-d') . ' - export')
                            ->only([
                                'name',
                                'email',
                            ])
                            ->withColumns([
                                Column::make('name')->heading('Nome'),
                                Column::make('email')->heading('Endereço de e-mail'),
                            ])
                            ->askForWriterType(),
                        ExcelExport::make('form')->fromForm()
                            ->withFilename('User_form_' . date('Y-m-d') . ' - export')
                            ->only([
                                'name',
                                'email',
                            ])
                            ->withColumns([
                                Column::make('name')->heading('Nome'),
                                Column::make('email')->heading('Endereço de e-mail'),
                            ])
                            ->askForWriterType(),
                    ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
