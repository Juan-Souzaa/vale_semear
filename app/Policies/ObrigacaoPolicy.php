<?php

namespace App\Policies;

use App\Models\Obrigacao;
use App\Models\User;

class ObrigacaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('obrigacoes.view');
    }

    public function view(User $user, Obrigacao $obrigacao): bool
    {
        return $user->hasPermissionTo('obrigacoes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('obrigacoes.create');
    }

    public function update(User $user, Obrigacao $obrigacao): bool
    {
        return $user->hasPermissionTo('obrigacoes.update');
    }

    public function delete(User $user, Obrigacao $obrigacao): bool
    {
        return $user->hasPermissionTo('obrigacoes.delete');
    }
}
