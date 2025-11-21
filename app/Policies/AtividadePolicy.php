<?php

namespace App\Policies;

use App\Models\Atividade;
use App\Models\User;

class AtividadePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('atividades.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Atividade $atividade): bool
    {
        return $user->hasPermissionTo('atividades.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('atividades.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Atividade $atividade): bool
    {
        return $user->hasPermissionTo('atividades.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Atividade $atividade): bool
    {
        return $user->hasPermissionTo('atividades.delete');
    }
}
