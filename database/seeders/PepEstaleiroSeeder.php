<?php

namespace Database\Seeders;

use App\Models\Pep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PepEstaleiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Pep::firstOrCreate(
            ['code' => '016.000/EST'],
            [
                'description' => 'PEP para Estaleiro',
                'active' => true,
            ]
        );
    }
}
