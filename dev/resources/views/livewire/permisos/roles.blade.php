<div class="tab-panel tab-role">
    
    <div class="tab-role__roles my-6">
        <div class="tab-role__usuarios my-6">
            <h2 class="tab-role__titulo text-xl font-bold">Asignación de roles</h2>

            <select wire:model="userSelected" id="tab-role__selectUsuarios">
                <option value="0" selected>Selecciona usuario</option>
                
                @foreach ($usuarios as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>    
                @endforeach
                
            </select>

            <button 
                class="boton boton--azul" 
                id="tab-role__btnAsignarRoles"                 
                @if ( !$this->userSelected ) disabled @endif
            >
                Asignar Roles
            </button>
        </div>

        <h2 class="tab-role__titulo text-xl font-bold">Listado de Roles</h2>
        <table id="tab-role__tablaRoles" class="tabla-role">
            <thead>
                <th>Descripcion</th>
                <th>Usuarios</th>
                <th>Acciones</th>
                <th>
                    <label>
                        <input 
                            type="checkbox" 
                            id="tab-role__checkboxMaster" 
                            data-id="all" 
                            class="disabled:bg-gray-300"
                            @if ( !$this->userSelected ) disabled @endif
                            @if ( $numRolesAsignados == $roles->count() ) checked @endif
                            > 
                        Todos
                    </label>
                </th>
            </thead>
            <tbody>
            @foreach($roles as $r)
                <tr>
                    <td> {{ $r->name }}</td>
                    <td> {{ \App\Models\User::role($r->name)->count() }} </td>
                    <td>
                        <button class="boton boton--gris" onclick="showRole('{{$r}}')">Editar</button>

                        @if (\App\Models\User::role($r->name)->count()<1)
                            <button class="boton boton--rojo" onclick="confirmar({{ $r->id }}, 'destroyRole')">Borrar</button>
                        @endif
                    </td>
                    <td>
                        <div>
                            <label class="tabla-role__checkLabel">
                                <input 
                                    data-id="{{ $r->id }}" 
                                    type="checkbox" 
                                    class="disabled:bg-gray-300"
                                    @if ( !$this->userSelected ) disabled @endif
                                    @if ( $r->checked ) checked @endif
                                    >
                                Asignar
                            </label>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h2 class="tab-role__titulo text-xl font-bold" style="margin-top:1.5rem;">Crear / Editar roles</h2>
        
        <div class="tab-role__editarRole">
            <input type="hidden" id="roleId" name="roleId">   
            <input type="text" id="roleName" name="roleName">
            <button 
                class="boton boton--azul" 
                wire:click="$emitSelf('crearRole',document.getElementById('roleName').value, document.getElementById('roleId').value)"
            >Guardar cambios
            </button>
            <button class="boton boton--gris" onclick="clearRoleSelected()">Cancelar</button>
        </div>

    </div>


    <script>
    
        function showRole(role)
        {
            var data = JSON.parse(role);

            document.querySelector("#roleId").value = data["id"];
            document.querySelector("#roleName").value = data["name"];
            
        }

        function clearRoleSelected()
        {
            document.querySelector("#roleId").value = "0";
            document.querySelector("#roleName").value = "";
        }

        function confirmar(id, eventName)
        {
            if (confirm("Seguro que quieres borrar el registro?"))
            {            
                window.livewire.emit(eventName, id);
                clearRoleSelected();
            }
            else
            {
                console.log("Cancelado");
            }            
        }

        function checkAll(valueMasterCheck)
        {
            const elements = document.querySelectorAll(["#tab-role__tablaRoles [type=checkbox]"]);

            elements.forEach(element => {
                if(element.dataset.id != "all"){
                    element.checked = valueMasterCheck;                    
                }
            });
        }

        function checkIfAll(){
            const idTabla = "#tab-role__tablaRoles";
            const masterCheck = "tab-role__checkboxMaster";

            const total = document.querySelectorAll(idTabla + " [type=checkbox]").length - 1;
            const checked = document.querySelectorAll(idTabla + " [type=checkbox]:checked").length;
            var todos = true;

            
            document
                .querySelectorAll(idTabla + " [type=checkbox]")
                .forEach(element=>{
                    if(element.dataset.id != "all"){
                        todos = todos && element.checked;
                    }
                });

            if(todos){
                document.getElementById(masterCheck).checked = true;
            }
            else{
                document.getElementById(masterCheck).checked = false;
            }
        }

        function asignarRoles(){
            const roleList = [];

            document
                .querySelectorAll("#tab-role__tablaRoles [type=checkbox]:checked")
                .forEach(element => {
                    if(element.dataset.id != "all"){
                        roleList.push(element.dataset.id);
                    }
                });
            
            window.livewire.emit("asignarRoles", roleList);
        }



        window.addEventListener('clearRoleSelected', event => {
            clearRoleSelected();
        });

        document.getElementById("tab-role__btnAsignarRoles").addEventListener("click", asignarRoles);
        
        document.getElementById('tab-role__checkboxMaster').addEventListener("click",(event)=>{
            checkAll(event.target.checked);
        });

        document
            .querySelectorAll("#tab-role__tablaRoles [type=checkbox]")
            .forEach(element=>{
                if(element.dataset.id != "all"){
                    element.addEventListener("change",function(){checkIfAll();});
                }
            });

    </script>

</div>

