<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $connection = 'desarrollo-social';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
