<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    /**
     * Mostrar página de gerenciamento de roles e permissões do usuário
     */
    public function show(User $user)
    {
        $this->authorize('update', $user);
        
        $user->load('roles', 'permissions');
        $roles = Role::all();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('users.roles', compact('user', 'roles', 'permissions'));
    }

    /**
     * Atribuir roles ao usuário
     */
    public function assignRoles(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Converter IDs para inteiros e buscar as roles
        $roleIds = isset($validated['roles']) ? array_map('intval', $validated['roles']) : [];
        $roles = Role::whereIn('id', $roleIds)->get();
        
        $user->syncRoles($roles);

        return redirect()->route('users.roles', $user)->with('success', 'Roles atualizadas com sucesso!');
    }

    /**
     * Atribuir permissões diretas ao usuário
     */
    public function assignPermissions(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Converter IDs para inteiros e buscar as permissões
        $permissionIds = isset($validated['permissions']) ? array_map('intval', $validated['permissions']) : [];
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        $user->syncPermissions($permissions);

        return redirect()->route('users.roles', $user)->with('success', 'Permissões atualizadas com sucesso!');
    }
}
