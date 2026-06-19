<?php

namespace App\Livewire\ConejoDeFuego;

use App\Models\ConejoDeFuego\Mesa;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Mesas extends Component
{
    use WithPagination;

    public string $search = '';

    public ?Mesa $selectedMesa = null;
    public ?int $deleteId = null;

    public string $numero = '';
    public string $estado = 'libre';

    public int $per_page = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        return Mesa::query()
            ->when($this->search, function ($query) {

                $search = $this->search;

                $query->where(function ($q) use ($search) {

                    $q->where('numero', 'like', "%{$search}%")
                        ->orWhere('estado', 'like', "%{$search}%");

                });

            })
            ->orderBy('numero')
            ->paginate($this->per_page);
    }

    public function create()
    {
        $this->resetForm();

        Flux::modal('mesa-modal')->show();
    }

    public function edit($id)
    {
        $this->selectedMesa = Mesa::findOrFail($id);

        $this->numero = $this->selectedMesa->numero;
        $this->estado = $this->selectedMesa->estado;

        Flux::modal('mesa-modal')->show();
    }

    public function save()
    {
        $this->validate([
            'numero' => 'required|max:50',
            'estado' => 'required|in:libre,ocupada',
        ]);

        if ($this->selectedMesa) {

            $this->selectedMesa->update([
                'numero' => $this->numero,
                'estado' => $this->estado,
            ]);

        } else {

            Mesa::create([
                'numero' => $this->numero,
                'estado' => $this->estado,
            ]);

        }

        $this->resetForm();

        Flux::modal('mesa-modal')->close();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;

        Flux::modal('delete-mesa-modal')->show();
    }

    public function delete()
    {
        Mesa::findOrFail($this->deleteId)->delete();

        $this->deleteId = null;

        Flux::modal('delete-mesa-modal')->close();
    }

    public function resetForm()
    {
        $this->reset([
            'selectedMesa',
            'numero',
            'estado',
        ]);

        $this->estado = 'libre';
    }

    public function render()
    {
        return view('livewire.conejo-de-fuego.mesas');
    }
}