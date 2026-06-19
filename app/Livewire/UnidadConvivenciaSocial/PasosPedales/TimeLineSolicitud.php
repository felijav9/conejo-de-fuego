<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TimeLineSolicitud extends Component
{
    use DataTable, Interact;

    public array $headers = [
        [ 'index' => 'id', 'label' => 'Expediente #', 'align' => 'center'  ],
        [ 'index' => 'solicitud.nombre_completo', 'label' => 'Solicitante' ],
        [ 'index' => 'solicitud.cui', 'label' => 'Dpi' ],
        [ 'index' => 'solicitud.patente_comercio', 'label' => 'Patente' ],
        [ 'index' => 'solicitud.tipo_persona', 'label' => 'Tipo persona' ],
        [ 'index' => 'solicitud.sede.nombre', 'label' => 'Área solicitada' ],
        [ 'index' => 'latestWorkflow.estado.nombre', 'label' => ' Último estado' ],
        [ 'index' => 'actions', 'label' => '' ],
    ];
    public ?Expediente $expediente = null;

    #[Computed]
    public function rows() {
        $query = Expediente::filterAdvance($this->headers, [
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
        return view('livewire.unidad-convivencia-social.pasos-pedales.time-line-solicitud');
    }

    public function viewTimeLineRequest(int $id) {
        $expediente = Expediente::findOrFail($id);
        $this->expediente = $expediente->load([
            'solicitud.sede',
            'workflows.estado',
            'workflows.user.information',
            'area_sede'
        ]);
        
        Flux::modal('time-line')->show();
    }

    public function resetData () {
        $this->reset('expediente');
        Flux::modals()->close();
    }

}