<?php

namespace Database\Seeders\ConejoDeFuego;

use Illuminate\Database\Seeder;
use App\Models\ConejoDeFuego\Categoria;
use App\Models\ConejoDeFuego\Producto;

class ComidaMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. OBTENCIÓN O CREACIÓN DE CATEGORÍAS (Consistente con CategoriaSeeder)
        $catDesayunosCena = Categoria::firstOrCreate(['nombre' => 'Desayunos y Cena'], ['activo' => true]);
        $catAlmuerzos     = Categoria::firstOrCreate(['nombre' => 'Almuerzos'], ['activo' => true]);
        $catPastas        = Categoria::firstOrCreate(['nombre' => 'Pastas'], ['activo' => true]);
        $catAntojitos     = Categoria::firstOrCreate(['nombre' => 'Antojitos'], ['activo' => true]);
        $catHamburguesas  = Categoria::firstOrCreate(['nombre' => 'Hamburguesas'], ['activo' => true]);
        
        // Nuevas categorías de bebidas extraídas de tus imágenes
        $catBebidasCalientes = Categoria::firstOrCreate(['nombre' => 'Bebidas Calientes'], ['activo' => true]);
        $catBebidasFrias     = Categoria::firstOrCreate(['nombre' => 'Bebidas Frías'], ['activo' => true]);
        $catLicores          = Categoria::firstOrCreate(['nombre' => 'Licores / Bebidas Preparadas'], ['activo' => true]);

        // 2. POBLAR PRODUCTOS POR CATEGORÍA

        // --- DESAYUNOS Y CENA ---
        $desayunos = [
            [
                'nombre' => 'Tradicional',
                'descripcion' => 'Huevos al gusto acompañados de frijol, crema, una tortilla con queso mozzarella, salsa de tomate y plátanos fritos. Incluyen: avena, café y tortillas.',
                'precio' => 30.00,
            ],
            [
                'nombre' => "Chu' Taxtyox",
                'descripcion' => 'Huevos al gusto acompañado de 2 chorizos o 2 longanizas ahumado (a elección), frijol, crema, una tortilla con queso mozzarella, salsa de tomate y plátanos fritos. Incluyen: avena, café y tortillas.',
                'precio' => 45.00,
            ],
            [
                'nombre' => 'Conejo de Fuego',
                'descripcion' => 'Huevos revueltos con tomate, cebolla, chile jalapeño y chile pimiento, acompañado de lomito, frijol, crema, una tortilla con queso mozzarella, salsa de tomate y plátanos fritos. Incluyen: avena, café y tortillas.',
                'precio' => 50.00,
            ],
            [
                'nombre' => 'Omelette',
                'descripcion' => 'Relleno de jamón, queso, champiñones, tomate, pimientos y cebolla, acompañado de frijol, crema, salsa de tomate y plátanos fritos. Incluyen: avena, café y tortillas.',
                'precio' => 40.00,
            ],
            [
                'nombre' => 'Cereal',
                'descripcion' => 'Cereal, leche y banano. Incluyen: avena, café y tortillas.',
                'precio' => 25.00,
            ],
        ];
        foreach ($desayunos as $item) {
            $catDesayunosCena->productos()->create(array_merge($item, ['area' => 'cocina', 'activo' => true]));
        }

        // --- ALMUERZOS (ASADOS, CALDOS Y MARISCOS) ---
        $almuerzos = [
            ['nombre' => 'Asado de Lomito', 'precio' => 45.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Asado de Pollo', 'precio' => 45.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Asado de Riñón', 'precio' => 45.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Asado de Hígado', 'precio' => 45.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Asado de Adobado', 'precio' => 45.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Asado de Lengua', 'precio' => 50.00, 'descripcion' => 'Incluye fresco natural, arroz, frijoles, ensalada verde, aderezos, chirmol y picante.'],
            ['nombre' => 'Mojarras Fritas o Empanizadas', 'precio' => 75.00, 'descripcion' => 'Mojarra frita o empanizada.'],
            ['nombre' => 'Camarones Empanizados', 'precio' => 75.00, 'descripcion' => 'Porción de camarones empanizados.'],
            ['nombre' => 'Camarones al Ajillo / Pan Ajo o Simple', 'precio' => 90.00, 'descripcion' => 'Camarones al ajillo con pan con ajo o simple.'],
            ['nombre' => 'Camarones a la Diabla', 'precio' => 90.00, 'descripcion' => 'Camarones cocinados en salsa a la diabla.'],
            ['nombre' => 'Caldo de Res (Miércoles)', 'precio' => 45.00, 'descripcion' => 'Caldo de res tradicional (disponible miércoles).'],
            ['nombre' => 'Caldo de Pata (Domingos)', 'precio' => 45.00, 'descripcion' => 'Caldo de pata tradicional (disponible domingos).'],
            ['nombre' => 'Ceviches de Camarón / Cangrejo', 'precio' => 60.00, 'descripcion' => 'Ceviche fresco de camarón o cangrejo.'],
            ['nombre' => 'Aguachile', 'precio' => 60.00, 'descripcion' => 'Platillo tradicional de aguachile.'],
        ];
        foreach ($almuerzos as $item) {
            $catAlmuerzos->productos()->create(array_merge($item, ['area' => 'cocina', 'activo' => true]));
        }

        // --- PASTAS ---
        $pastas = [
            ['nombre' => 'Lasagna a la Bolañesa Individual', 'precio' => 50.00, 'descripcion' => 'Acompañado de pan con ajo o simple.'],
            ['nombre' => 'Espagueti a la Bolañesa', 'precio' => 35.00, 'descripcion' => 'Acompañado de pan con ajo o simple.'],
        ];
        foreach ($pastas as $item) {
            $catPastas->productos()->create(array_merge($item, ['area' => 'cocina', 'activo' => true]));
        }

        // --- HAMBURGUESAS ---
        $hamburguesas = [
            ['nombre' => 'Hamburguesa Clásica', 'precio' => 25.00, 'descripcion' => 'Carne, vegetales y aderezos. Incluyen papas fritas o papalina.'],
            ['nombre' => 'Queso Hamburguesa', 'precio' => 35.00, 'descripcion' => 'Carne, queso mozzarella, queso craft, vegetales y aderezos. Incluyen papas fritas o papalina.'],
            ['nombre' => 'Torito', 'precio' => 35.00, 'descripcion' => 'Carne, huevo, queso craft, vegetales y aderezos. Incluyen papas fritas o papalina.'],
            ['nombre' => 'Súper Torito', 'precio' => 42.00, 'descripcion' => 'Doble carne, huevo, queso craft, vegetales y aderezos. Incluyen papas fritas o papalina.'],
            ['nombre' => '1/4 Libra', 'precio' => 45.00, 'descripcion' => 'Carne, huevo, queso craft, queso mozzarella, vegetales y aderezos. Incluyen papas fritas o papalina.'],
            ['nombre' => 'Hamburguesas Hawaianas', 'precio' => 45.00, 'descripcion' => 'Carne, huevo, queso craft, queso mozzarella, piña, vegetales y aderezos. Incluyen papas fritas o papalina.'],
        ];
        foreach ($hamburguesas as $item) {
            $catHamburguesas->productos()->create(array_merge($item, ['area' => 'cocina', 'activo' => true]));
        }

        // --- ANTOJITOS Y POSTRES ---
        $antojitos = [
            ['nombre' => 'Papas Fritas Simples', 'precio' => 18.00, 'descripcion' => 'Papas fritas simples.'],
            ['nombre' => 'Nachos con Carne', 'precio' => 30.00, 'descripcion' => 'Nachos acompañados con carne.'],
            ['nombre' => 'Burritos', 'precio' => 25.00, 'descripcion' => 'Burritos de la casa.'],
            ['nombre' => 'Papas Fritas con Carne', 'precio' => 25.00, 'descripcion' => 'Papas fritas crujientes con carne.'],
            ['nombre' => 'Quesadillas', 'precio' => 35.00, 'descripcion' => 'Quesadillas tradicionales.'],
            ['nombre' => 'Poporopos', 'precio' => 10.00, 'descripcion' => 'Porción de poporopos.'],
            ['nombre' => 'Granizadas', 'precio' => 10.00, 'descripcion' => 'Granizadas de sabores.'],
            ['nombre' => 'Crepa Nutella', 'precio' => 25.00, 'descripcion' => 'Crepa con Nutella.'],
            ['nombre' => 'Crepa de Nutella y Banano', 'precio' => 28.00, 'descripcion' => 'Crepa dulce de Nutella con banano.'],
            ['nombre' => 'Crepa de Nutella y Fresa', 'precio' => 30.00, 'descripcion' => 'Crepa dulce de Nutella con fresas.'],
            ['nombre' => 'Crepa Mixta', 'precio' => 35.00, 'descripcion' => 'Crepa mixta con frutas.'],
            ['nombre' => 'Crepa de Melocotón', 'precio' => 38.00, 'descripcion' => 'Crepa dulce con melocotón.'],
            ['nombre' => 'Plátanos Fritos', 'precio' => 20.00, 'descripcion' => 'Porción de plátanos fritos.'],
            ['nombre' => 'Copa de Helado', 'precio' => 20.00, 'descripcion' => 'Copa de helado de la casa.'],
            ['nombre' => 'Porción de Tortillas con Queso Mozzarella (6 tortillas)', 'precio' => 30.00, 'descripcion' => 'Seis tortillas rellenas de queso mozzarella.'],
        ];
        foreach ($antojitos as $item) {
            $catAntojitos->productos()->create(array_merge($item, ['area' => 'cocina', 'activo' => true]));
        }

        // --- BEBIDAS CALIENTES ---
        $calientes = [
            ['nombre' => 'Café Simple', 'precio' => 10.00],
            ['nombre' => 'Café con Cardamomo', 'precio' => 20.00],
            ['nombre' => 'Café con Leche o Cremora', 'precio' => 15.00],
            ['nombre' => 'Leche', 'precio' => 15.00],
            ['nombre' => 'Chocolate Artesanal', 'precio' => 15.00],
            ['nombre' => 'Chocolate Artesanal con Leche', 'precio' => 20.00],
            ['nombre' => 'Té', 'precio' => 10.00],
            ['nombre' => 'Infusión de Hierbas', 'precio' => 25.00],
            ['nombre' => 'Chocolate con Cardamomo', 'precio' => 20.00],
        ];
        foreach ($calientes as $item) {
            $catBebidasCalientes->productos()->create(array_merge($item, ['area' => 'bebidas', 'activo' => true]));
        }

        // --- BEBIDAS FRÍAS ---
        $frias = [
            ['nombre' => 'Botella de Agua Pura', 'precio' => 7.00],
            ['nombre' => 'Gaseosas en Lata', 'precio' => 7.00],
            ['nombre' => 'Gaseosas en Vidrio', 'precio' => 6.00],
            ['nombre' => 'Naranjada / Limonada Simple o con Soda', 'precio' => 20.00],
            ['nombre' => 'Licuados con y sin Leche (Papaya, Melón, Banano, Piña, Fresa, y Sandía)', 'precio' => 20.00],
            ['nombre' => 'Choco Milk Simple', 'precio' => 20.00],
            ['nombre' => 'Batido Choco Milk (Avena, Helado, Banano y Choco Milk)', 'precio' => 30.00],
            ['nombre' => 'Frappe Simple', 'precio' => 20.00],
            ['nombre' => 'Frappe Oreo', 'precio' => 25.00],
            ['nombre' => 'Café Frío', 'precio' => 20.00],
            ['nombre' => 'Smoothies (Arándanos, Maracuyá, Mango o Mora)', 'precio' => 30.00, 'descripcion' => 'Especialidad de la casa.'],
            ['nombre' => 'Black Berry (Fresa, Frambuesa, Mora y Arándanos)', 'precio' => 35.00, 'descripcion' => 'Especialidad de la casa.'],
            ['nombre' => 'Exótico (Mango, Maracuyá y Guayaba)', 'precio' => 35.00, 'descripcion' => 'Especialidad de la casa.'],
            ['nombre' => 'Tropical (Piña, Limón, Pepino y Menta)', 'precio' => 30.00, 'descripcion' => 'Especialidad de la casa.'],
        ];
        foreach ($frias as $item) {
            $catBebidasFrias->productos()->create(array_merge($item, ['area' => 'bebidas', 'activo' => true]));
        }

        // --- LICORES Y BEBIDAS PREPARADAS ---
        $licores = [
            ['nombre' => 'Cervezas Gallo y Corona', 'precio' => 15.00],
            ['nombre' => 'Cerveza Montecarlo', 'precio' => 20.00],
            ['nombre' => 'Tequila José Cuervo', 'precio' => 25.00],
            ['nombre' => 'Tequila Gran Malo', 'precio' => 35.00],
            ['nombre' => 'Whisky Johnnie Walker-Roja', 'precio' => 55.00],
            ['nombre' => 'Whisky Johnnie Walker-Negra', 'precio' => 45.00],
            ['nombre' => 'Ron Botrán 12 Años', 'precio' => 30.00],
            ['nombre' => 'Ron Zacapa 23 Años', 'precio' => 35.00],
            ['nombre' => 'Buchanan\'s 12 Años', 'precio' => 45.00],
            ['nombre' => 'Jager', 'precio' => 35.00],
            ['nombre' => 'Vino', 'precio' => 30.00],
            ['nombre' => 'Michelada (Gallo o Corona)', 'precio' => 30.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Mojito (Clásico, Fresa y Mora)', 'precio' => 40.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Margaritas', 'precio' => 35.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Piña Colada', 'precio' => 45.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Charro Negro', 'precio' => 45.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Picosa Gallo', 'precio' => 20.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Cimarrona', 'precio' => 20.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Mineral Preparada', 'precio' => 25.00, 'descripcion' => 'Bebida preparada.'],
            ['nombre' => 'Sangría', 'precio' => 35.00, 'descripcion' => 'Bebida preparada.'],
        ];
        foreach ($licores as $item) {
            $catLicores->productos()->create(array_merge($item, ['area' => 'bebidas', 'activo' => true]));
        }
    }
}