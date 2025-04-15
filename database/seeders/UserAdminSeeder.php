<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creación de usuario con asignación de rol administrador
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('123456'),
        ])->assignRole('admin');
    }
}
