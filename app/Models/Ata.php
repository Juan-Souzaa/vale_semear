<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ata extends Model
{
    use HasFactory;

    protected $table = 'atas';

    protected $fillable = [
        'reuniao_id',
        'numero',
        'data_aprovacao',
        'conteudo',
        'aprovada',
        'criado_por_id',
    ];

    protected $casts = [
        'data_aprovacao' => 'date',
        'aprovada' => 'boolean',
    ];

    public function reuniao(): BelongsTo
    {
        return $this->belongsTo(Reuniao::class, 'reuniao_id');
    }

    public function criadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por_id');
    }

    public function decisoes(): HasMany
    {
        return $this->hasMany(Decisao::class, 'ata_id');
    }
}
