<?php

namespace Database\Seeders\ConejoDeFuego;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConejoDeFuego\Mesa;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Mesa::create([
                'numero' => "MESA-$i",
                'estado' => 'libre',
            ]);
        }
    }
}
