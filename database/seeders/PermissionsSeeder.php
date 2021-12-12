<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'edit customers']);
        Permission::create(['name' => 'inactive customers']);
        Permission::create(['name' => 'create customers']);

        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo('edit customers');
        $role1->givePermissionTo('inactive customers');
        $role1->givePermissionTo('create customers');

        Role::create(['name' => 'customer']);
    }
}
