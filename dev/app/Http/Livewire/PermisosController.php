<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisosController extends Component
{
    public $tipo;
    public $userSelected = "", $roleSelected = "";

    protected $listeners = [
        'crearRole',
        'destroyRole',
        'asignarRoles',
    ];


    /********************************* */
    // public $foo;
 
    // public function boot()
    // {
    //     $this->emit('msg-ok',"Boot");
    // }
 
    // public function mount()
    // {
    //     $this->emit('msg-ok',"Mount");
    // }
 
    // public function hydrateFoo($value)
    // {
    //     //
    // }
 
    // public function dehydrateFoo($value)
    // {
    //     //
    // }
 
    // public function hydrate()
    // {
    //     $this->emit('msg-ok',"Hydrate");
    // }
 
    // public function dehydrate()
    // {
    //     $this->emit('msg-ok',"Dehydrate");
    // }
 
    // public function updating($name, $value)
    // {
    //     $this->emit('msg-ok',"Updating ".$name." a valor ".$value);
    // }
 
    // public function updated($name, $value)
    // {
    //     $this->emit('msg-ok',"Updated ".$name." a valor ".$value);
    // }
 
    // public function updatingFoo($value)
    // {
    //     //
    // }
 
    // public function updatedFoo($value)
    // {
    //     //
    // }
 
    // public function updatingFooBar($value)
    // {
    //     //
    // }
 
    // public function updatedFooBar($value)
    // {
    //     //
    // }




    //*********************************************/
    //
    //    Renderizado y seleccción de vista
    //
    //*********************************************/


    public function render()
    {
        switch($this->tipo){
            case "rol":
                return $this->renderRoles();
                break;
            case "permiso":
                return $this->renderPermisos();
                break;
        }
        
    }

    private function renderRoles(){
        // Obtenemos todos los roles añadiendo una columna extra con nombre checked y valor 0
        $roles = Role::select('*', DB::raw("0 as checked"))->get();
        $numRolesAsignados = 0;


        // Si hay un usuario seleccionado, recorremos todos los roles y si el usuario lo tiene actualizamos la columna checked y un contador
        if($this->userSelected != "" && $this->userSelected != "0")
        {
            foreach ($roles as $r) 
            {
                $user = User::findOrFail($this->userSelected);
                if($user->hasRole($r->name))
                {
                    $r->checked = 1;
                    $numRolesAsignados += 1;
                }
            }
        }

        return view('livewire.permisos.roles',[
            'roles' => $roles,
            'numRolesAsignados' => $numRolesAsignados,
            'usuarios' => User::select('id','name')->get()
        ]);
    }

    private function renderPermisos(){
        // Obtenemos todos los permisos añadiendo una columna extra con nombre checked y valor 0
        $permisos = Permission::select('*', DB::raw("0 as checked"))->get();
        $numPermisosAsignados = 0;

        // Si hay un rol seleccionado, recorremos todos los permisos y si el rol lo tiene actualizamos la columna checked y un contador
        if($this->roleSelected != "" && $this->roleSelected != "0")
        {
            foreach($permisos as $p)
            {
                $role = Role::findOrFail($this->roleSelected);
                if($role->hasPermissionTo($p->name))
                {
                    $p->checked = 1;
                    $numPermisosAsignados += 1;
                }
            }
        }

        return view('livewire.permisos.permisos',[
            'roles' => Role::all(),
            'permisos' => $permisos,
            'numPermisosAsignados' => $numPermisosAsignados,
        ]);
    }

    public function resetInput()
    {        
        $this->emit('clearRoleSelected');
        $this->userSelected = "";
        $this->roleSelected = "";        
    }
    
    
    
    
    
    
    //*********************************************/
    //
    // FUNCIONES PARA EL MANEJO DE ROLES
    //
    //*********************************************/

    public function crearRole($roleName, $roleId)
    {
        if($roleName != "")
        {
            if(is_numeric(substr($roleName, 0, 1))){
                $roleName = "_" . $roleName;
            }

            if($roleId)
            {
                $this->updateRole(strval($roleName), $roleId);
            }
            else
            {
                $this->saveRole(strval($roleName));
            }        
        }  
        else{
            $this->emit("msg-err","El nombre del rol no puede ser vacio");            
        }   

        $this->dispatchBrowserEvent('clearRoleSelected');
    }

    public function saveRole($roleName)
    {
        $role = Role::where("name",$roleName)->first();

        if($role){
            $this->emit('msg-err','El rol que intenta crear ya existe en el sistema');            
            return;
        }

        Role::create([
            "name" => $roleName
        ]);

        $this->emit('msg-ok','El rol se ha creado correctamente');        
    }

    public function updateRole($roleName, $roleId)
    {        
        $role = Role::where("name",$roleName)->where('id','<>',$roleId)->first();

        if($role){
            $this->emit('msg-err','El rol que intenta crear ya existe en el sistema asociado a otro id');
            return;
        }

        $role = Role::where('id', $roleId)->firstOrFail();
        $role->name = $roleName;
        $role->save();

        $this->emit('msg-ok',"El rol se ha actualizado");
    }

    public function destroyRole($roleId)
    {        
        if($this->tipo == "rol"){
            Role::find($roleId)->delete();      
            $this->emit('msg-ok',"El rol se ha eliminado");          
            $this->resetInput();           
        }
    }

    public function asignarRoles($roles)
    {
        if($this->tipo == "rol"){
            if($this->userSelected){
                $user = User::findOrFail($this->userSelected);
    
                $user->syncRoles($roles);
            }
    
            $this->emit("msg-ok","Los roles de usuario se han actualizado");
        }        
    }
    
    
    
    
    
    
    //*********************************************/
    //
    // FUNCIONES PARA EL MANEJO DE PERMISOS
    //
    //*********************************************/

    
}
