<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se o usuário já existe
        $user = User::where('email', 'admin@semear.com')->first();
        
        if (!$user) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@semear.com',
                'password' => Hash::make('12345678'),
            ]);
            
            $this->command->info('Usuário de teste criado com sucesso!');
            $this->command->info('Email: admin@semear.com');
            $this->command->info('Senha: 12345678');
        } else {
            $this->command->info('Usuário de teste já existe!');
        }
    }
}
