<?php

namespace Database\Seeders\ConejoDeFuego;

use App\Models\ConejoDeFuego\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Desayunos y Cena',
            'Almuerzos',
            'Pastas',
            'Antojitos',
            'Hamburguesas',
            'Bebidas Calientes',
            'Bebidas Frías',
            'Licores / Bebidas Preparadas',
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate([
                'nombre' => $categoria,
            ], [
                'activo' => true,
            ]);
        }
    }
}