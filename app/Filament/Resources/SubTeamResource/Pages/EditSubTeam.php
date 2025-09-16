<?php

namespace App\Filament\Resources\SubTeamResource\Pages;

use App\Filament\Resources\SubTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubTeam extends EditRecord
{
    protected static string $resource = SubTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
}
