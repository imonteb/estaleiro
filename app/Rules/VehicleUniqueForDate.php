<?php
namespace App\Rules;

use Illuminate\Support\Facades\Log;
use App\Helpers\VehicleAssignmentHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class VehicleUniqueForDate implements ValidationRule
{
    protected $workDate;
    protected $teamId;
    protected $excludeVehicleId;

    public function __construct($workDate, $teamId = null, $excludeVehicleId = null)
    {
        $this->workDate = $workDate;
        $this->teamId = $teamId;
        $this->excludeVehicleId = $excludeVehicleId;
    }
    public function validate(string $attribute, mixed $value, \Closure $fail): void

    {

        $status = VehicleAssignmentHelper::getDetailedStatusForDate(
            $value,
            $this->workDate,
            $this->teamId,
            $this->excludeVehicleId
        );

        if ($status['status'] === 'indisponivel') {
            $fail("Este veículo está indisponível: {$status['motivo']} em {$status['date']}.");
        } elseif ($status['status'] === 'atribuido' && empty($status['same_team'])) {
            $context = $status['context'] === 'subgrupo' ? 'sub-equipa' : 'equipa';
            $fail("Este veículo já está atribuído à {$context} {$status['team_name']} em {$status['date']}.");
        }
    }
}
