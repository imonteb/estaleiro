<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyTeams extends ListRecords
{
    
    protected static string $resource = DailyTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
             Actions\CreateAction::make()
                ->label('Criar Equipa') // ← Cambia el texto del botón
                ->modalHeading('Criar Equipa Diária')
                ->slideOver(),
        ];
    }
}
