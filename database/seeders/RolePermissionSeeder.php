<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'superadmin' => 'Super Administrator',
            'admin' => 'Administrator',
            'user' => 'Regular User'
        ];

        foreach ($roles as $name => $desc) {
            Role::create(['name' => $name, 'description' => $desc]);
        }

        $permissions = [
            'view-dashboard' => 'View Dashboard',
            'manage-users' => 'Manage Users',
            'manage-roles' => 'Manage Roles'
        ];

        foreach ($permissions as $name => $desc) {
            Permission::create(['name' => $name, 'description' => $desc]);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'superadmin')->first();
        $superAdmin->permissions()->attach(Permission::all());

        // Create default user
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
        ])->roles()->attach(Role::where('name', 'superadmin')->first());
    }
}
