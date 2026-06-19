<?php

use App\Livewire\ConejoDeFuego\Categorias;

use App\Livewire\ConejoDeFuego\Mesas;
use App\Livewire\ConejoDeFuego\Ordenes;
use App\Livewire\ConejoDeFuego\Productos;
// ultimos 3 livewire
use App\Livewire\ConejoDeFuego\OrdenesListado;
use App\Livewire\ConejoDeFuego\Cocina;
use App\Livewire\ConejoDeFuego\DashboardMesas;
use Illuminate\Support\Facades\Route;
use App\Livewire\ConejoDeFuego\Bebidas;
use App\Livewire\ConejoDeFuego\Facturacion;
use App\Livewire\ConejoDeFuego\DashboardVentas;
Route::prefix('conejo-de-fuego')->group(function () {

    Route::get('registro-categorias', Categorias::class)
        ->middleware(['can:page.view.conejo-de-fuego.registro-categorias'])
        ->name('conejo-de-fuego.registro-categorias');

    Route::get('registro-comidas', Productos::class)
        ->middleware(['can:page.view.conejo-de-fuego.registro-comidas'])
        ->name('conejo-de-fuego.registro-comidas');
    Route::get('admin-mesas', Mesas::class)
        ->middleware(['can:page.view.conejo-de-fuego.admin-mesas'])
        ->name('conejo-de-fuego.admin-mesas');
    Route::get('admin-ordenes', Ordenes::class)
        ->middleware(['can:page.view.conejo-de-fuego.admin-ordenes'])
        ->name('conejo-de-fuego.admin-ordenes');

    Route::get('ordenes-listado', OrdenesListado::class)
        ->middleware(['can:page.view.conejo-de-fuego.ordenes-listado'])
        ->name('conejo-de-fuego.ordenes-listado');
    Route::get('cocina', Cocina::class)
        ->middleware(['can:page.view.conejo-de-fuego.cocina'])
        ->name('conejo-de-fuego.cocina');

    Route::get('bebidas', Bebidas::class)
        ->middleware(['can:page.view.conejo-de-fuego.bebidas'])
        ->name('conejo-de-fuego.bebidas');

    Route::get('facturacion', Facturacion::class)
        ->middleware(['can:page.view.conejo-de-fuego.bebidas'])
        ->name('conejo-de-fuego.facturacion');


    Route::get('dashboard-ventas', DashboardVentas::class)
        ->middleware(['can:page.view.conejo-de-fuego.dashboard-ventas'])
        ->name('conejo-de-fuego.dashboard-ventas');

    Route::get('dashboard-mesas', DashboardMesas::class)
        ->middleware(['can:page.view.conejo-de-fuego.dashboard-mesas'])
        ->name('conejo-de-fuego.dashboard-mesas');
});
