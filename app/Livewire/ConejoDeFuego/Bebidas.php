<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Mesa;
use App\Models\ConejoDeFuego\Orden;
use App\Traits\Interact;
use Livewire\Component;
use Livewire\WithPagination;

class Bebidas extends Component
{
    use WithPagination, Interact;

    public string $search = '';

    public int $per_page = 10;

    public function getRowsProperty()
    {
        return Orden::with([
                'mesa',
                'items.producto.categoria'
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero', 'like', "%{$this->search}%")
                        ->orWhere('estado', 'like', "%{$this->search}%");
                });
            })
            ->whereNotIn('estado', [
                'entregada',
                'cancelada'
            ])
            // Asegura que la orden tenga al menos un producto de la categoría de bebidas
            ->whereHas('items.producto.categoria', function ($query) {
                $query->whereIn('nombre', [
                    'Bebidas Calientes', 
                    'Bebidas Frías', 
                    'Licores / Bebidas Preparadas'
                ]);
            })
            ->latest()
            ->paginate($this->per_page);
    }

    public function cambiarEstado($id)
    {
        $orden = Orden::findOrFail($id);

        $nuevoEstado = match ($orden->estado) {
            'pendiente' => 'preparando',
            'preparando' => 'lista',
            'lista' => 'entregada',
            default => $orden->estado,
        };

        $orden->update([
            'estado' => $nuevoEstado
        ]);

        /*
        liberar mesa cuando la orden finaliza
        */
        if (
            $nuevoEstado === 'entregada'
            && $orden->mesa_id
        ) {
            Mesa::where(
                'id',
                $orden->mesa_id
            )->update([
                'estado' => 'libre'
            ]);
        }

        $this->toastSuccess(
            'Estado actualizado'
        );
    }

    public function cancelar($id)
    {
        $orden = Orden::findOrFail($id);

        $orden->update([
            'estado' => 'cancelada'
        ]);

        if ($orden->mesa_id) {
            Mesa::where(
                'id',
                $orden->mesa_id
            )->update([
                'estado' => 'libre'
            ]);
        }

        $this->toastSuccess(
            'Orden cancelada'
        );
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.bebidas');
    }
}