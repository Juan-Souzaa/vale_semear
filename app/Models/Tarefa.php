<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarefa extends Model
{
    use HasFactory;

    protected $table = 'tarefas';

    protected $fillable = [
        'decisao_id',
        'titulo',
        'descricao',
        'status',
        'data_vencimento',
        'responsavel_id',
        'prioridade',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    public function decisao(): BelongsTo
    {
        return $this->belongsTo(Decisao::class, 'decisao_id');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
