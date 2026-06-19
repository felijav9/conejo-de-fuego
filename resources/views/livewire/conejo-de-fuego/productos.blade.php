<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <flux:heading size="xl">
                Productos
            </flux:heading>

            <flux:subheading>
                Administración del menú
            </flux:subheading>

        </div>

        <flux:button
            icon="plus"
            wire:click="create"
        >
            Nuevo Producto
        </flux:button>

    </div>

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Buscar producto..."
    />

    <flux:table>

        <flux:table.columns>

            <flux:table.column>
                Producto
            </flux:table.column>

            <flux:table.column>
                Categoría
            </flux:table.column>

            <flux:table.column>
                Área
            </flux:table.column>

            <flux:table.column>
                Precio
            </flux:table.column>

            <flux:table.column>
                Estado
            </flux:table.column>

            <flux:table.column align="right">
                Acciones
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->rows as $producto)

                <flux:table.row>

                    <flux:table.cell>
                        {{ $producto->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $producto->categoria?->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $producto->area }}
                    </flux:table.cell>

                    <flux:table.cell>
                        Q {{ number_format($producto->precio,2) }}
                    </flux:table.cell>

                    <flux:table.cell>

                        @if($producto->activo)

                            <flux:badge color="green">
                                Activo
                            </flux:badge>

                        @else

                            <flux:badge color="red">
                                Inactivo
                            </flux:badge>

                        @endif

                    </flux:table.cell>

                    <flux:table.cell align="right">

                        <div class="flex gap-2 justify-end">

                            <flux:button
                                size="sm"
                                icon="pencil"
                                variant="ghost"
                                wire:click="edit({{ $producto->id }})"
                            />

                            <flux:button
                                size="sm"
                                icon="trash"
                                variant="danger"
                                wire:click="confirmDelete({{ $producto->id }})"
                            />

                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @endforeach

        </flux:table.rows>

    </flux:table>

    {{ $this->rows->links() }}

    <flux:modal name="producto-modal" class="min-w-[700px]">

        <div class="space-y-4">

            <flux:heading size="lg">

                {{ $selectedProducto
                    ? 'Editar Producto'
                    : 'Nuevo Producto' }}

            </flux:heading>

            <flux:input
                label="Nombre"
                wire:model="nombre"
            />

            <flux:textarea
                label="Descripción"
                wire:model="descripcion"
            />

            <div class="grid grid-cols-2 gap-4">

                <flux:select
                    label="Categoría"
                    wire:model="categoria_id"
                >

                    <option value="">
                        Seleccione
                    </option>

                    @foreach($categorias as $categoria)

                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </option>

                    @endforeach

                </flux:select>

                <flux:select
                    label="Área de impresión"
                    wire:model="area"
                >

                    <option value="Cocina">
                        Cocina
                    </option>

                    <option value="Bebidas">
                        Bebidas
                    </option>

                </flux:select>

            </div>

            <div class="grid grid-cols-2 gap-4">

                <flux:input
                    type="number"
                    step="0.01"
                    label="Precio"
                    wire:model="precio"
                />

                <flux:input
                    type="file"
                    label="Imagen"
                    wire:model="imagen"
                />

            </div>

            <flux:checkbox
                wire:model="activo"
                label="Producto activo"
            />

            <div class="flex justify-end gap-2">

                <flux:modal.close>

                    <flux:button variant="ghost">
                        Cancelar
                    </flux:button>

                </flux:modal.close>

                <flux:button
                    wire:click="save"
                >
                    Guardar
                </flux:button>

            </div>

        </div>

    </flux:modal>

    <flux:modal name="delete-producto-modal">

        <div class="space-y-6">

            <flux:heading size="lg">
                Eliminar producto
            </flux:heading>

            <flux:text>
                ¿Está seguro de eliminar este producto?
            </flux:text>

            <div class="flex justify-end gap-2">

                <flux:modal.close>

                    <flux:button variant="ghost">
                        Cancelar
                    </flux:button>

                </flux:modal.close>

                <flux:button
                    variant="danger"
                    wire:click="delete"
                >
                    Eliminar
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>