<?php

namespace App\Policies;

use App\Models\Decisao;
use App\Models\User;

class DecisaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('decisoes.view');
    }

    public function view(User $user, Decisao $decisao): bool
    {
        return $user->hasPermissionTo('decisoes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('decisoes.create');
    }

    public function update(User $user, Decisao $decisao): bool
    {
        return $user->hasPermissionTo('decisoes.update');
    }

    public function delete(User $user, Decisao $decisao): bool
    {
        return $user->hasPermissionTo('decisoes.delete');
    }
}
