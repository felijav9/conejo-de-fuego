<?php

namespace App\Models\ConejoDeFuego;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'area',
        'categoria_id',
        'activo'
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrdenItem::class);
    }
}