<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipanteReuniao extends Model
{
    use HasFactory;

    protected $table = 'participantes_reuniao';

    protected $fillable = [
        'reuniao_id',
        'user_id',
        'confirmado',
        'presente',
    ];

    protected $casts = [
        'confirmado' => 'boolean',
        'presente' => 'boolean',
    ];

    public function reuniao(): BelongsTo
    {
        return $this->belongsTo(Reuniao::class, 'reuniao_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
