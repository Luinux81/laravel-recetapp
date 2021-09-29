<div id="{{ $id }}" {{ $attributes }} data-activa="{{ $activa }}">
    <div id="{{ $id }}-tablist" role="tablist">
    </div>
    <div id="{{ $id }}-content">
        {{ $slot }}
    </div>    
</div>

<script>
document.addEventListener("DOMContentLoaded",()=>{
    const componente = document.getElementById('{{ $id }}');
    let links = document.getElementById('{{ $id }}-tablist');
    let tabs = document.getElementById('{{ $id }}-content');
    let index = 0;

    // Para cada hijo del componente creamos un botón con texto igual al atributo titulo del hijo,
    // Añadimos el botón a la tablist y le asignamos función para poner el atributo activa del componente
    // Lanzamos la función togglePanel para hacer visible la tab con nombre igual al atributo activa    
    Array.from(tabs.children).forEach((element)=>{
        var nombrePanel = element.dataset.nombre;        
        var boton = document.createElement("button");

        boton.id = '{{ $id }}'+'-boton-'+index;
        boton.innerText = element.dataset.titulo;
        boton.classList.add("tabs__boton");

        links.appendChild(boton);
        boton.addEventListener("click",function(event){
            componente.dataset.activa = nombrePanel;
            togglePanel();
        });
    });

    togglePanel();
});


function togglePanel(){
    const componente = document.getElementById('{{ $id }}');
    let tabs = document.getElementById('{{ $id }}-content');

    Array.from(tabs.children).forEach((element)=>{
        if(element.dataset.nombre == componente.dataset.activa){
            element.style.display = "block";
        }
        else{
            element.style.display = "none";
        }
    });
}

</script>