<?php

namespace App\Rules;

use App\Helpers\EmployeeAssignmentHelper;
use App\Models\DailyTeam;
use App\Models\SubTeamName;
use App\Models\TeamName;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeUniqueForDate implements ValidationRule
{
    protected $workDate;
    protected $teamId;
    protected $excludeMemberId;
    protected $isTemplate;

    /** * @param string|\DateTime $workDate Fecha del equipo (puede ser string o Carbon)
     * * @param int|null $teamId ID del equipo (DailyTeam o SubTeam)
     * * @param int|null $excludeMemberId ID del miembro a excluir en edición
     * * @param bool $isTemplate Indica si es plantilla (true) o equipo real (false)
     * */

    public function __construct($workDate, $teamId = null, $excludeMemberId = null, $isTemplate = false)
    {
        $this->workDate = $workDate;
        $this->teamId = $teamId;
        $this->excludeMemberId = $excludeMemberId;
        $this->isTemplate = $isTemplate;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $employeeId = $value;
        $date = $this->workDate instanceof \Carbon\Carbon ? $this->workDate->toDateString() : (string)$this->workDate;
        $excludeTeamId = $this->teamId;
        $excludeMemberId = $this->excludeMemberId;

        // 1. Buscar como líder en DailyTeam
        $team = \App\Models\DailyTeam::where('leader_id', $employeeId)
            ->whereDate('work_date', $date)
            ->when($excludeTeamId, fn($q) => $q->where('id', '!=', $excludeTeamId))
            ->with('teamname')
            ->first();

        if ($team) {
            $teamName = $team->teamname->name ?? '—';
            $fail("Este colaborador já está atribuído como Líder no equipe '{$teamName}' el {$date}.");
            return;
        }

        // 2. Buscar como colaborador en DailyTeamMember
        $member = \App\Models\DailyTeamMember::where('employee_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date, $excludeTeamId) {
                $q->whereDate('work_date', $date);
                if ($excludeTeamId) $q->where('id', '!=', $excludeTeamId);
            })
            ->when($excludeMemberId, fn($q) => $q->where('id', '!=', $excludeMemberId))
            ->with('dailyTeam.teamname')
            ->first();

        if ($member) {
            $teamName = $member->dailyTeam->teamname->name ?? '—';
            $fail("Este colaborador já está atribuído como Colaborador no equipe '{$teamName}' el {$date}.");
            return;
        }

        // 3. Buscar como líder en SubTeam
        $subTeam = \App\Models\SubTeam::where('leader_id', $employeeId)
            ->whereHas('dailyTeam', function ($q) use ($date, $excludeTeamId) {
                $q->whereDate('work_date', $date);
                if ($excludeTeamId) $q->where('id', '!=', $excludeTeamId);
            })
            ->with(['subTeamName', 'dailyTeam.teamname'])
            ->first();

        if ($subTeam) {
            $subTeamName = $subTeam->subTeamName->name ?? '—';
            $teamName = $subTeam->dailyTeam->teamname->name ?? '—';
            $fail("Este colaborador já está atribuído como Líder no subequipe '{$subTeamName}' do equipe '{$teamName}' el {$date}.");
            return;
        }

        // 4. Buscar como colaborador en SubTeamMember
        $subMember = \App\Models\SubTeamMember::where('employee_id', $employeeId)
            ->whereHas('subTeam', function ($q) use ($date, $excludeTeamId) {
                $q->whereHas('dailyTeam', function ($q2) use ($date, $excludeTeamId) {
                    $q2->whereDate('work_date', $date);
                    if ($excludeTeamId) $q2->where('id', '!=', $excludeTeamId);
                });
            })
            ->when($excludeMemberId, fn($q) => $q->where('id', '!=', $excludeMemberId))
            ->with(['subTeam.subTeamName', 'subTeam.dailyTeam.teamname'])
            ->first();

        if ($subMember) {
            $subTeamName = $subMember->subTeam->subTeamName->name ?? '—';
            $teamName = $subMember->subTeam->dailyTeam->teamname->name ?? '—';
            $fail("Este colaborador já está atribuído como Colaborador no subequipe '{$subTeamName}' do equipe '{$teamName}' el {$date}.");
            return;
        }
    }
}
