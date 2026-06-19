<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <flux:heading size="xl">
                Mesas
            </flux:heading>

            <flux:subheading>
                Administración de mesas
            </flux:subheading>

        </div>

        <flux:button
            icon="plus"
            wire:click="create"
        >
            Nueva Mesa
        </flux:button>

    </div>

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Buscar mesa..."
    />

    <flux:table>

        <flux:table.columns>

            <flux:table.column>
                Número
            </flux:table.column>

            <flux:table.column>
                Estado
            </flux:table.column>

            <flux:table.column align="right">
                Acciones
            </flux:table.column>

        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->rows as $mesa)

                <flux:table.row>

                    <flux:table.cell>
                        {{ $mesa->numero }}
                    </flux:table.cell>

                    <flux:table.cell>

                        @if($mesa->estado === 'libre')

                            <flux:badge color="green">
                                Libre
                            </flux:badge>

                        @else

                            <flux:badge color="red">
                                Ocupada
                            </flux:badge>

                        @endif

                    </flux:table.cell>

                    <flux:table.cell align="right">

                        <div class="flex gap-2 justify-end">

                            <flux:button
                                size="sm"
                                icon="pencil"
                                variant="ghost"
                                wire:click="edit({{ $mesa->id }})"
                            />

                            <flux:button
                                size="sm"
                                icon="trash"
                                variant="danger"
                                wire:click="confirmDelete({{ $mesa->id }})"
                            />

                        </div>

                    </flux:table.cell>

                </flux:table.row>

            @endforeach

        </flux:table.rows>

    </flux:table>

    {{ $this->rows->links() }}

    {{-- Modal Crear / Editar --}}
    <flux:modal
        name="mesa-modal"
        class="min-w-[500px]"
    >

        <div class="space-y-4">

            <flux:heading size="lg">

                {{ $selectedMesa
                    ? 'Editar Mesa'
                    : 'Nueva Mesa' }}

            </flux:heading>

            <flux:input
                label="Número de mesa"
                wire:model="numero"
            />

            <flux:select
                label="Estado"
                wire:model="estado"
            >

                <option value="libre">
                    Libre
                </option>

                <option value="ocupada">
                    Ocupada
                </option>

            </flux:select>

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

    {{-- Modal Eliminar --}}
    <flux:modal
        name="delete-mesa-modal"
    >

        <div class="space-y-6">

            <flux:heading size="lg">
                Eliminar Mesa
            </flux:heading>

            <flux:text>
                ¿Está seguro de eliminar esta mesa?
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