<div class="space-y-6">

    <div>

        <flux:heading size="xl">
            Nueva Orden
        </flux:heading>

        <flux:subheading>
            Registro de pedidos
        </flux:subheading>

    </div>

    <div class="grid md:grid-cols-2 gap-4">

        <flux:select
            label="Tipo de orden"
            wire:model.live="tipo"
        >
            <option value="mesa">
                Mesa
            </option>

            <option value="llevar">
                Para llevar
            </option>
        </flux:select>

        @if($tipo === 'mesa')

            <flux:select
                label="Mesa"
                wire:model="mesa_id"
            >

                <option value="">
                    Seleccione una mesa
                </option>

                @foreach($mesas as $mesa)

                    <option value="{{ $mesa->id }}">
                        {{ $mesa->numero }}
                    </option>

                @endforeach

            </flux:select>

        @endif

    </div>

    <div class="flex justify-between items-center">

        <flux:heading size="lg">
            Productos
        </flux:heading>

        <flux:button
            icon="plus"
            wire:click="addItem"
        >
            Agregar
        </flux:button>

    </div>

    <div class="space-y-4">

        @foreach($items as $index => $item)

            <div
                class="border rounded-lg p-4 space-y-4"
                wire:key="item-{{ $index }}"
            >

                <div class="grid md:grid-cols-4 gap-4">

                    <flux:select
                        label="Producto"
                        wire:model.live="items.{{ $index }}.producto_id"
                    >

                        <option value="">
                            Seleccione
                        </option>

                        @foreach($productos as $producto)

                            <option value="{{ $producto->id }}">
                                {{ $producto->nombre }}
                                - Q{{ number_format($producto->precio,2) }}
                            </option>

                        @endforeach

                    </flux:select>

                    <flux:input
                        type="number"
                        min="1"
                        label="Cantidad"
                        wire:model.live="items.{{ $index }}.cantidad"
                    />

                    <flux:input
                        readonly
                        label="Precio"
                        wire:model="items.{{ $index }}.precio"
                    />

                    <flux:input
                        readonly
                        label="Subtotal"
                        wire:model="items.{{ $index }}.subtotal"
                    />

                </div>

                <div class="flex gap-3">

                    <div class="flex-1">

                        <flux:input
                            label="Nota"
                            wire:model="items.{{ $index }}.nota"
                        />

                    </div>

                    <div class="flex items-end">

                        <flux:button
                            variant="danger"
                            icon="trash"
                            wire:click="removeItem({{ $index }})"
                        />

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <div
        class="border rounded-xl p-5 flex justify-between items-center"
    >

        <div>

            <div class="text-sm text-zinc-500">
                Total
            </div>

            <div class="text-3xl font-bold">
                Q {{ number_format($total,2) }}
            </div>

        </div>

        <flux:button
            variant="primary"
            wire:click="save"
        >
            Guardar Orden
        </flux:button>

    </div>

</div>