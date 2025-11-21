<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('usuarios.view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('usuarios.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.update');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.delete');
    }
}
