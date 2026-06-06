<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates roles, permissions, and assigns permissions to each role.
     */
    public function run(): void
    {
        // Clear existing permissions cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------------------
        // Create Permissions
        // -------------------------------------------------------------------
        $permissions = [
            'manage users',
            'manage vehicles',
            'manage orders',
            'create reports',
            'manage products',
            'manage quotations',
            'manage sales',
            'view reports',
            'manage settings',
            'view dashboard',
            'delete reports',
            'generate pdfs',
            'register payments',
            'cancel sales',
            'convert quotations',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        // -------------------------------------------------------------------
        // Create Roles
        // -------------------------------------------------------------------
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $technicianRole = Role::firstOrCreate([
            'name' => 'technician',
            'guard_name' => 'web',
        ]);

        $clientRole = Role::firstOrCreate([
            'name' => 'client',
            'guard_name' => 'web',
        ]);

        // -------------------------------------------------------------------
        // Assign Permissions to Roles
        // -------------------------------------------------------------------

        // Admin: ALL permissions
        $adminRole->syncPermissions($permissions);

        // Technician: limited permissions
        $technicianRole->syncPermissions([
            'manage orders',
            'create reports',
            'view reports',
            'view dashboard',
            'manage vehicles',
        ]);

        // Client: read-only permissions
        $clientRole->syncPermissions([
            'view reports',
            'view dashboard',
        ]);

        $this->command->info('✅ Roles and permissions seeded successfully.');
        $this->command->info('   Roles: admin, technician, client');
        $this->command->info('   Permissions: ' . implode(', ', $permissions));
    }
}
