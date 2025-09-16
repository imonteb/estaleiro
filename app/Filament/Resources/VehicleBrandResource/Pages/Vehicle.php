<?php

namespace App\Filament\Resources\VehicleBrandResource\Pages;

use App\Filament\Resources\VehicleBrandResource;
use Filament\Resources\Pages\Page;

class Vehicle extends Page
{
    protected static string $resource = VehicleBrandResource::class;

    protected static string $view = 'filament.resources.vehicle-brand-resource.pages.vehicle';
}
