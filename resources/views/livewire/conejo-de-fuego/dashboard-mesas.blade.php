<div class="space-y-6">

    <div>

        <flux:heading size="xl">
            Dashboard de Mesas
        </flux:heading>

        <flux:subheading>
            Estado actual del restaurante
        </flux:subheading>

    </div>

    <div
        class="
            grid
            grid-cols-2
            md:grid-cols-4
            lg:grid-cols-6
            gap-4
        "
    >

        @foreach($mesas as $mesa)

            <button
                wire:click="verMesa({{ $mesa->id }})"
                class="
                    rounded-xl
                    border
                    p-6
                    transition
                    hover:scale-105
                    text-center
                "

                @if($mesa->estado === 'ocupada')

                    style="
                        background:#fee2e2;
                    "

                @else

                    style="
                        background:#dcfce7;
                    "

                @endif
            >

                <div class="text-3xl font-bold">

                    {{ $mesa->numero }}

                </div>

                <div class="mt-2">

                    @if($mesa->estado === 'ocupada')

                        <flux:badge color="red">
                            Ocupada
                        </flux:badge>

                    @else

                        <flux:badge color="green">
                            Libre
                        </flux:badge>

                    @endif

                </div>

            </button>

        @endforeach

    </div>

    {{-- DETALLE MESA --}}

    <flux:modal
        name="detalle-mesa-modal"
        class="min-w-[900px]"
    >

        @if($selectedMesa)

            <div class="space-y-5">

                <flux:heading size="lg">

                    Mesa
                    {{ $selectedMesa->numero }}

                </flux:heading>

                @php

                    $ordenesActivas =
                    $selectedMesa->ordenes
                        ->whereNotIn(
                            'estado',
                            [
                                'entregada',
                                'cancelada'
                            ]
                        );

                @endphp

                @forelse(
                    $ordenesActivas
                    as $orden
                )

                    <flux:card>

                        <div class="space-y-4">

                            <div
                                class="
                                    flex
                                    justify-between
                                    items-center
                                "
                            >

                                <div>

                                    <strong>
                                        {{ $orden->numero }}
                                    </strong>

                                </div>

                                <flux:badge>

                                    {{ ucfirst($orden->estado) }}

                                </flux:badge>

                            </div>

                            <flux:table>

                                <flux:table.columns>

                                    <flux:table.column>
                                        Producto
                                    </flux:table.column>

                                    <flux:table.column>
                                        Cantidad
                                    </flux:table.column>

                                    <flux:table.column>
                                        Subtotal
                                    </flux:table.column>

                                </flux:table.columns>

                                <flux:table.rows>

                                    @foreach(
                                        $orden->items
                                        as $item
                                    )

                                        <flux:table.row>

                                            <flux:table.cell>

                                                {{ $item->producto?->nombre }}

                                            </flux:table.cell>

                                            <flux:table.cell>

                                                {{ $item->cantidad }}

                                            </flux:table.cell>

                                            <flux:table.cell>

                                                Q {{ number_format($item->subtotal,2) }}

                                            </flux:table.cell>

                                        </flux:table.row>

                                    @endforeach

                                </flux:table.rows>

                            </flux:table>

                            <div
                                class="
                                    text-right
                                    font-bold
                                "
                            >

                                Total:
                                Q {{ number_format($orden->total,2) }}

                            </div>

                        </div>

                    </flux:card>

                @empty

                    <flux:text>

                        No existen órdenes activas.

                    </flux:text>

                @endforelse

            </div>

        @endif

    </flux:modal>

</div>