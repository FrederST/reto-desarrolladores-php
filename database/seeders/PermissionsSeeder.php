<?php

namespace Database\Seeders;

use File;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = json_decode(File::get('database/data/permissions.json'));

        foreach ($permissions->permissions as $value) {
            Permission::create([
                'name' => $value,
            ]);
        }

        foreach ($permissions->roles as $key => $value) {
            $role = Role::create(['name' => $key]);

            $role->syncPermissions($value);
        }
    }
}
