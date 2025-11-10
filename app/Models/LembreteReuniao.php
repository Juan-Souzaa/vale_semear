<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LembreteReuniao extends Model
{
    use HasFactory;

    protected $table = 'lembretes_reuniao';

    protected $fillable = [
        'reuniao_id',
        'tipo',
        'enviado',
        'data_envio',
    ];

    protected $casts = [
        'enviado' => 'boolean',
        'data_envio' => 'datetime',
    ];

    public function reuniao(): BelongsTo
    {
        return $this->belongsTo(Reuniao::class, 'reuniao_id');
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            '1_dia' => '1 dia antes',
            '3_dias' => '3 dias antes',
            '1_semana' => '1 semana antes',
            default => $this->tipo
        };
    }
}
