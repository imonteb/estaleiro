<?php

namespace App\Filament\Resources\DailyTeamResource\Pages;

use App\Filament\Resources\DailyTeamResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreateDailyTeam extends CreateRecord
{

    protected static string $resource = DailyTeamResource::class;
    protected static ?string $title = 'Criar Equipos Diários';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::debug('Datos recibidos en create:', $data);
        if ($data['work_date'] === '2000-01-01') {
            $data['is_template'] = true;
        } else {
            $data['is_template'] = false;
        }
        $data['created_by'] = \App\Models\Employee::where('user_id', auth()->id())->value('id');
        return $data;
    }

    protected function beforeCreate(): void
    {
        $workDate = $this->form->getState()['work_date'] ?? now()->toDateString();

        
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
