<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use App\Filament\Resources\DailyTeamResource\Widgets\TeamOverviewWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditDailyTeam extends EditRecord
{

    protected static string $resource = DailyTeamResource::class;
    protected static ?string $title = 'Editar Equipos Diários'; // ← Cambia el título de la página
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['template_id']);


    // ...log eliminado...

        return $data;
    }
}
