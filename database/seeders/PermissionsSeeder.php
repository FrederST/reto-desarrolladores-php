<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
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

        // create a demo user
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1->assignRole($role1);
        $user2->assignRole($role1);
    }
}
