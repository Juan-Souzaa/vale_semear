<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reuniao extends Model
{
    use HasFactory;

    protected $table = 'reunioes';

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'data_hora',
        'local',
        'status',
        'organizador_id',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizador_id');
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(ParticipanteReuniao::class, 'reuniao_id');
    }

    public function atas(): HasMany
    {
        return $this->hasMany(Ata::class, 'reuniao_id');
    }

    public function decisoes(): HasMany
    {
        return $this->hasMany(Decisao::class, 'reuniao_id');
    }

    public function lembretes(): HasMany
    {
        return $this->hasMany(LembreteReuniao::class, 'reuniao_id');
    }
}
