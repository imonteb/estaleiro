<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use App\Models\Vehicle;

use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class VehicleViewPage extends Page
{

    protected static string $resource = VehicleResource::class;

    protected static string $view = 'filament.resources.vehicle-resource.pages.vehicle-view-page';
    public ?Vehicle $record = null;
    public $vehicles;


    public function mount(Vehicle $record): void
    {
        $this->vehicles = Vehicle::all();
    }
    
}
