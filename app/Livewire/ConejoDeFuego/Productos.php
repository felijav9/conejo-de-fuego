<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Categoria;
use App\Models\ConejoDeFuego\Producto;
use App\Traits\DataTable;
use App\Traits\Interact;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Productos extends Component
{
    use DataTable, Interact, WithFileUploads, WithPagination;

    public ?Producto $selectedProducto = null;

    public ?int $deleteId = null;

    public string $nombre = '';

    public string $descripcion = '';

    public $precio = '';

    public string $area = 'Cocina';

    public bool $activo = true;

    public $imagen = null;

    public $categoria_id = '';

    public function mount()
    {
        $this->per_page = 10;
    }

    public function getRowsProperty()
    {
        return Producto::with('categoria')
            ->when($this->search, function ($query) {

                $search = $this->search;

                $query->where(function ($q) use ($search) {

                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('area', 'like', "%{$search}%")
                        ->orWhere('precio', 'like', "%{$search}%")
                        ->orWhere('activo', 'like', "%{$search}%")

                      // búsqueda por categoría (relación)
                        ->orWhereHas('categoria', function ($cat) use ($search) {
                            $cat->where('nombre', 'like', "%{$search}%");
                        });

                });

            })
            ->orderBy('nombre')
            ->paginate($this->per_page);
    }

    public function create()
    {
        $this->resetForm();
        Flux::modal('producto-modal')->show();
    }

    public function edit($id)
    {
        $this->selectedProducto = Producto::findOrFail($id);

        $this->nombre = $this->selectedProducto->nombre;
        $this->descripcion = $this->selectedProducto->descripcion;
        $this->precio = $this->selectedProducto->precio;
        $this->area = $this->selectedProducto->area;
        $this->categoria_id = $this->selectedProducto->categoria_id;
        $this->activo = $this->selectedProducto->activo;

        Flux::modal('producto-modal')->show();
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'nullable|max:1000',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required',
            'area' => 'required',
        ]);

        $path = null;

        if ($this->imagen) {
            $path = $this->imagen->store('productos', 'public');
        }

        if ($this->selectedProducto) {

            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'categoria_id' => $this->categoria_id,
                'area' => $this->area,
                'activo' => $this->activo,
            ];

            if ($path) {
                $data['imagen'] = $path;
            }

            $this->selectedProducto->update($data);

            $this->toastSuccess('Producto actualizado correctamente');

        } else {

            Producto::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'imagen' => $path,
                'categoria_id' => $this->categoria_id,
                'area' => $this->area,
                'activo' => true,
            ]);

            $this->toastSuccess('Producto creado correctamente');
        }

        $this->resetForm();
        Flux::modal('producto-modal')->close();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        Flux::modal('delete-producto-modal')->show();
    }

    public function delete()
    {
        Producto::findOrFail($this->deleteId)->delete();

        $this->deleteId = null;

        Flux::modal('delete-producto-modal')->close();

        $this->toastSuccess('Producto eliminado correctamente');
    }

    public function resetForm()
    {
        $this->reset([
            'selectedProducto',
            'nombre',
            'descripcion',
            'precio',
            'imagen',
            'categoria_id',
            'area',
            'activo',
        ]);

        $this->activo = true;
        $this->area = 'Cocina';
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.productos', [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }
}
