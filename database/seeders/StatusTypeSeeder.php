<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatusTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_types')->insert([
            ['name' => 'ativo', 'color' => '#28a745', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'férias', 'color' => '#ffc107', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'licença', 'color' => '#17a2b8', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'baixa médica', 'color' => '#dc3545', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'outro', 'color' => '#6c757d', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
