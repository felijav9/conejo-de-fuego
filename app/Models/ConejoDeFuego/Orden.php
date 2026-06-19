<?php

namespace App\Models\ConejoDeFuego;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'numero',
        'mesa_id',
        'tipo',
        'estado',
        'subtotal',
        'total'
    ];

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenItem::class);
    }
}