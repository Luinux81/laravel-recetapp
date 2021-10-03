<div class="tab-panel tab-permisos">
    
    <div class="tab-permisos__permisos my-6">

        <div class="tab-permisos__usuarios my-6">
            <h2 class="tab-permisos__titulo text-xl font-bold">Asignación de permisos</h2>

            <select wire:model="roleSelected" id="tab-permisos__selectRoles">
                <option value="0" selected>Selecciona</option>
                
                @foreach ($roles as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>    
                @endforeach
                
            </select>

            <button 
                id="tab-permisos__btnAsignarPermisos" 
                class="boton boton--azul"                 
                @if ( !$this->roleSelected ) disabled @endif
            >
            Asignar Permisos
            </button>
        </div>


        <h2 class="tab-permisos__titulo text-xl font-bold" style="margin-top:1.5rem;">Permisos del sistema</h2>
        <table id="tab-permisos__tablaPermisos" class="tabla-permisos">
            <thead> 
                <th>Descripcion</th>
                <th>Usuarios con permiso</th>
                <th>Acciones</th>
                <th>
                    <label>
                        <input 
                            type="checkbox" 
                            id="tab-permisos__checkboxMaster" 
                            data-id="all" 
                            class="disabled:bg-gray-300"
                            @if ( !$this->roleSelected ) disabled @endif
                            @if ( $numPermisosAsignados == $permisos->count() ) checked @endif
                            > 
                        TODOS
                    </label>
                </th>
            </thead>
            <tbody>
            @foreach($permisos as $p)
                <tr>
                    <td> {{ $p->name }}</td>
                    <td> {{ \App\Models\User::permission($p->name)->count() }} </td>
                    <td>
                        <button class="boton boton--gris" onclick="showPermission('{{$p}}')">Editar</button>
                        
                        @if (\App\Models\User::permission($p->name)->count()<1)
                            <button class="boton boton--rojo" onclick="confirmarBorradoPermiso({{ $p->id }}, 'destroyPermission')">Borrar</button>
                        @endif
                    </td>
                    <td>
                        <div>
                            <label class="tabla-role__checkLabel">
                                <input 
                                    data-id="{{ $p->id }}" 
                                    type="checkbox" 
                                    class="disabled:bg-gray-300"
                                    @if ( !$this->roleSelected ) disabled @endif
                                    @if ( $p->checked ) checked @endif
                                    >
                                Asignar
                            </label>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h2 class="tab-permisos__titulo text-xl font-bold" style="margin-top:1.5rem;">Crear / Editar permisos</h2>
        
        <div class="tab-permisos__editarPermiso">
            <input type="hidden" id="permissionId" name="permissionId">            
            <input type="text" id="permissionName" name="permissionName">
            <button 
                class="boton boton--azul" 
                wire:click="$emitSelf('crearPermission',document.getElementById('permissionName').value, document.getElementById('permissionId').value)"
            >Guardar cambios
            </button>
            <button class="boton boton--gris" onclick="clearPermissionSelected()">Cancelar</button>
        </div>
    </div>


    <script>
        
        function showPermission(permission)
        {
            var data = JSON.parse(permission);

            document.querySelector("#permissionId").value = data["id"];
            document.querySelector("#permissionName").value = data["name"];
            
        }

        function clearPermissionSelected()
        {
            document.querySelector("#permissionId").value = "0";
            document.querySelector("#permissionName").value = "";
        }

        function confirmarBorradoPermiso(id, eventName)
        {
            if(typeof window.Swal !== "undefined"){
                window.Swal.fire({
                    title: 'Confirmar borrado',
                    text: '¿Estás seguro/a de borrar el registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText:'Si',
                    confirmButtonAriaLabel: 'Yes',
                    cancelButtonText:'No',
                    cancelButtonAriaLabel: 'No'
                }).then(function(value){                    
                    if(value.isConfirmed){
                        window.livewire.emit(eventName, id);
                        clearPermissionSelected();
                    }                    
                });
            }
            else{
                if(confirm("Seguro que quieres borrar el registro?")){
                    window.livewire.emit(eventName, id);
                    clearPermissionSelected();
                }
            }      
        }

        function checkAll(valueMasterCheck)
        {
            const elements = document.querySelectorAll(["#tab-permisos__tablaPermisos [type=checkbox]"]);

            elements.forEach(element => {
                if(element.dataset.id != "all"){
                    element.checked = valueMasterCheck;                    
                }
            });
        }

        function checkIfAll(){
            const idTabla = "#tab-permisos__tablaPermisos";
            const masterCheck = "tab-permisos__checkboxMaster";

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

        function asignarPermisos()
        {
            permissionList = [];

            document
                .querySelectorAll("#tab-permisos__tablaPermisos [type=checkbox]:checked")
                .forEach(element => {
                    if(element.dataset.id != "all"){
                        permissionList.push(element.dataset.id);
                    }
                });



            window.livewire.emit("asignarPermisos", permissionList);
        }



        window.addEventListener('clearPermissionSelected', event => {
            clearPermissionSelected();
        });

        window.addEventListener('setCheckboxesEventListener', event => {
            setCheckboxesEventListener();
            checkIfAll();
        });

        document.getElementById("tab-permisos__btnAsignarPermisos").addEventListener("click", asignarPermisos);
        
        document.getElementById('tab-permisos__checkboxMaster').addEventListener("click",(event)=>{
            checkAll(event.target.checked);
        });

        setCheckboxesEventListener();

        function setCheckboxesEventListener(){
            document
                .querySelectorAll("#tab-permisos__tablaPermisos [type=checkbox]")
                .forEach(element=>{
                    if(element.dataset.id != "all"){
                        element.addEventListener("change",function(){checkIfAll();});
                    }
                });
        }
    </script>
</div>