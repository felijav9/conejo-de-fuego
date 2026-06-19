<?php

namespace App\Models\UnidadConvivenciaSocial\PasosPedales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{

    use HasFactory;

    protected $connection = 'unidad-convivencia-social';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
    ];

    public function workflows() {
        return $this->hasMany(WorkFlow::class);
    }
}
