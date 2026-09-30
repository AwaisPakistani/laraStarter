<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        // Create Role
        $superadmin_role = Role::create([
            'name' => 'super_admin',
        ]);
        $superadmin->roles()->attach($superadmin_role);

        // Make permissions
        $users_permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.destroy',
            'users.show',
            'users.toggleStatus',
        ];
        $roles_permissions = [
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.destroy',
            'roles.show',
            'roles.toggleStatus',
        ];
        $permissions_permissions = [
            'permissions.index',
            'permissions.create',
            'permissions.edit',
            'permissions.destroy',
            'permissions.show',
            'permissions.toggleStatus',
        ];

        foreach ($users_permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        foreach ($roles_permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        foreach ($permissions_permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // CLEAR CACHE SO SPATIE RECOGNIZES THE NEWLY CREATED PERMISSIONS
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Admin Role
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $admin_role = Role::create([
            'name' => 'admin',
        ]);
        $admin->roles()->attach($admin_role);

        // Give permission to superadmin
        $superadmin_role->givePermissionTo(Permission::all());

        // Give specific permissions to admin using a query or filtered collection
        $adminPermissions = Permission::whereIn('name', $permissions_permissions)->get();
        $admin_role->givePermissionTo($adminPermissions);
    }
}
