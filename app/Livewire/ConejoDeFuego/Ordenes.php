<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Mesa;
use App\Models\ConejoDeFuego\Orden;
use App\Models\ConejoDeFuego\OrdenItem;
use App\Models\ConejoDeFuego\Producto;
use App\Traits\Interact;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ordenes extends Component
{
    use Interact;

    public string $tipo = 'mesa';

    public ?int $mesa_id = null;

    public array $items = [];

    public float $total = 0;

    public function mount()
    {
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'producto_id' => null,
            'cantidad' => 1,
            'precio' => 0,
            'subtotal' => 0,
            'nota' => '',
        ];

        $this->calculateTotal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);

        $this->items = array_values($this->items);

        if (count($this->items) === 0) {
            $this->addItem();
        }

        $this->calculateTotal();
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'items.')) {
            $this->recalculateItems();
        }
    }

    public function recalculateItems()
    {
        foreach ($this->items as $index => $item) {

            if (empty($item['producto_id'])) {

                $this->items[$index]['precio'] = 0;
                $this->items[$index]['subtotal'] = 0;

                continue;
            }

            $producto = Producto::find($item['producto_id']);

            if (! $producto) {
                continue;
            }

            $precio = (float) $producto->precio;
            $cantidad = max(
                1,
                (int) ($item['cantidad'] ?? 1)
            );

            $this->items[$index]['cantidad'] = $cantidad;
            $this->items[$index]['precio'] = $precio;
            $this->items[$index]['subtotal'] =
                round($precio * $cantidad, 2);
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->items)
            ->sum(function ($item) {
                return (float) ($item['subtotal'] ?? 0);
            });
    }

    public function save()
    {
        $this->validate([
            'tipo' => 'required|in:mesa,llevar',
        ]);

        if (
            $this->tipo === 'mesa'
            && ! $this->mesa_id
        ) {
            $this->addError(
                'mesa_id',
                'Debe seleccionar una mesa'
            );

            return;
        }

        $productosValidos = collect($this->items)
            ->filter(fn ($item) => ! empty($item['producto_id']));

        if ($productosValidos->count() === 0) {

            $this->addError(
                'items',
                'Debe agregar al menos un producto'
            );

            return;
        }

        DB::transaction(function () {

            $numero = 'ORD-'.
                str_pad(
                    Orden::count() + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            $orden = Orden::create([
                'numero' => $numero,
                'mesa_id' => $this->mesa_id,
                'tipo' => $this->tipo,
                'estado' => 'pendiente',
                'subtotal' => $this->total,
                'total' => $this->total,
            ]);

            foreach ($this->items as $item) {

                if (empty($item['producto_id'])) {
                    continue;
                }

                OrdenItem::create([
                    'orden_id' => $orden->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => (int) $item['cantidad'],
                    'precio_unitario' => (float) $item['precio'],
                    'subtotal' => (float) $item['subtotal'],
                    'nota' => $item['nota'] ?? null,
                ]);
            }

            if ($this->tipo === 'mesa' && $this->mesa_id) {

                Mesa::where('id', $this->mesa_id)
                    ->update([
                        'estado' => 'ocupada',
                    ]);
            }
        });

        $this->reset([
            'mesa_id',
            'items',
            'total',
        ]);

        $this->tipo = 'mesa';

        $this->addItem();

        if (method_exists($this, 'toastSuccess')) {
            $this->toastSuccess(
                'Orden registrada correctamente'
            );
        }
    }

    public function render()
    {
        return view(
            'livewire.conejo-de-fuego.ordenes',
            [
                'mesas' => Mesa::where(
                    'estado',
                    'libre'
                )
                    ->orderBy('numero')
                    ->get(),

                'productos' => Producto::where(
                    'activo',
                    true
                )
                    ->orderBy('nombre')
                    ->get(),
            ]
        );
    }
}
