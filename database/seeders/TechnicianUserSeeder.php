<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TechnicianUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a sample technician user.
     */
    public function run(): void
    {
        $technician = User::firstOrCreate(
            ['email' => 'pedro.martinez@autoscan.com'],
            [
                'name'     => 'Pedro Martínez',
                'password' => Hash::make('password'),
                'phone'    => '+58 416-7778899',
                'active'   => true,
            ]
        );
        $technician->assignRole('technician');
        $technician->markEmailAsVerified();

        $this->command->info('✅ Technician user created: pedro.martinez@autoscan.com / password');
    }
}
