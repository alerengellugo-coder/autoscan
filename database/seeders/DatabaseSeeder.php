<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Run seeders in a specific order to respect foreign key constraints.
     * 1. Roles & Permissions (foundational)
     * 2. Admin user (needs roles)
     * 3. Technician user (needs roles)
     * 4. Sample products (independent)
     * 5. Sample settings (independent)
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
            TechnicianUserSeeder::class,
            SampleProductsSeeder::class,
            SampleSettingsSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
