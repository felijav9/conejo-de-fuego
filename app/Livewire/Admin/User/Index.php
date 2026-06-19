<?php

namespace App\Livewire\Admin\User;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\Area;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\User;
use App\Models\Zona;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use DataTable, Interact;

    public array $headers = [
        [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
        [ 'index' => 'cui', 'label' => 'Cui'],
        [ 'index' => 'information.nombre_completo', 'label' => 'Usuario' ],
        [ 'index' => 'information.sexo', 'label' => 'Sexo' ],
        [ 'index' => 'information.fecha_nacimiento', 'label' => 'Fecha nacimiento'],
        [ 'index' => 'area.name', 'label' => 'Area',],
        [ 'index' => 'role_name', 'label' => 'Role', 'exclude' => true ],
        [ 'index' => 'user_type', 'label' => 'Tipo', ],
        [ 'index' => 'deleted_at', 'label' => 'Active', 'align' => 'center' ],
        [ 'index' => 'actions', 'label' => '']
    ];

    public ?int $usuario_id = null;
    public $departamentos, $zonas, $areas, $roles;   
    public ?int $departamento_id = 7;
    public array $user = [];

    #[Computed]
    public function rows() {
        $query = User::with(['information','area','role'])
        ->withTrashed()
        ->filterAdvance($this->headers, [
            'search' => $this->search,
            'sort' => [
                'field' => $this->sortBy, 
                'direction' => $this->sortDirection
            ],
            'filters' => $this->processFilters(),
        ]);

         return $query->paginate($this->per_page ?? 10);
    }

    public function mount() {
        $this->departamentos = Departamento::orderBy('nombre')->get();
        $this->zonas = Zona::all();
        $this->areas = Area::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
    }

    public function render() {
        $municipios = Municipio::where('departamento_id',$this->departamento_id)->orderBy('nombre')->get();
        return view('livewire.admin.user.index',compact('municipios'));
    }

    public function store() {
        $this->validate([
            'user.area_id' => 'nullable|exists:desarrollo-social.areas,id',
            'user.role' => 'nullable|exists:desarrollo-social.roles,name',

            'user.information.nombres' => 'required|string|max:255',
            'user.information.apellidos' => 'required|string|max:255',
            'user.information.cui' => 'required|digits:13|unique:desarrollo-social.user_information,cui',
            'user.information.telefono' => 'required|digits:8',
            'user.information.fecha_nacimiento' => 'required|date|date_format:Y-m-d',
            'user.information.correo' => 'required|string|email',
            'user.information.sexo' => 'required|in:M,F',

            'user.information.domicilio.municipio_id' => 'required|exists:desarrollo-social.municipios,id',
            'user.information.domicilio.zona_id' => 'nullable|exists:desarrollo-social.zonas,id',
            'user.information.domicilio.colonia' => 'nullable|string|max:255',
            'user.information.domicilio.direccion' => 'required|string|max:255',

        ]);

        try {

            DB::transaction(function () {
                
                $user = User::create([
                    'cui' => $this->user['information']['cui'],
                    'email' => mb_strtolower($this->user['information']['correo']),
                    'password' => Hash::make(User::DEFAULTPASS),
                    'area_id' => $this->user['area_id'] ?? null,
                ]);
    

    
                $user->information()->create([
                    'nombres' => ucwords(mb_strtolower($this->user['information']['nombres'])),
                    'apellidos' => ucwords(mb_strtolower($this->user['information']['apellidos'])),
                    'cui' => $this->user['information']['cui'],
                    'telefono' => $this->user['information']['telefono'],
                    'fecha_nacimiento' => $this->user['information']['fecha_nacimiento'],
                    'correo' => mb_strtolower($this->user['information']['correo']),
                    'sexo' => $this->user['information']['sexo'],
                ]);

    
                $user->information->domicilio()->create([
                    'municipio_id' => $this->user['information']['domicilio']['municipio_id'],
                    'zona_id' => $this->user['information']['domicilio']['zona_id'] ?? null,
                    'colonia' => ucwords(mb_strtolower($this->user['information']['domicilio']['colonia'])) ?? null,
                    'direccion' => ucwords(mb_strtolower($this->user['information']['domicilio']['direccion'])),
                ]);
    
                if(isset($this->user['role'])) {
                    $user->syncRoles($this->user['role']);
                }

                $this->toastSuccess('Usuario creado correctamente.');

                $this->resetData();

            });
            

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->toastError('Error al crear al usuario.'.$th->getMessage());
        }
    }

    public function userRestore(int $id) {
        $this->usuario_id = $id;
        Flux::modal('restaurar-usuario')->show();
    }

    public function restore() {
        $user = User::withTrashed()->findOrFail($this->usuario_id);
        $user->restore();

        
        $this->toastSuccess(
            'Usuario restaurado correctamente.',
        );

        Flux::modals()->close();
    }

    public function resetData() {
        $this->reset(['user']);
        Flux::modals()->close();
    }

}

