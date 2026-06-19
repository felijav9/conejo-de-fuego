<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Orden;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class OrdenesListado extends Component
{
    use WithPagination;

    public string $search = '';

    public string $estado = '';

    public ?Orden $selectedOrden = null;

    public ?int $deleteId = null;

    public int $per_page = 10;

    public function getRowsProperty()
    {
        return Orden::with([
                'mesa',
                'items.producto'
            ])
            ->when($this->search, function ($query) {

                $search = $this->search;

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'numero',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'tipo',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'estado',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas('mesa', function ($mesa) use ($search) {

                        $mesa->where(
                            'numero',
                            'like',
                            "%{$search}%"
                        );

                    });

                });

            })

            ->when($this->estado, function ($query) {

                $query->where(
                    'estado',
                    $this->estado
                );

            })

            ->latest()
            ->paginate($this->per_page);
    }

    public function showDetail($id)
    {
        $this->selectedOrden = Orden::with([
            'mesa',
            'items.producto'
        ])->findOrFail($id);

        Flux::modal('detalle-orden-modal')->show();
    }

    public function confirmCancel($id)
    {
        $this->deleteId = $id;

        Flux::modal('cancelar-orden-modal')->show();
    }

    public function cancel()
    {
        $orden = Orden::findOrFail(
            $this->deleteId
        );

        $orden->update([
            'estado' => 'cancelada'
        ]);

        $this->deleteId = null;

        Flux::modal('cancelar-orden-modal')
            ->close();
    }

    public function render()
    {
        return view(
            'livewire.conejo-de-fuego.ordenes-listado'
        );
    }
}