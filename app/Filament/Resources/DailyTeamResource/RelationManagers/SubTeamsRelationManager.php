<?php

namespace App\Filament\Resources\DailyTeamResource\RelationManagers;

use App\Models\Employee;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;

class SubTeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'subTeams';
    protected static ?string $title = 'Subgrupos atribuídos';

    public function form(Forms\Form $form): Forms\Form
    {
        $workDate = $this->getOwnerRecord()->work_date;

        return $form->schema([
            Forms\Components\Select::make('sub_team_name_id')
                ->relationship('subTeamName', 'name')
                ->label('Nome do Subgrupo')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('leader_id')
                ->label('Líder do Subgrupo')
                ->options(Employee::all()->mapWithKeys(fn($e) => [
                    $e->id => $e->getLabelParaSelect($workDate),
                ]))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('pep_id')
                ->relationship('pep', 'code')
                ->label('Código PEP')
                ->default(fn() => $this->getOwnerRecord()->pep_id)
                ->nullable(),

            Forms\Components\Hidden::make('work_date')
                ->default(fn() => $this->getOwnerRecord()->work_date)
                ->dehydrated()
                ->required(),

            Forms\Components\Hidden::make('team_name_id')
                ->default(fn() => $this->getOwnerRecord()->team_name_id)
                ->dehydrated()
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subTeamName.name')->label('Subgrupo'),
                Tables\Columns\TextColumn::make('leader.full_name')->label('Líder'),
                Tables\Columns\TextColumn::make('subTeamMembers_count')
                    ->counts('subTeamMembers')
                    ->label('Membros')
                    ->badge(),
                Tables\Columns\TextColumn::make('subTeamVehicles_count')
                    ->counts('subTeamVehicles')
                    ->label('Veículos')
                    ->badge(),

            ])    ->actions([
            EditAction::make()
                ->label('Editar')
                ->modalHeading('Editar Subgrupo')
                ->form([
                    Forms\Components\Select::make('sub_team_name_id')
                        ->relationship('subTeamName', 'name')
                        ->label('Nome do Subgrupo')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('leader_id')
                        ->relationship('leader', 'last_name') // cambia al campo real
                        ->label('Líder')
                        ->searchable()
                        ->required(),

                    Forms\Components\DatePicker::make('work_date')
                        ->label('Data')
                        ->displayFormat('d/m/Y')
                        ->required(),
                ])
                ->mutateFormDataUsing(fn(array $data) => $data) // opcional para ajustar antes de guardar

        ])


            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Editar Subgrupo')
                    ->icon('heroicon-o-pencil')
                    ->url(fn($record) => route('filament.admin.resources.sub-teams.edit', ['record' => $record->id]))
                    ->openUrlInNewTab()
                    ->visible(
                        fn($record) =>
                        $record->subTeamMembers()->exists() || $record->subTeamVehicles()->exists()
                    ),

                Tables\Actions\DeleteAction::make(),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->heading('Subgrupos atribuídos')
            ->paginated(false);
    }
}
