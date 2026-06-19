<div class="space-y-6">

    <div>
        <flux:heading size="xl">
            Facturación
        </flux:heading>

        <flux:subheading>
            Órdenes listas para cobro
        </flux:subheading>
    </div>

    {{-- SEARCH --}}
    <flux:input
        wire:model.live="search"
        placeholder="Buscar orden..."
    />

    {{-- TABLE --}}
    <flux:table>

        <flux:table.columns>

            <flux:table.column>Orden</flux:table.column>
            <flux:table.column>Mesa</flux:table.column>
            <flux:table.column>Total</flux:table.column>
            <flux:table.column>Acciones</flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @forelse($this->rows as $orden)

                <flux:table.row>

                    <flux:table.cell>
                        {{ $orden->numero }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $orden->mesa?->numero ?? 'Para llevar' }}
                    </flux:table.cell>

                    <flux:table.cell>
                        Q {{ number_format($orden->total, 2) }}
                    </flux:table.cell>

                    <flux:table.cell class="flex gap-2">

                        {{-- FACTURAR --}}
                        <flux:button
                            variant="primary"
                            wire:click="facturar({{ $orden->id }})"
                        >
                            Facturar
                        </flux:button>

                        {{-- VER DETALLE --}}
                        <flux:button
                            wire:click="verDetalle({{ $orden->id }})"
                        >
                            Ver detalle
                        </flux:button>

                    </flux:table.cell>

                </flux:table.row>

            @empty

                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center">
                        No hay órdenes para facturar
                    </flux:table.cell>
                </flux:table.row>

            @endforelse

        </flux:table.rows>

    </flux:table>

    {{-- DETALLE (OPCIONAL) --}}
    @if($detalleOrden)

        <flux:card class="mt-6">

            <div class="space-y-3">

                <div class="font-bold text-lg">
                    Orden {{ $detalleOrden->numero }}
                </div>

                <div>
                    Mesa: {{ $detalleOrden->mesa?->numero ?? 'Para llevar' }}
                </div>

                <div class="border-t pt-3">

                    @foreach($detalleOrden->items as $item)

                        <div class="flex justify-between">
                            <span>
                                {{ $item->producto?->nombre }}
                                x{{ $item->cantidad }}
                            </span>

                            <span>
                                Q {{ number_format($item->subtotal, 2) }}
                            </span>
                        </div>

                    @endforeach

                </div>

                <div class="font-bold text-right">
                    Total: Q {{ number_format($detalleOrden->total, 2) }}
                </div>

            </div>

        </flux:card>

    @endif

    {{-- PAGINACION --}}
    <div class="mt-4">
        {{ $this->rows->links() }}
    </div>

</div>