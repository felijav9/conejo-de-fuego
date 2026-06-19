<?php

namespace App\Livewire\Admin;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\Area;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Areas extends Component
{
    use DataTable, Interact;

    public array $headers = [
        [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
        [ 'index' => 'name', 'label' => 'Área' ],
        [ 'index' => 'dependency.name', 'label' => 'Pertenece a' ],
        [ 'index' => 'active', 'label' => 'Estado' ],
        [ 'index' => 'actions', 'label' => '', 'width' => '100px' ],
    ];
    public array $area = [];

    #[Computed]
    public function rows() {
        $query = Area::with(['dependency'])
        ->filterAdvance($this->headers,[
            'search' => $this->search,
            'sort' => [
                'field' => $this->sortBy, 
                'direction' => $this->sortDirection
            ],
            'filters' => $this->processFilters(),
        ]);

        return $query->paginate($this->per_page);
    }

    public function render() {
        $dependencies = Area::orderBy('name')->get(['id','name']);
        return view('livewire.admin.areas',compact('dependencies'));
    }

    public function store() {
        $this->validate([
            'area.name' => 'required|string|max:255',
            'area.area_id' => 'nullable|int|exists:desarrollo-social.areas,id',
        ]);

        try {
            
            Area::create([
                'name' => $this->area['name'],
                'area_id' => $this->area['area_id'] ?? null,
            ]);
    
            $this->toastSuccess('Area creada exitosamente.');
            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Ocurrió un error al crear el área');
        }

    }

    public function edit(int $id) {
        $this->area = Area::findOrFail($id)->toArray();
        Flux::modal('editArea')->show();
    }

    public function update() {
        $this->validate([
            'area.name' => 'required|string|min:3|unique:desarrollo-social.areas,name,' . $this->area['id'],
            'area.area_id' => 'nullable|int|exists:desarrollo-social.areas,id',
        ]);

        try {
            $area = Area::findOrFail($this->area['id']);
            $area->name = $this->area['name'];
            $area->area_id = $this->area['area_id'] ?? null;
            $area->active = $this->area['active'];
    
            if($area->isDirty()){
                $area->save();
                $this->toastSuccess('Area actualizada exitosamente.');
            }
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Ocurrió un error al actualizar el área');
        }

    }

    public function disableItem(int $id) {
        $this->area = Area::findOrFail($id)->toArray();
        Flux::modal('disableArea')->show();
    }

    public function disabled() {
        try {
            $area = Area::findOrFail($this->area['id']);
            $area->active = false;
            $area->save();

            $this->toastSuccess('Area deshabilitada exitosamente.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Ocurrió un error al deshabilitar el área');
        }
    }

    public function delete(int $id) {
        $this->area = Area::findOrFail($id)->toArray();
        Flux::modal('deleteArea')->show();
    }

    public function destroy() {
        try {
            $area = Area::findOrFail($this->area['id']);
            $area->delete();
    
            $this->toastSuccess('Area eliminada exitosamente.');
    
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Ocurrió un error al eliminar el área');
        }
    }

    public function resetData() {
        $this->reset('area');
        $this->resetValidation();
        Flux::modals()->close();
    }
}
