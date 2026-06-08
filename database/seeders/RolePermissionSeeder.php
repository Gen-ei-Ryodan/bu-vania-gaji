<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-any-gaji',
            'view-gaji',
            'view-any-laporan',
            'view-laporan-detail',
            'input-absensi',
            'input-bibit',
            'manage-master-data',
            'delete-data', // New permission for deletion
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $owner = Role::firstOrCreate(['name' => 'Owner']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);

        // Assign permissions to Owner (full access)
        $owner->syncPermissions(Permission::all());

        // Assign permissions to Admin (limited access but includes master data management, excluding delete)
        $admin->syncPermissions([
            'input-absensi',
            'input-bibit',
            'view-any-laporan',
            'manage-master-data', // Grant access to master data
            // 'delete-data' is intentionally omitted
        ]);

        // Create Owner user
        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner',
                'password' => bcrypt('password'),
            ]
        );
        $ownerUser->assignRole('Owner');

        // Create Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole('Admin');
    }
}
