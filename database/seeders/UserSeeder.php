<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder não cria mais usuários, apenas atribui roles aos usuários existentes.
     * O admin é criado pelo AdminSeeder e os usuários de teste pelo DatabaseTestSeeder.
     */
    public function run(): void
    {
        // Buscar role "Usuário" para atribuir aos usuários sem role
        $roleUsuario = Role::where('name', 'Usuário')->first();
        
        if ($roleUsuario) {
            // Atribuir role "Usuário" a todos os usuários que não têm role
            $usersSemRole = User::doesntHave('roles')->get();
            
            foreach ($usersSemRole as $user) {
                // Não atribuir role ao admin mestre (já tem Super Admin)
                if ($user->email !== 'admin@semear.com') {
                    $user->assignRole($roleUsuario);
                }
            }
            
            if ($usersSemRole->count() > 0) {
                $this->command->info('Roles atribuídas aos usuários sem role!');
            }
        }
    }
}
