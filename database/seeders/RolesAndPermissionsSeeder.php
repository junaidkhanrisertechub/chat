<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'user-list']);
        Permission::create(['name' => 'user-create']);
        Permission::create(['name' => 'admin-dashboard']);
        Permission::create(['name' => 'user-dashboard']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo(Permission::all());
        $role->givePermissionTo('admin-dashboard');
        $role->givePermissionTo('user-list');
        $role->givePermissionTo('user-create');

        $role = Role::create(['name' => 'employee']);
        $role->givePermissionTo('user-dashboard');
    }
}
