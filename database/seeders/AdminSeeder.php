<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar todas as permissões primeiro
        $this->call(PermissionSeeder::class);

        // Criar role Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Atribuir todas as permissões ao Super Admin
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        // Criar primeiro usuário admin mestre
        $admin = User::firstOrCreate(
            ['email' => 'admin@semear.com'],
            [
                'name' => 'Administrador Mestre',
                'password' => Hash::make('admin123'),
            ]
        );

        // Atribuir role Super Admin ao admin
        if (!$admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }

        // Criar role Admin (com permissões de gerenciamento)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'usuarios.view',
            'usuarios.create',
            'usuarios.update',
            'permissoes.view',
            'permissoes.manage',
        ]);

        // Criar role Usuário (sem permissões especiais)
        Role::firstOrCreate(['name' => 'Usuário', 'guard_name' => 'web']);

        $this->command->info('Admin mestre criado com sucesso!');
        $this->command->info('Email: admin@semear.com');
        $this->command->info('Senha: admin123');
        $this->command->warn('IMPORTANTE: Altere a senha após o primeiro login!');
    }
}
