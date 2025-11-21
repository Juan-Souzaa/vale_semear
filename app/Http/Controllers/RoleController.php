<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('permissoes.view');
        
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        
        // Buscar todos os usuários para gerenciamento
        $users = \App\Models\User::with('roles')->get();
        
        return view('roles.index', compact('roles', 'permissions', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('permissoes.manage');
        
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('permissoes.manage');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        
        if (isset($validated['permissions'])) {
            // Converter IDs para inteiros e buscar os objetos Permission
            $permissionIds = array_map('intval', $validated['permissions']);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        Gate::authorize('permissoes.view');
        
        $role->load(['permissions', 'users']);
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('permissoes.update');
        
        // Não permitir editar Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Não é possível editar a role Super Admin.');
        }
        
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('permissoes.update');
        
        // Não permitir editar Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Não é possível editar a role Super Admin.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        
        if (isset($validated['permissions'])) {
            // Converter IDs para inteiros e buscar os objetos Permission
            $permissionIds = array_map('intval', $validated['permissions']);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('permissoes.delete');
        
        // Não permitir excluir Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Não é possível excluir a role Super Admin.');
        }
        
        // Verificar se há usuários com esta role
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Não é possível excluir a role. Existem usuários atribuídos a ela.');
        }
        
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role excluída com sucesso!');
    }
}
