<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Obrigacao extends Model
{
    use HasFactory;

    protected $table = 'obrigacoes';

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'data_vencimento',
        'data_lembrete',
        'status',
        'prioridade',
        'responsavel_id',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_lembrete' => 'date',
    ];

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function isVencida(): bool
    {
        return $this->status === 'vencida' || 
               ($this->data_vencimento < now() && $this->status !== 'concluida');
    }

    public function isUrgente(): bool
    {
        return $this->prioridade === 'urgente' || 
               ($this->data_vencimento <= now()->addDays(3) && $this->status !== 'concluida');
    }
}
