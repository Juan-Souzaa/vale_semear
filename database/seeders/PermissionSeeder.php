<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissões de Atividades
        Permission::firstOrCreate(['name' => 'atividades.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atividades.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atividades.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atividades.delete', 'guard_name' => 'web']);

        // Permissões de Obrigações
        Permission::firstOrCreate(['name' => 'obrigacoes.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'obrigacoes.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'obrigacoes.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'obrigacoes.delete', 'guard_name' => 'web']);

        // Permissões de Reuniões
        Permission::firstOrCreate(['name' => 'reunioes.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reunioes.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reunioes.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reunioes.delete', 'guard_name' => 'web']);

        // Permissões de Atas
        Permission::firstOrCreate(['name' => 'atas.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atas.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atas.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atas.delete', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'atas.approve', 'guard_name' => 'web']);

        // Permissões de Decisões
        Permission::firstOrCreate(['name' => 'decisoes.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'decisoes.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'decisoes.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'decisoes.delete', 'guard_name' => 'web']);

        // Permissões de Relatórios
        Permission::firstOrCreate(['name' => 'relatorios.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'relatorios.export', 'guard_name' => 'web']);

        // Permissões de Usuários
        Permission::firstOrCreate(['name' => 'usuarios.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.delete', 'guard_name' => 'web']);

        // Permissões de Permissões (Gerenciamento)
        Permission::firstOrCreate(['name' => 'permissoes.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permissoes.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permissoes.delete', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permissoes.manage', 'guard_name' => 'web']);

        $this->command->info('Permissões criadas com sucesso!');
    }
}
