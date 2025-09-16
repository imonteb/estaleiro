<?php

namespace Database\Seeders;

use App\Models\SubTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        SubTeam::insert([
            ['slug' => 'fibra-optica',   'name' => 'Fibra Ótica',     'color' => '#4E73DF'],
            ['slug' => 'pedreiros',      'name' => 'Pedreiros',        'color' => '#1CC88A'],
            ['slug' => 'desmatacao',     'name' => 'Desmatação',        'color' => '#E74A3B'],
            ['slug' => 'grua',           'name' => 'Grua',             'color' => '#F6C23E'],
        ]);
    }
}
