<?php

namespace App\Filament\Resources\DailyTeamResource\RelationManagers;

use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DailyTeamMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'dailyTeamMembers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Colaborador')
                    ->options(function () {
                        $owner = $this->getOwnerRecord();
                        $date = $owner->work_date ?? now();
                        $isTemplate = $owner->is_template ?? false;
                        $teamId = $owner->id ?? null;
                        return Employee::with('user')->get()
                            ->mapWithKeys(fn($employee) => [
                                $employee->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                                    $employee,
                                    $date,
                                    $teamId,
                                    null
                                )
                            ]);
                    })
                    ->rules([
                        fn($get, $record) => new \App\Rules\EmployeeUniqueForDate(
                            $this->getOwnerRecord()->work_date,
                            $this->getOwnerRecord()->id,
                            $record?->id, // excluye el mismo si estás editando
                            $this->getOwnerRecord()->is_template ?? false
                        )
                    ])
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->helperText('Puedes seleccionar cualquier colaborador. Si ya está asignado o ausente, verás un mensaje de error al guardar.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.name')
            ->columns([
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('employee.last_name')
                    ->label('Apelido'),
                Tables\Columns\TextColumn::make('employee.employee_number')
                    ->label('Nº Colaborador'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state, $record) =>
                            $record->employee->getLabelParaSelect($record->dailyTeam->work_date)
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
