<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relatorio extends Model
{
    use HasFactory;

    protected $table = 'relatorios';

    protected $fillable = [
        'tipo',
        'periodo_inicio',
        'periodo_fim',
        'dados',
        'gerado_por_id',
        'arquivo_path',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
        'dados' => 'array',
    ];

    public function geradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerado_por_id');
    }
}
