<?php

namespace App\Policies;

use App\Models\Ata;
use App\Models\User;

class AtaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('atas.view');
    }

    public function view(User $user, Ata $ata): bool
    {
        return $user->hasPermissionTo('atas.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('atas.create');
    }

    public function update(User $user, Ata $ata): bool
    {
        return $user->hasPermissionTo('atas.update');
    }

    public function delete(User $user, Ata $ata): bool
    {
        return $user->hasPermissionTo('atas.delete');
    }

    public function approve(User $user, Ata $ata): bool
    {
        return $user->hasPermissionTo('atas.approve');
    }
}
