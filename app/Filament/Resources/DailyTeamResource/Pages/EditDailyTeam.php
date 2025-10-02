<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use App\Filament\Resources\DailyTeamResource\Widgets\TeamOverviewWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyTeam extends EditRecord
{
    protected static string $resource = DailyTeamResource::class;
    protected static ?string $title = 'Editar Equipos Diários';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['work_date'] === '2000-01-01') {
            $data['is_template'] = true;
        } else {
            $data['is_template'] = false;
        }

        return $data;
    }
}
