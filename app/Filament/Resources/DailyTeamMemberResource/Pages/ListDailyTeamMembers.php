<?php

namespace App\Filament\Resources\DailyTeamMemberResource\Pages;

use App\Filament\Resources\DailyTeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyTeamMembers extends ListRecords
{
    protected static string $resource = DailyTeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
