<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role as ModelsRole;

use function Laravel\Prompts\alert;

class UserImport implements ToCollection, WithHeadingRow
{
    public bool $queueable = true;
    public string $message;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = optional(User::where('email', $row['email'])->first())->email;

            if ($email == null) {
                User::create([
                    'name' => $row['nome'],
                    'email' => $row['email'],
                    'email_verified_at' => $row['email_verificado'],
                    'password' => $row['palabra_pase'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $this->message = 'Importado com sucesso';

            } else {
                 $this->message = 'Erro de importação';
                
            }
        }
        $this->afterImport();
    }

    public function afterImport(): void
    {
        Notification::make()
            ->title($this->message)
            ->success()
            ->send();
    }
}
