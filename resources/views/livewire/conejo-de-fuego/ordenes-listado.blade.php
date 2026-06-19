<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <flux:heading size="xl">
                Órdenes
            </flux:heading>

            <flux:subheading>
                Consulta de órdenes registradas
            </flux:subheading>

        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-4">

        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar orden..."
        />

        <flux:select
            wire:model.live="estado"
        >

            <option value="">
                Todos los estados
            </option>

            <option value="pendiente">
                Pendiente
            </option>

            <option value="preparando">
                Preparando
            </option>

            <option value="lista">
                Lista
            </option>

            <option value="entregada">
                Entregada
            </option>

            <option value="cancelada">
                Cancelada
            </option>

        </flux:select>

    </div>

    <flux:table>

        <flux:table.columns>

            <flux:table.column>
                Orden
            </flux:table.column>

            <flux:table.column>
                Mesa
            </flux:table.column>

            <flux:table.column>
                Tipo
            </flux:table.column>

            <flux:table.column>
                Estado
            </flux:table.column>

            <flux:table.column>
                Total
            </flux:table.column>

            <flux:table.column>
                Fecha
            </flux:table.column>

            <flux:table.column align="right">
                Acciones
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @forelse($this->rows as $orden)

                <flux:table.row>

                    <flux:table.cell>
                        {{ $orden->numero }}
                    </flux:table.cell>

                    <flux:table.cell>

                        {{ $orden->mesa?->numero ?? 'Llevar' }}

                    </flux:table.cell>

                    <flux:table.cell>
                        {{ ucfirst($orden->tipo) }}
                    </flux:table.cell>

                    <flux:table.cell>

                        @switch($orden->estado)

                            @case('pendiente')
                                <flux:badge color="yellow">
                                    Pendiente
                                </flux:badge>
                            @break

                            @case('preparando')
                                <flux:badge color="blue">
                                    Preparando
                                </flux:badge>
                            @break

                            @case('lista')
                                <flux:badge color="green">
                                    Lista
                                </flux:badge>
                            @break

                            @case('entregada')
                                <flux:badge color="green">
                                    Entregada
                                </flux:badge>
                            @break

                            @case('cancelada')
                                <flux:badge color="red">
                                    Cancelada
                                </flux:badge>
                            @break

                        @endswitch

                    </flux:table.cell>

                    <flux:table.cell>
                        Q {{ number_format($orden->total,2) }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $orden->created_at->format('d/m/Y H:i') }}
                    </flux:table.cell>

                    <flux:table.cell align="right">

                        <div class="flex justify-end gap-2">

                            <flux:button
                                size="sm"
                                icon="eye"
                                variant="ghost"
                                wire:click="showDetail({{ $orden->id }})"
                            />

                            @if(
                                $orden->estado !== 'cancelada'
                                &&
                                $orden->estado !== 'entregada'
                            )

                                <flux:button
                                    size="sm"
                                    icon="x-mark"
                                    variant="danger"
                                    wire:click="confirmCancel({{ $orden->id }})"
                                />

                            @endif

                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @empty

                <flux:table.row>

                    <flux:table.cell colspan="7">
                        No existen órdenes
                    </flux:table.cell>

                </flux:table.row>

            @endforelse

        </flux:table.rows>

    </flux:table>

    {{ $this->rows->links() }}

    {{-- DETALLE --}}

    <flux:modal
        name="detalle-orden-modal"
        class="min-w-[900px]"
    >

        @if($selectedOrden)

            <div class="space-y-5">

                <flux:heading size="lg">

                    {{ $selectedOrden->numero }}

                </flux:heading>

                <div class="grid md:grid-cols-3 gap-4">

                    <div>
                        Mesa:
                        {{ $selectedOrden->mesa?->numero ?? 'Llevar' }}
                    </div>

                    <div>
                        Tipo:
                        {{ ucfirst($selectedOrden->tipo) }}
                    </div>

                    <div>
                        Total:
                        Q {{ number_format($selectedOrden->total,2) }}
                    </div>

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
                            Precio
                        </flux:table.column>

                        <flux:table.column>
                            Subtotal
                        </flux:table.column>

                    </flux:table.columns>

                    <flux:table.rows>

                        @foreach($selectedOrden->items as $item)

                            <flux:table.row>

                                <flux:table.cell>
                                    {{ $item->producto->nombre }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ $item->cantidad }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    Q {{ number_format($item->precio_unitario,2) }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    Q {{ number_format($item->subtotal,2) }}
                                </flux:table.cell>

                            </flux:table.row>

                        @endforeach

                    </flux:table.rows>

                </flux:table>

            </div>

        @endif

    </flux:modal>

    {{-- CANCELAR --}}

    <flux:modal name="cancelar-orden-modal">

        <div class="space-y-6">

            <flux:heading size="lg">
                Cancelar orden
            </flux:heading>

            <flux:text>
                ¿Desea cancelar esta orden?
            </flux:text>

            <div class="flex justify-end gap-2">

                <flux:modal.close>

                    <flux:button variant="ghost">
                        No
                    </flux:button>

                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="cancel"
                >
                    Sí, cancelar
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>