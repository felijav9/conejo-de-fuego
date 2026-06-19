<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <flux:heading size="xl">
                Cocina
            </flux:heading>

            <flux:subheading>
                Gestión de pedidos
            </flux:subheading>

        </div>

    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar orden..." />

    <div class="grid gap-4">

        @forelse($this->rows as $orden)
            <flux:card>

                <div class="space-y-4">

                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">

                        <div>

                            <div class="font-bold text-lg">

                                {{ $orden->numero }}

                            </div>

                            <div class="text-sm text-zinc-500">

                                @if ($orden->tipo === 'mesa')
                                    Mesa:
                                    {{ $orden->mesa?->numero }}
                                @else
                                    Para llevar
                                @endif

                            </div>

                        </div>

                        <div>

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
                            @endswitch

                        </div>

                    </div>

                    <div>

                        <flux:table>

                            <flux:table.columns>

                                <flux:table.column>
                                    Producto
                                </flux:table.column>

                                <flux:table.column>
                                    Cantidad
                                </flux:table.column>

                                <flux:table.column>
                                    Nota
                                </flux:table.column>

                            </flux:table.columns>

                            <flux:table.rows>

                                @foreach($orden->items as $item)

                                    @if($item->producto?->area === 'cocina')

                                        <flux:table.row>

                                            <flux:table.cell>
                                                {{ $item->producto?->nombre }}
                                            </flux:table.cell>

                                            <flux:table.cell>
                                                {{ $item->cantidad }}
                                            </flux:table.cell>

                                            <flux:table.cell>
                                                {{ $item->nota ?: '-' }}
                                            </flux:table.cell>

                                        </flux:table.row>

                                    @endif

                                @endforeach

                            </flux:table.rows>
                        </flux:table>

                    </div>

                    <div class="flex justify-between items-center">

                        <div class="font-bold">

                            Total:
                            Q {{ number_format($orden->total, 2) }}

                        </div>

                        <div class="flex gap-2">

                            <flux:button variant="danger" wire:click="cancelar({{ $orden->id }})">
                                Cancelar
                            </flux:button>

                            <flux:button wire:click="cambiarEstado({{ $orden->id }})">

                                @if ($orden->estado === 'pendiente')
                                    Iniciar preparación
                                @elseif($orden->estado === 'preparando')
                                    Marcar lista
                                @elseif($orden->estado === 'lista')
                                    Entregar
                                @endif

                            </flux:button>

                        </div>

                    </div>

                </div>

            </flux:card>

            @empty

                <flux:card>

                    <div class="text-center py-10">

                        No existen órdenes pendientes.

                    </div>

                </flux:card>
            @endforelse

        </div>

        {{ $this->rows->links() }}

    </div>
