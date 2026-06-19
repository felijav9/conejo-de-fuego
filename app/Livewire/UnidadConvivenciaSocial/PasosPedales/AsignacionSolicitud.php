<?php

namespace App\Livewire\UnidadConvivenciaSocial\PasosPedales;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\UnidadConvivenciaSocial\PasosPedales\AreaSede;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Expediente;
use App\Models\UnidadConvivenciaSocial\PasosPedales\Sede;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AsignacionSolicitud extends Component
{
    use DataTable, Interact;

    public array $headers = [
        [ 'index' => 'id', 'label' => 'Expediente #', 'align' => 'center'  ],
        [ 'index' => 'solicitud.nombre_completo', 'label' => 'Solicitante' ],
        [ 'index' => 'solicitud.cui', 'label' => 'Dpi' ],
        [ 'index' => 'solicitud.patente_comercio', 'label' => 'Patente' ],
        [ 'index' => 'solicitud.tipo_persona', 'label' => 'Tipo persona' ],
        [ 'index' => 'solicitud.sede.nombre', 'label' => 'Área solicitada' ],
        [ 'index' => 'latestWorkflow.estado.nombre', 'label' => 'Último estado' ],
        [ 'index' => 'actions', 'label' => '' ],
    ];
    public array $expediente = [];
    public ?string $urlDoc = null;
    public $areas_sede;
    public string $urlImagen = '';
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
                $query->whereIn('estado_id',[3,4,8]);
            });
        return $query->paginate($this->per_page);
    }

    
    public function render() {
        $sedes = Sede::orderBy('nombre')->get();
        return view('livewire.unidad-convivencia-social.pasos-pedales.asignacion-solicitud', compact('sedes'));
    }

    public function getAreasSede(int $sede_id) {
        $this->areas_sede = AreaSede::where('sede_id', $sede_id)->orderBy('nombre')->get();
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

    public function previewImage(string $url) {
        $this->urlImagen = $url;
    }

    public function reviewingRequest(Expediente $expediente) {
        try {

            if($expediente->workflows()->where('estado_id',4)->first()) {
                $this->toastInfo('El expediente ya está en revisión');
                return;
            }

            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 4
            ]);

            $this->toastSuccess('Verificando espacio disponible.');

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

    public function assignSpace() {

        $this->validate([
            'expediente.area_sede_id' => 'required|integer|exists:unidad-convivencia-social.areas_sede,id',
            'expediente.descripcion' => 'nullable|string|max:1000',
        ]);

        try {

            $expediente = Expediente::findOrFail($this->expediente['id']);

            $expediente->area_sede_id = $this->expediente['area_sede_id'];
            $expediente->descripcion = $this->expediente['descripcion'];
            $expediente->save();
            
            $expediente->workflows()->create([
                'user_id' => Auth::user()->id,
                'estado_id' => 5
            ]);
            
            $this->toastSuccess('Se asigno espacio exitosamente.');

            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Error : '.$th->getMessage());
        }
    }

    public function resetData() {
        $this->reset(['expediente','nav_option','urlDoc','urlImagen','areas_sede']);
        Flux::modals()->close();
        $this->resetErrorBag();
    }
}
