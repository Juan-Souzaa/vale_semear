<?php

namespace App\Policies;

use App\Models\Reuniao;
use App\Models\User;

class ReuniaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('reunioes.view');
    }

    public function view(User $user, Reuniao $reuniao): bool
    {
        return $user->hasPermissionTo('reunioes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('reunioes.create');
    }

    public function update(User $user, Reuniao $reuniao): bool
    {
        return $user->hasPermissionTo('reunioes.update');
    }

    public function delete(User $user, Reuniao $reuniao): bool
    {
        return $user->hasPermissionTo('reunioes.delete');
    }
}
