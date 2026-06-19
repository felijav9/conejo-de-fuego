<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RecepcionSolicitud extends Component
{
    use DataTable, Interact;

    public array $headers = [
        [ 'index' => 'id', 'label' => 'Expediente #', 'align' => 'center' ],
        [ 'index' => 'solicitud.nombre_completo', 'label' => 'Solicitante' ],
        [ 'index' => 'solicitud.cui', 'label' => 'Dpi' ],
        [ 'index' => 'solicitud.patente_comercio', 'label' => 'Patente' ],
        [ 'index' => 'solicitud.tipo_persona', 'label' => 'Tipo persona' ],
        [ 'index' => 'solicitud.sede.nombre', 'label' => 'Área solicitada' ],
        [ 'index' => 'latestWorkflow.estado.nombre', 'label' => 'Último estado' ],
        [ 'index' => 'solicitud.created_at', 'label' => 'Fecha creación' ],
        [ 'index' => 'actions', 'label' => '' ],
    ];
    public array $expediente = [];
    public ?string $urlDoc = null;
    public array $navItems = [
        [ 'option' => 1, 'label' => 'Datos del solicitante', 'icon' => 'user-circle'],
        [ 'option' => 2, 'label' => 'Espacio solicitado', 'icon' => 'map'],
        [ 'option' => 3, 'label' => 'Documentos subidos', 'icon' => 'document'],
        [ 'option' => 4, 'label' => 'Cambiar estado', 'icon' => 'arrow-path'],
    ];

    #[Computed]
    public function rows() {
        $query = Expediente::filterAdvance($this->headers, [
            'search' => $this->search,
            'sort' => [
                'field' => $this->sortBy, 
                'direction' => $this->sortDirection
            ],
            'filters' => $this->processFilters(),
        ])->whereHas('latestWorkflow',function ($query){
            $query->whereIn('estado_id',[1,2]);
        });
        return $query->paginate($this->per_page);
    }

    public function render() {
        return view('livewire.unidad-convivencia-social.pasos-pedales.recepcion-solicitud');
    }

    public function viewRequest(int $id) {
        try {

            $expediente = Expediente::findOrFail($id);
            $this->reviewingRequest($expediente);
            $this->expediente = $expediente->load([
                'solicitud.sede',
                'solicitud.documentos',
                'latestWorkflow'
            ])->toArray();
            Flux::modal('revision-solicitud')->show();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function previewDoc(string $url) {
        $this->urlDoc = $url;
    }

    public function reviewingRequest(Expediente $expediente) {
        try {

            if($expediente->workflows()->where('estado_id',2)->first()) {
                $this->toastInfo('La solicitud ya está en revisión');
                return;
            }

            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 2
            ]);

            $this->toastSuccess('La solicitud está en revisión.');

        } catch (\Throwable $th) {
            
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function rejectRequest() {

        $this->validate([
            'expediente.latestWorkflow.observacion' => 'required|string|max:1000'
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'observacion' => $this->expediente['latestWorkflow']['observacion'],
                'user_id' => Auth::user()->id,
                'estado_id' => 7
            ]);
            
            $this->toastWarning('Se rechazo la solicitud.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function acceptRequest() {

        $this->validate([
            'expediente.latestWorkflow.observacion' => 'nullable|string|max:1000'
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);
            
            $expediente->workflows()->create([
                'observacion' => $this->expediente['latestWorkflow']['observacion'] ?? null,
                'user_id' => Auth::user()->id,
                'estado_id' => 3
            ]);
            
            $this->toastSuccess('Se acepto solicitud.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function resetData() {
        $this->reset(['expediente','nav_option','urlDoc']);
        Flux::modals()->close();
        $this->resetErrorBag();
    }
}
