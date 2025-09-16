<?php

namespace App\Filament\Resources\DailyTeamResource\RelationManagers;

use App\Models\DailyTeamVehicle;
use App\Models\DailyTeamVehicles;
use App\Models\Vehicle;
use App\Rules\VehicleAvailableForWorkDate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DailyTeamVehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'dailyTeamVehicles';
    protected static ?string $title = 'Veículos atribuídos';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('vehicle_id')
                ->label('Veículo')
                ->searchable()
                ->preload()
                ->options(fn() => $this->getFilteredVehicleOptions())
                ->required()
                ->rules([
                    new \App\Rules\VehicleUniqueForDate(
                        $this->getOwnerRecord()->work_date ?? now(),
                        $this->getOwnerRecord()->id ?? null,
                        $this->getMountedTableActionRecord()?->id
                    ),
                ])


        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('vehicle.label')
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.car_plate')->label('Matrícula'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->vehicle->getEstadoBadge($record->dailyTeam->work_date)['emoji'] . ' ' .
                            $record->vehicle->getEstadoBadge($record->dailyTeam->work_date)['label']
                    )
                    ->color(
                        fn($record) =>
                        $record->vehicle->getEstadoBadge($record->dailyTeam->work_date)['color']
                    ),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    protected function getFilteredVehicleOptions(): array
    {
        $date = $this->getOwnerRecord()->work_date ?? now();

        return Vehicle::with(['dailyTeamVehicles.dailyTeam', 'subTeamVehicles.subTeam', 'maintenances', 'incidents'])
            ->get()
            ->filter(fn(Vehicle $vehicle) => $vehicle->isAvailableOn($date))
            ->mapWithKeys(fn(Vehicle $vehicle) => [
                $vehicle->id => $vehicle->getSelectableLabel($date),
            ])
            ->all();
    }
}
