<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the main admin user, two sample clients, and one sample technician.
     */
    public function run(): void
    {
        // -------------------------------------------------------------------
        // Admin User
        // -------------------------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@autoscan.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'phone'    => '+58 412-0000000',
                'active'   => true,
            ]
        );
        $admin->assignRole('admin');
        $admin->markEmailAsVerified();

        $this->command->info('✅ Admin user created: admin@autoscan.com / password');

        // -------------------------------------------------------------------
        // Sample Clients
        // -------------------------------------------------------------------
        $client1 = User::firstOrCreate(
            ['email' => 'carlos.garcia@email.com'],
            [
                'name'     => 'Carlos García',
                'password' => Hash::make('password'),
                'phone'    => '+58 414-1112233',
                'active'   => true,
            ]
        );
        $client1->assignRole('client');
        $client1->markEmailAsVerified();

        $client2 = User::firstOrCreate(
            ['email' => 'maria.lopez@email.com'],
            [
                'name'     => 'María López',
                'password' => Hash::make('password'),
                'phone'    => '+58 412-4445566',
                'active'   => true,
            ]
        );
        $client2->assignRole('client');
        $client2->markEmailAsVerified();

        $this->command->info('✅ Sample clients created:');
        $this->command->info('   - carlos.garcia@email.com / password');
        $this->command->info('   - maria.lopez@email.com / password');

        // -------------------------------------------------------------------
        // Sample Vehicles for Clients
        // -------------------------------------------------------------------
        Vehicle::firstOrCreate(
            [
                'client_id' => $client1->id,
                'plate'     => 'ABC-123',
            ],
            [
                'brand'            => 'Toyota',
                'model'            => 'Corolla',
                'year'             => 2021,
                'color'            => 'Blanco',
                'engine_type'      => 'gasoline',
                'transmission'     => 'automatic',
                'mileage'          => 35000,
                'vin'              => '1HGBH41JXMN109186',
            ]
        );

        Vehicle::firstOrCreate(
            [
                'client_id' => $client1->id,
                'plate'     => 'XYZ-789',
            ],
            [
                'brand'            => 'Honda',
                'model'            => 'Civic',
                'year'             => 2020,
                'color'            => 'Negro',
                'engine_type'      => 'gasoline',
                'transmission'     => 'manual',
                'mileage'          => 52000,
                'vin'              => '2HGFC2F59NH558892',
            ]
        );

        Vehicle::firstOrCreate(
            [
                'client_id' => $client2->id,
                'plate'     => 'DEF-456',
            ],
            [
                'brand'            => 'Chevrolet',
                'model'            => 'Aveo',
                'year'             => 2019,
                'color'            => 'Rojo',
                'engine_type'      => 'gasoline',
                'transmission'     => 'manual',
                'mileage'          => 78000,
                'vin'              => 'KL1TD56679B123456',
            ]
        );

        $this->command->info('✅ Sample vehicles created for clients.');
    }
}
