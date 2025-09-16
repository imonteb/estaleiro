<?php

namespace App\Livewire\DailyTeams;

use Livewire\Component;
use App\Models\DailyTeam;
use Illuminate\Support\Carbon;

class DailyTeamsIndex extends Component
{
    public $showSlideover = false;
    public $teamId = null;
    public $sourceDate;
    public $teams = [];
    public $showDuplicateModal = false;
    public $destDate;
    public $showImportModal = false;
    public $importDate;
    public $showImportLastWorkDayModal = false;
    public $importSourceDate;
    public $importDestDate;

    protected $listeners = ['closeSlideover' => 'closeSlideover'];

    public function mount()
    {

        $lastTeam = DailyTeam::latest('work_date')->first();
        $this->sourceDate = $lastTeam
            ? $lastTeam->work_date->format('Y-m-d')
            : now()->format('Y-m-d');
        $this->destDate = $this->getNextBusinessDay($this->sourceDate);

        // $this->sourceDate = now()->format('Y-m-d');
        $this->loadTeams();
    }

    public function updatedSourceDate()
    {
        $this->loadTeams();
    }

    public function loadTeams()
    {
        $this->teams = DailyTeam::with([
            'teamname',
            'pep',
            'leader',
            'dailyTeamMembers.employee',
            'dailyTeamVehicles.vehicle.vehicleBrand',
            'subTeams.subTeamName',
            'subTeams.leader',
            'subTeams.members.employee',
            'subTeams.vehicles.vehicle'
        ])->where('work_date', $this->sourceDate)->get();
    }

    public function openSlideover($teamId)
    {
        $this->teamId = $teamId;
        $this->showSlideover = true;
        $this->dispatch('openSlideover');
    }

    public function closeSlideover()
    {
        $this->showSlideover = false;
        $this->teamId = null;
        $this->loadTeams();
    }
    public function getNextBusinessDay($date = null): string
    {
        $carbonDate = $date ? Carbon::parse($date) : Carbon::today();
        $next = $carbonDate->copy()->addDay();
        while ($next->isWeekend()) {
            $next = $next->addDay();
        }
        return $next->format('Y-m-d');
    }
    public function createCard()
    {
        $this->teamId = null;
        $this->showSlideover = true;
    }
    public function openImportModal()
    {
        $this->importDate = $this->getNextBusinessDay($this->sourceDate);
        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importDate = null;
    }

