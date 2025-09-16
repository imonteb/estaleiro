<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Filament\Resources\PositionResource\RelationManagers;
use App\Models\Department;
use App\Models\Position;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PositionResource extends Resource
{
    
    protected static ?string $model = Position::class;
//protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Colaboradores';
    protected static ?string $navigationBadge = '0';
    protected static ?string $navigationBadgeColor = 'info';
    protected static ?string $navigationSortGroup = 'Cargos';
    protected static ?string $navigationSortIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Cargo';
    protected static ?string $pluralModelLabel = 'Cargos';
    protected static ?string $navigationLabel = 'Cargos';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Department')
                    ->placeholder('Select a department')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Department Name')
                            ->dehydrateStateUsing(fn(string $state): string => Str::upper($state))
                            ->required()
                            ->unique(Department::class, 'name', ignoreRecord: true)
                            ->maxLength(255),
                    ]),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->dehydrateStateUsing(fn(string $state): string => ucfirst(strtolower($state)))
                    ->unique(Position::class, 'name', ignoreRecord: true)
                    ->label('Position Name')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
