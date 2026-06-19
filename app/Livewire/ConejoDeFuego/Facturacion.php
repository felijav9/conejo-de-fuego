<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Orden;
use Livewire\Component;
use Livewire\WithPagination;

class Facturacion extends Component
{
    use WithPagination;

    public string $search = '';
    public int $per_page = 10;

    public ?Orden $detalleOrden = null;

    public function getRowsProperty()
    {
        return Orden::with([
                'mesa',
                'items.producto'
            ])

            // SOLO ORDENES ENTREGADAS (listas para cobrar)
            ->where('estado', 'entregada')

            ->when($this->search, function ($query) {
                $query->where('numero', 'like', "%{$this->search}%");
            })

            ->latest()
            ->paginate($this->per_page);
    }

    // 🔍 VER DETALLE
    public function verDetalle($id)
    {
        $this->detalleOrden = Orden::with([
            'mesa',
            'items.producto'
        ])->findOrFail($id);
    }

    // 💰 FACTURAR ORDEN
    public function facturar($id)
    {
        $orden = Orden::findOrFail($id);

        if ($orden->estado !== 'entregada') {
            return;
        }

        $orden->update([
            'estado' => 'facturada'
        ]);

        // ✅ FIX TOAST (seguro)
        if (method_exists($this, 'toastSuccess')) {
            $this->toastSuccess('Orden facturada correctamente');
        }
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.facturacion');
    }
}