    public function confirmImportTemplate()
    {
        // Validar que no existan equipos en esa fecha
        $exists = \App\Models\DailyTeam::where('work_date', $this->importDate)->exists();
        if ($exists) {
            session()->flash('error', 'Ya existen equipos creados para esa fecha. No se puede importar.');
            $this->closeImportModal();
            return;
        }
        $this->importTemplate($this->importDate);
        $this->closeImportModal();
    }
    public function importTemplate($templateDate = null)
    {
        // Calcular el próximo día útil si no se envía fecha
        if (!$templateDate) {
            $templateDate = $this->getNextBusinessDay($this->sourceDate);
        }
        // Obtener todos los equipos plantilla
        $templates = \App\Models\DailyTeam::where('is_template', true)->get();
        $imported = 0;
        foreach ($templates as $template) {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $template->team_name_id,
                'work_type' => $template->work_type,
                'location' => $template->location,
                'leader_id' => $template->leader_id,
                'pep_id' => $template->pep_id,
                'is_template' => false,
                'published' => $template->published,
                'work_date' => $templateDate,
            ]);
            foreach ($template->dailyTeamMembers as $member) {
                if ($member->employee_id) {
                    $team->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                }
            }
            foreach ($template->dailyTeamVehicles as $vehicle) {
                if ($vehicle->vehicle_id) {
                    $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                }
            }
            foreach ($template->subTeams as $sub) {
                $subTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $sub->sub_team_name_id,
                    'leader_id' => $sub->leader_id,
                    'work_date' => $templateDate,
                ]);
                foreach ($sub->members as $member) {
                    if ($member->employee_id) {
                        $subTeam->members()->create(['employee_id' => $member->employee_id]);
                    }
                }
                foreach ($sub->vehicles as $vehicle) {
                    if ($vehicle->vehicle_id) {
                        $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                    }
                }
            }
            $imported++;
        }
        if ($imported > 0) {
            session()->flash('success', "$imported equipa(s) importadas para $templateDate!");
            $this->sourceDate = $templateDate;
            $this->loadTeams();
        } else {
            session()->flash('error', 'Nenhum template encontrado para importar.');
        }
    }
    public function openImportLastWorkDayModal()
    {
        // Buscar el último día laboral real con equipos creados (no fin de semana)
        $lastWorkDay = null;
        $lastDate = \App\Models\DailyTeam::where('is_template', false)->latest('work_date')->value('work_date');
        if ($lastDate) {
            $date = Carbon::parse($lastDate);
            do {
                // Si no es fin de semana y hay equipos creados ese día, lo usamos
                if (!$date->isWeekend() && \App\Models\DailyTeam::where('is_template', false)->whereDate('work_date', $date->format('Y-m-d'))->exists()) {
                    $lastWorkDay = $date->format('Y-m-d');
                    break;
                }
                $date->subDay();
            } while ($date->greaterThan(Carbon::parse('2000-01-01'))); // Límite inferior
        }
        $this->importSourceDate = $lastWorkDay ?? now()->format('Y-m-d');
        // Fecha de destino: próximo día útil
        $this->importDestDate = $this->getNextBusinessDay($this->importSourceDate);
        $this->showImportLastWorkDayModal = true;
    }

    public function closeImportLastWorkDayModal()
    {
        $this->showImportLastWorkDayModal = false;
        $this->importSourceDate = null;
        $this->importDestDate = null;
    }

    public function confirmImportLastWorkDay()
    {
        // Validar que no existan equipos en la fecha destino
        $exists = \App\Models\DailyTeam::where('work_date', $this->importDestDate)->exists();
        if ($exists) {
            session()->flash('error', 'Já existem equipas criadas para essa data. Não é possível importar.');
            $this->closeImportLastWorkDayModal();
            return;
        }
        // Obtener todos los equipos del último día trabajado
        $teams = \App\Models\DailyTeam::where('work_date', $this->importSourceDate)->get();
        $imported = 0;
        foreach ($teams as $template) {
            $team = \App\Models\DailyTeam::create([
                'team_name_id' => $template->team_name_id,
                'work_type' => $template->work_type,
                'location' => $template->location,
                'leader_id' => $template->leader_id,
                'pep_id' => $template->pep_id,
                'is_template' => false,
                'published' => $template->published,
                'work_date' => $this->importDestDate,
            ]);
            foreach ($template->dailyTeamMembers as $member) {
                if ($member->employee_id) {
                    $team->dailyTeamMembers()->create(['employee_id' => $member->employee_id]);
                }
            }
            foreach ($template->dailyTeamVehicles as $vehicle) {
                if ($vehicle->vehicle_id) {
                    $team->dailyTeamVehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                }
            }
            foreach ($template->subTeams as $sub) {
                $subTeam = $team->subTeams()->create([
                    'sub_team_name_id' => $sub->sub_team_name_id,
                    'leader_id' => $sub->leader_id,
                    'work_date' => $this->importDestDate,
                ]);
                foreach ($sub->members as $member) {
                    if ($member->employee_id) {
                        $subTeam->members()->create(['employee_id' => $member->employee_id]);
                    }
                }
                foreach ($sub->vehicles as $vehicle) {
                    if ($vehicle->vehicle_id) {
                        $subTeam->vehicles()->create(['vehicle_id' => $vehicle->vehicle_id]);
                    }
                }
            }
            $imported++;
        }
        if ($imported > 0) {
            session()->flash('success', "$imported equipa(s) importadas de $this->importSourceDate para $this->importDestDate!");
            $this->sourceDate = $this->importDestDate;
            $this->loadTeams();
        } else {
            session()->flash('error', 'Nenhuma equipa encontrada para importar.');
        }
        $this->closeImportLastWorkDayModal();
    }
    public function importLastWorkDay()
    {
        $this->openImportLastWorkDayModal();
    }

    public function render()
    {
        return view('livewire.daily-teams.daily-teams-index');
    }
}
