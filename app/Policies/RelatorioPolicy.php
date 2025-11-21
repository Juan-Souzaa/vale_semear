<?php

namespace App\Policies;

use App\Models\User;

class RelatorioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('relatorios.view');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('relatorios.export');
    }
}
