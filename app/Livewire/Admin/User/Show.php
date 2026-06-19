<?php

namespace App\Livewire\Admin\User;

use App\Traits\DataTable;
use App\Traits\Interact;
use App\Models\Area;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Permission;
use App\Models\User;
use App\Models\Zona;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class Show extends Component
{
    use Interact, WithFileUploads, DataTable;

    public array $headers = [
        [ 'index' => 'id', 'label' => '#', 'align' => 'center' ],
        [ 'index' => 'name', 'label' => 'Permiso' ],
        [ 'index' => 'module', 'label' => 'Pertenece a modulo' ],
    ];
    public User $user;
    public array $usuario = [];
    public $departamentos, $zonas, $areas, $roles;
    public ?int $departamento_id = 7;
    public ?string $search_permissions = null;

    public function mount(User $user) {
        $this->user = $user->load(['area','roles','information.domicilio']);
        $this->user->role = $this->user->roles[0]->name ?? null;
        $this->usuario = $this->user->toArray();
        $this->selectedRows = $user->getDirectPermissions()->pluck('id')->toArray();
        $this->departamentos = Departamento::orderBy('nombre')->get();
        $this->zonas = Zona::all();
        $this->areas = Area::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
    }

    #[Computed]
    public function rows() {
        $query = Permission::filterAdvance($this->headers,[
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

        $municipios = Municipio::where('departamento_id',$this->departamento_id)->orderBy('nombre')->get();
        return view('livewire.admin.user.show',compact('municipios'));
    }

    public function updateProfileInformation() {
        $this->validate([
            'usuario.area_id' => 'nullable|exists:desarrollo-social.areas,id',
            'usuario.role' => 'nullable|exists:desarrollo-social.roles,name',

            'usuario.information.nombres' => 'required|string|max:255',
            'usuario.information.apellidos' => 'required|string|max:255',
            'usuario.information.cui' => 'required|digits:13|unique:desarrollo-social.user_information,cui,'.$this->usuario['information']['id'],
            'usuario.information.telefono' => 'required|digits:8',
            'usuario.information.fecha_nacimiento' => 'required|date|date_format:Y-m-d',
            'usuario.information.correo' => 'required|string|email',
            'usuario.information.sexo' => 'required|in:M,F',
            'usuario.information.user_id' => 'required|exists:desarrollo-social.users,id',

            'usuario.information.domicilio.municipio_id' => 'required|exists:desarrollo-social.municipios,id',
            'usuario.information.domicilio.zona_id' => 'nullable|exists:desarrollo-social.zonas,id',
            'usuario.information.domicilio.colonia' => 'required|string|max:255',
            'usuario.information.domicilio.direccion' => 'required|string|max:255',

        ]);

        try {
            
            $this->user->area_id = empty($this->usuario['area_id']) ? null : $this->usuario['area_id'];
            $this->user->save();

            $this->user->syncRoles($this->usuario['role'] ?? []);
    
            $this->user->information->update($this->usuario['information']);
            $this->user->information->domicilio->update($this->usuario['information']['domicilio']);
    
            $this->toastSuccess('Informacion actualizada correctamente.');

        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al actualizar la informacion.'. $th->getMessage());
        }
        
    }

    public function resetPassword() {
        try {
            
            $this->user->password = Hash::make($this->user::DEFAULTPASS);
            $this->user->save();
    
            $this->toastSuccess('Se ha restablecido la contraseña al usuario.');
    
            $this->resetData();

        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al restablecer la contraseña.');
        }
    }

    public function disabledUser() {

        try {
            $this->user->delete();

            $this->toastSuccess('Se ha desactivado al usuario.');

            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al desactivar al usuario.');
        }
    }

    public function uploadPicture() {
        $this->validate([
            'usuario.information.foto' => 'nullable|image|max:2048', // 2MB Max
        ]);

        try {
            if ($this->usuario['information']['foto']) {
                $path = $this->usuario['information']['foto']->store('user-photos');
                $this->user->information->foto = $path;
                $this->user->information->save();
    
                $this->toastSuccess('Foto de perfil actualizada correctamente.');
            }
        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al subir la foto de perfil.');
        }

    }

    public function deletePicture() {
        try {
            Storage::delete($this->user->information->foto);
            $this->user->information->foto = null;
            $this->user->information->save();
    
            $this->toastSuccess('Foto de perfil eliminada correctamente.');
        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al eliminar la foto de perfil.');
        }
    }

    public function syncDirectPermissions() {
        try {
            $this->user->permissions()->sync($this->selectedRows);
            $this->toastSuccess('Permisos asignados correctamente.');
            $this->resetData();
        } catch (\Throwable $th) {
            $this->toastError('Ocurrio un error al asignar los permisos.');
        }
    }

    public function resetData() {
        Flux::modals()->close();
    }
}
