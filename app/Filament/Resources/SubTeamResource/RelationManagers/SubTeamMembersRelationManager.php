<?php

namespace App\Filament\Resources\SubTeamResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Models\Employee;

class SubTeamMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Membros';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('employee_id')
                ->label('Colaborador')
                ->options(function () {
                    $owner = $this->getOwnerRecord();
                    $date = $owner->work_date ?? now();
                    $isTemplate = $owner->is_template ?? false;
                    $teamId = $owner->id;
                    return Employee::with('user')->get()->mapWithKeys(fn($employee) => [
                        $employee->id => \App\Helpers\EmployeeAssignmentHelper::getLabelParaSelect(
                            $employee,
                            $date,
                            $teamId,
                            null
                        )
                    ]);
                })
                ->searchable()
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('employee.employee_number')->label('Nº'),
            TextColumn::make('employee.user.name')->label('Nome'),
            TextColumn::make('employee.last_name')->label('Apelido'),
        ])
        ->headerActions([Tables\Actions\CreateAction::make()])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
