<?php

namespace App\Filament\Resources\PepResource\Pages;

use App\Filament\Resources\PepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeps extends ListRecords
{
    protected static string $resource = PepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
