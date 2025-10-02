<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\View\View;

class ListDailyTeams extends ListRecords
{
    protected static string $resource = DailyTeamResource::class;

    
}



