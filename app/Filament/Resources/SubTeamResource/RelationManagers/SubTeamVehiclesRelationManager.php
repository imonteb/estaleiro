<?php

namespace App\Filament\Resources\SubTeamResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Models\Vehicle;

class SubTeamVehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicles';

    protected static ?string $title = 'Veículos atribuídos';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('vehicle_id')
                ->label('Veículo')
                ->options(Vehicle::all()->pluck('label', 'id'))
                ->searchable()
                ->required(),
            Select::make('status')
                ->label('Status')
                ->options([
                    'uso' => 'Em uso',
                    'reserva' => 'Reserva',
                    'extra' => 'Extra',
                ])
                ->nullable(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('vehicle.label')->label('Veículo'),
            TextColumn::make('status')->label('Status')->badge()->color(fn($state) => match ($state) {
                'uso' => 'success',
                'reserva' => 'warning',
                'extra' => 'gray',
                default => 'secondary',
            }),
        ])
        ->headerActions([Tables\Actions\CreateAction::make()])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
