<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\StatusType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AtivoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $ativo = StatusType::firstOrCreate(
        ['name' => 'Ativo'],
        ['color' => 'success', 'is_default' => true]
    );

    Employee::all()->each(function ($employee) use ($ativo) {
        $hasActive = $employee->statuses()
            ->where('status_type_id', $ativo->id)
            ->whereNull('end_date')
            ->exists();

        if (! $hasActive) {
            EmployeeStatus::create([
                'employee_id' => $employee->id,
                'status_type_id' => $ativo->id,
                'start_date' => now()->toDateString(),
            ]);
        }
    });
}

}
