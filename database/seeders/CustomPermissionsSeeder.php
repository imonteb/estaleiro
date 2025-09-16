<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CustomPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'teams.view.workbench',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
