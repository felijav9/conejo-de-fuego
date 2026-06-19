<?php

namespace App\Models\ConejoDeFuego;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    protected $fillable = [
        'numero',
        'estado'
    ];

    public function ordenes(): HasMany
    {
        return $this->hasMany(Orden::class);
    }
}