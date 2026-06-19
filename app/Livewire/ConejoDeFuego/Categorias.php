<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Categoria;
use App\Traits\DataTable;
use App\Traits\Interact;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Categorias extends Component
{
    use DataTable, Interact, WithPagination;

    public ?Categoria $selectedCategoria = null;

    public ?int $deleteId = null;

    public string $nombre = '';

    public function mount()
    {
        $this->per_page = 10;
    }

    public array $headers = [
        ['index' => 'id', 'label' => '#'],
        ['index' => 'nombre', 'label' => 'Nombre'],
        ['index' => 'activo', 'label' => 'Estado'],
        ['index' => 'actions', 'label' => ''],
    ];

    #[Computed]
    public function rows()
    {
        return Categoria::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', "%{$this->search}%");
            })
            ->orderBy('nombre')
            ->paginate($this->per_page);
    }

    public function create()
    {
        $this->resetForm();

        Flux::modal('categoria-modal')->show();
    }

    public function edit($id)
    {
        $this->selectedCategoria = Categoria::findOrFail($id);

        $this->nombre = $this->selectedCategoria->nombre;

        Flux::modal('categoria-modal')->show();
    }

    public function save()
    {
        $this->validate([
            'nombre' => ['required', 'max:255']
        ]);

        if ($this->selectedCategoria) {

            $this->selectedCategoria->update([
                'nombre' => $this->nombre,
            ]);

            $this->toastSuccess(
                'Categoría actualizada correctamente'
            );

        } else {

            Categoria::create([
                'nombre' => $this->nombre,
                'activo' => true,
            ]);

            $this->toastSuccess(
                'Categoría creada correctamente'
            );
        }

        $this->resetForm();

        Flux::modal('categoria-modal')->close();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;

        Flux::modal('delete-categoria-modal')->show();
    }

    public function delete()
    {
        Categoria::findOrFail($this->deleteId)->delete();

        $this->deleteId = null;

        Flux::modal('delete-categoria-modal')->close();

        $this->toastSuccess(
            'Categoría eliminada correctamente'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'selectedCategoria',
            'nombre',
        ]);
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.categorias');
    }
}