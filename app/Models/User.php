<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacionamentos
    public function atividadesResponsavel()
    {
        return $this->hasMany(Atividade::class, 'responsavel_id');
    }

    public function obrigacoesResponsavel()
    {
        return $this->hasMany(Obrigacao::class, 'responsavel_id');
    }

    public function reunioesOrganizadas()
    {
        return $this->hasMany(Reuniao::class, 'organizador_id');
    }

    public function participantesReuniao()
    {
        return $this->hasMany(ParticipanteReuniao::class, 'user_id');
    }

    public function atasCriadas()
    {
        return $this->hasMany(Ata::class, 'criado_por_id');
    }

    public function decisoesResponsavel()
    {
        return $this->hasMany(Decisao::class, 'responsavel_id');
    }

    public function tarefasResponsavel()
    {
        return $this->hasMany(Tarefa::class, 'responsavel_id');
    }

    public function relatoriosGerados()
    {
        return $this->hasMany(Relatorio::class, 'gerado_por_id');
    }

    /**
     * Verifica se o usuário é Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Verifica se o usuário pode gerenciar permissões
     */
    public function canManagePermissions(): bool
    {
        return $this->isSuperAdmin() || $this->hasPermissionTo('permissoes.manage');
    }
}
