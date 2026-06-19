<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>
            <flux:heading size="xl">
                Categorías
            </flux:heading>

            <flux:subheading>
                Administración de categorías del restaurante
            </flux:subheading>
        </div>

        <flux:button
            variant="primary"
            icon="plus"
            wire:click="create"
        >
            Nueva Categoría
        </flux:button>

    </div>

    <div class="flex justify-end">

        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar categoría..."
        />

    </div>

    <flux:table>

        <flux:table.columns>

            <flux:table.column>#</flux:table.column>

            <flux:table.column>
                Nombre
            </flux:table.column>

            <flux:table.column>
                Estado
            </flux:table.column>

            <flux:table.column align="right">
                Acciones
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @forelse($this->rows as $categoria)

                <flux:table.row :key="$categoria->id">

                    <flux:table.cell>
                        {{ $categoria->id }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $categoria->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>

                        @if($categoria->activo)

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
                                wire:click="edit({{ $categoria->id }})"
                            />

                            <flux:button
                                size="sm"
                                icon="trash"
                                variant="danger"
                                wire:click="confirmDelete({{ $categoria->id }})"
                            />

                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @empty

                <flux:table.row>

                    <flux:table.cell colspan="4">
                        No existen registros
                    </flux:table.cell>

                </flux:table.row>

            @endforelse

        </flux:table.rows>

    </flux:table>

    <div>
        {{ $this->rows->links() }}
    </div>

    {{-- Modal Crear / Editar --}}
    <flux:modal name="categoria-modal">

        <div class="space-y-5">

            <flux:heading size="lg">

                {{ $selectedCategoria
                    ? 'Editar Categoría'
                    : 'Nueva Categoría' }}

            </flux:heading>

            <flux:input
                label="Nombre"
                wire:model="nombre"
            />

            @error('nombre')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex justify-end gap-2">

                <flux:modal.close>
                    <flux:button variant="ghost">
                        Cancelar
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    variant="primary"
                    wire:click="save"
                >
                    Guardar
                </flux:button>

            </div>

        </div>

    </flux:modal>

    {{-- Modal Eliminar --}}
    <flux:modal name="delete-categoria-modal">

        <div class="space-y-6">

            <div>

                <flux:heading size="lg">
                    Eliminar categoría
                </flux:heading>

                <flux:text class="mt-2">
                    ¿Está seguro de eliminar esta categoría?
                </flux:text>

                <flux:text variant="subtle">
                    Esta acción no se puede deshacer.
                </flux:text>

            </div>

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