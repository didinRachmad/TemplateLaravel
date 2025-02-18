<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Reset cache peran dan izin
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Membuat izin
        $permissions = [
            'index',
            'show',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'approve',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Membuat peran dan menetapkan izin
        $superAdminRole = Role::create(['name' => 'super_admin']);
        // $superAdminRole->givePermissionTo($permissions);

        $adminMoiRole = Role::create(['name' => 'admin_moi']);
        // $adminMoiRole->givePermissionTo($permissions);

        $managerHCRole = Role::create(['name' => 'manager_hc']);
        // $managerHCRole->givePermissionTo(['index', 'show']);

        $directurHCRole = Role::create(['name' => 'directur_hc']);
        // $directurHCRole->givePermissionTo(['index', 'show']);

        $managerAuditRole = Role::create(['name' => 'manager_audit']);
        // $managerAuditRole->givePermissionTo(['index', 'show']);

        $managerProcurementRole = Role::create(['name' => 'manager_procurement']);
        // $managerProcurementRole->givePermissionTo(['index', 'show']);

        $expertProcurementRole = Role::create(['name' => 'expert_procurement']);
        // $expertProcurementRole->givePermissionTo(['index', 'show']);

        // Membuat dan menetapkan peran ke pengguna
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'produksi' => 'P1',
        ]);
        $superAdmin->assignRole($superAdminRole);

        $user1 = User::create([
            'name' => 'Admin MOI',
            'email' => 'admin@moi.com',
            'password' => Hash::make('12345678'),
            'produksi' => 'P1',
        ]);
        $user1->assignRole($adminMoiRole);

        $user2 = User::create([
            'name' => 'Andry Studian',
            'email' => 'andry@managerhc.com',
            'password' => Hash::make('12345678'),
            'produksi' => 'P1',
        ]);
        $user2->assignRole($managerHCRole);

        $user3 = User::create([
            'name' => 'Yanuar Irnanto',
            'email' => 'yanuar@managerhc.com',
            'password' => Hash::make('12345678'),
            'produksi' => 'P2',
        ]);
        $user3->assignRole($managerHCRole);

        $user4 = User::create([
            'name' => 'Toto Sugeng Wiyoso',
            'email' => 'toto@directurhc.com',
            'password' => Hash::make('12345678'),
        ]);
        $user4->assignRole($directurHCRole);

        $user5 = User::create([
            'name' => 'Maybina',
            'email' => 'maybina@manageraudit.com',
            'password' => Hash::make('12345678'),
        ]);
        $user5->assignRole($managerAuditRole);

        $user6 = User::create([
            'name' => 'Denny',
            'email' => 'denny@managerprocurement.com',
            'password' => Hash::make('12345678'),
        ]);
        $user6->assignRole($managerProcurementRole);

        $user7 = User::create([
            'name' => 'Tanto',
            'email' => 'tanto@expertprocurement.com',
            'password' => Hash::make('12345678'),
        ]);
        $user7->assignRole($expertProcurementRole);
    }
}
