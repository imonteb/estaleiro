<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubTeamResource\Pages;
use App\Filament\Resources\SubTeamResource\RelationManagers;
use App\Filament\Resources\SubTeamResource\RelationManagers\SubTeamMembersRelationManager;
use App\Filament\Resources\SubTeamResource\RelationManagers\SubTeamVehiclesRelationManager;
use App\Models\DailyTeam;
use App\Models\SubTeam;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;





class SubTeamResource extends Resource
{

    protected static ?string $model = SubTeam::class;

    /* protected static ?string $navigationGroup = 'Gestão de Equipas';

    protected static ?string $navigationLabel = 'Subgrupos'; */

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('sub_team_name_id')
                ->relationship('subTeamName', 'name')
                ->label('Nome do Subgrupo')
                ->disabled()
                ->dehydrated(),

            Forms\Components\Select::make('leader_id')
                ->relationship('leader', 'last_name')
                ->label('Líder do Subgrupo')
                ->disabled()
                ->dehydrated(),

            Forms\Components\Select::make('pep_id')
                ->relationship('pep', 'code')
                ->label('Código PEP')
                ->disabled()
                ->dehydrated(),

            Select::make('daily_team_id')
                ->label('Equipa Principal')
                ->options(
                    \App\Models\DailyTeam::all()
                        ->mapWithKeys(fn($team) => [$team->id => $team->teamname?->name ?? '—'])
                )
                ->disabled()
                ->dehydrated(),


            Forms\Components\DatePicker::make('work_date')
                ->label('Data do Trabalho')
                ->disabled()
                ->dehydrated()
                ->displayFormat('d/m/Y'),
        ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('subTeamName.name')->label('Nome do Subgrupo'),
            TextColumn::make('dailyTeam.teamname')->label('Equipa Principal'),
            TextColumn::make('leader.full_name')->label('Líder'),
            TextColumn::make('pep.number')->label('PEP'),
            TextColumn::make('work_date')->label('Data')->date(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            SubTeamMembersRelationManager::class,
            SubTeamVehiclesRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubTeams::route('/'),
            'create' => Pages\CreateSubTeam::route('/create'),
            'edit' => Pages\EditSubTeam::route('/{record}/edit'),
        ];
    }

public static function shouldRegisterNavigation(): bool
{
    return false;
}



}
