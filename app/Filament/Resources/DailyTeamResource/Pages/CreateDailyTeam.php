<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateDailyTeam extends CreateRecord
{
    
    protected static string $resource = DailyTeamResource::class;
    protected static ?string $title = 'Criar Equipos Diários';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['template_id']);
        $data['created_by'] = \App\Models\Employee::where('user_id', auth()->id())->value('id');
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workDate = $this->form->getState()['work_date'] ?? now()->toDateString();

        // Si necesitas lógica para asegurar Estaleiro, implementa aquí directamente o llama a un helper.
        // Ejemplo:
        // EstaleiroHelper::ensureEstaleiro($workDate);
    }

    private function getNextWorkday(): string
    {
        $today = now();

        return match ($today->dayOfWeek) {
            Carbon::FRIDAY   => $today->copy()->addDays(3)->toDateString(),
            Carbon::SATURDAY => $today->copy()->addDays(2)->toDateString(),
            default          => $today->copy()->addDay()->toDateString(),
        };
    }
}
