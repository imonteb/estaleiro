<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use App\Enums\Sex;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

class EmployeeImport implements OnEachRow, WithHeadingRow
{
    public int $currentRow = 1;
    public int $correctos = 0;
    public int $errores = 0;

    public function onRow(Row $row)
    {
        $this->currentRow = $row->getIndex();
        $data = $row->toArray();

        try {
            // Normalización del campo 'sex'
            $sexValue = $this->normalizeSex($data['sex'] ?? null);

            // Buscar o crear usuario
            $user = User::firstOrNew(['email' => $data['email']]);

            $user->name = $data['name'];
            $user->email_verified_at = null;
            $user->password = bcrypt($data['palabra_pase'] ?? 'password');
            $user->save();

            // Buscar o crear empleado
            $employee = Employee::firstOrNew(['user_id' => $user->id]);

            $employee->last_name = $data['last_name'];
            $employee->employee_number = $data['employee_number'];
            $employee->sex = $sexValue;
            $employee->phone = $this->normalizePhone($data['phone'] ?? null);
            $employee->position_id = $data['position_id'];
            $employee->active = (bool)$data['active'];
            $employee->image_url = $data['image_url'] ?? null;
            $employee->save();

            $this->correctos++;

        } catch (\Exception $e) {
            // ...log eliminado...
            $this->errores++;
        }
    }

    private function normalizeSex(?string $sexRaw): Sex
    {
        $value = strtolower(trim($sexRaw ?? ''));

        return match($value) {
            '', null => Sex::Male, // por defecto Masculino
            'masculino', 'm' => Sex::Male,
            'feminino', 'f' => Sex::Female,
            default => throw new \Exception("Valor de sex inválido: '{$sexRaw}'"),
        };
    }

    private function normalizePhone(?string $phoneRaw): ?string
    {
        if (empty($phoneRaw)) {
            return null;
        }

        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedPhone = $phoneUtil->parse($phoneRaw, 'PT'); // Región por defecto Portugal
            return $phoneUtil->format($parsedPhone, \libphonenumber\PhoneNumberFormat::E164);
        } catch (NumberParseException $e) {
            throw new \Exception("Número de teléfono inválido: '{$phoneRaw}'");
        }
    }

    public function __destruct()
    {
    // ...log eliminado...
    }
}
