<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Mesa;
use Flux\Flux;
use Livewire\Component;

class DashboardMesas extends Component
{
    public ?Mesa $selectedMesa = null;

    public function verMesa($id)
    {
        $this->selectedMesa = Mesa::with([
            'ordenes.items.producto'
        ])->findOrFail($id);

        Flux::modal('detalle-mesa-modal')->show();
    }

    public function render()
    {
        return view(
            'livewire.conejo-de-fuego.dashboard-mesas',
            [
                'mesas' => Mesa::withCount([
                    'ordenes'
                ])
                ->orderBy('numero')
                ->get()
            ]
        );
    }
}