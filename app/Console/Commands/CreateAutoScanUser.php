<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAutoScanUser extends Command
{
    protected $signature = 'autoscan:create-user {name} {email} {password} {role=client}';
    protected $description = 'Create a new user for AutoScan';

    public function handle(): int
    {
        $role = $this->argument('role');
        
        if (!in_array($role, ['admin', 'technician', 'client'])) {
            $this->error('Rol inválido. Roles permitidos: admin, technician, client');
            return 1;
        }

        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => bcrypt($this->argument('password')),
            'role' => $role,
        ]);

        $user->assignRole($role);

        $this->info("Usuario {$user->name} ({$user->email}) creado exitosamente con rol {$role}.");
        return 0;
    }
}
