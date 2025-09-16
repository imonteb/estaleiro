<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyTeamMemberResource\Pages;
use App\Filament\Resources\DailyTeamMemberResource\RelationManagers;
use App\Models\DailyTeamMember;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyTeamMemberResource extends Resource
{

    protected static ?string $model = DailyTeamMember::class;


    //protected static ?string $navigationGroup = 'Gestão de Equipas';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('daily_team_id')
                    ->label('Equipe Diária')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('employee_id')
                    ->label('Funcionário')
                    ->placeholder('Selecione um Funcionário')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sub_team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('daily_team_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_team_id')
                    ->numeric()
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
            'index' => Pages\ListDailyTeamMembers::route('/'),
            'create' => Pages\CreateDailyTeamMember::route('/create'),
            'edit' => Pages\EditDailyTeamMember::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
{
    return false;
}
}
