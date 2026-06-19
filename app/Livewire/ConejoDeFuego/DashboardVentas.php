<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Orden;
use Livewire\Component;

class DashboardVentas extends Component
{
    public float $hoyTotal = 0;
    public int $hoyOrdenes = 0;

    public array $labels = [];
    public array $data = [];

    public $ordenSeleccionada = null;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $hoy = now()->toDateString();

        // HOY
        $hoyQuery = Orden::where('estado', 'facturada')
            ->whereDate('created_at', $hoy);

        $this->hoyTotal = (float) $hoyQuery->sum('total');
        $this->hoyOrdenes = (int) $hoyQuery->count();

        // ÚLTIMOS 7 DÍAS
        $ventas = Orden::where('estado', 'facturada')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $this->labels = $ventas->pluck('fecha')->toArray();
        $this->data = $ventas->pluck('total')->toArray();
    }

    // 📄 VER FACTURA (SIMULADA)
    public function verFactura($id)
    {
        $this->ordenSeleccionada = Orden::with([
            'mesa',
            'items.producto'
        ])->findOrFail($id);
    }

    // 🖨️ IMPRIMIR / DESCARGAR
    public function imprimir()
    {
        $this->dispatch('print-factura');
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.dashboard-ventas', [
            'ordenes' => Orden::with('mesa')
                ->where('estado', 'facturada')
                ->whereDate('created_at', now())
                ->latest()
                ->get()
        ]);
    }
}