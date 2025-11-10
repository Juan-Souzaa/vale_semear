<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Decisao extends Model
{
    use HasFactory;

    protected $table = 'decisoes';

    protected $fillable = [
        'reuniao_id',
        'ata_id',
        'titulo',
        'descricao',
        'status',
        'prazo',
        'responsavel_id',
    ];

    protected $casts = [
        'prazo' => 'date',
    ];

    public function reuniao(): BelongsTo
    {
        return $this->belongsTo(Reuniao::class, 'reuniao_id');
    }

    public function ata(): BelongsTo
    {
        return $this->belongsTo(Ata::class, 'ata_id');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class, 'decisao_id');
    }
}
