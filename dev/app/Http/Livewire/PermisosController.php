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
        'crearPermission',
        'destroyPermission',
        'asignarPermisos',
    ];


    //*********************************************/
    //
    //    Renderizado y seleccción de vista
    //
    //*********************************************/

    public function mount($tipo = "rol"){
        if ($tipo == "permiso"){
            $this->tipo = "permiso";
        }
        else{
            $this->tipo = "rol";
        }
    }

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

    public function crearPermission($permissionName, $permissionId){
        if($permissionName != "")
        {
            if(is_numeric(substr($permissionName, 0, 1))){
                $permissionName = "_" . $permissionName;
            }

            if($permissionId)
            {
                $this->updatePermission(strval($permissionName), $permissionId);
            }
            else
            {
                $this->savePermission(strval($permissionName));
            }        
        }  
        else{
            $this->emit("msg-err","El nombre del permiso no puede ser vacio");            
        }   

        $this->dispatchBrowserEvent('clearPermissionSelected');
        $this->dispatchBrowserEvent('setCheckboxesEventListener');
        $this->resetInput();
    }


    public function savePermission($permissionName)
    {
        $permission = Permission::where("name",$permissionName)->first();

        if($permission){
            $this->emit('msg-err','El permiso que intenta crear ya existe en el sistema');            
            return;
        }

        Permission::create([
            "name" => $permissionName
        ]);

        $this->emit('msg-ok','El permiso se ha creado correctamente');        
    }

    public function updatePermission($permissionName, $permissionId)
    {        
        $permission = Permission::where("name",$permissionName)->where('id','<>',$permissionId)->first();

        if($permission){
            $this->emit('msg-err','El permiso que intenta crear ya existe en el sistema asociado a otro id');
            return;
        }

        $permission = Permission::where('id', $permissionId)->firstOrFail();
        $permission->name = $permissionName;
        $permission->save();

        $this->emit('msg-ok',"El permiso se ha actualizado");
    }

    public function destroyPermission($permissionId)
    {        
        if($this->tipo == "permiso"){
            Permission::find($permissionId)->delete();      
            $this->emit('msg-ok',"El permiso se ha eliminado");          
            $this->resetInput();           
        }
    }

    public function asignarPermisos($permissions)
    {
        if($this->tipo == "permiso"){
            if($this->roleSelected){
                $rol = Role::findOrFail($this->roleSelected);
                $rol->syncPermissions($permissions);
            }
    
            $this->emit("msg-ok","Los permisos del rol se han actualizado");
        }        
    }
}
