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
    links.classList.add("flex");

    let tabs = document.getElementById('{{ $id }}-content');
    let index = 0;

    // Para cada hijo del componente creamos un botón con texto igual al atributo titulo del hijo,
    // Añadimos el botón a la tablist, detectamos si hay iconos (paquete blade-fontawesome publicado) y le asignamos función para poner el atributo activa del componente
    // Lanzamos la función togglePanel para hacer visible la tab con nombre igual al atributo activa    

    Array.from(tabs.children).forEach((element)=>{
        const nombrePanel = element.dataset.nombre;        
        const icono = element.dataset.icono;        
        const boton = document.createElement("button");

        boton.id = '{{ $id }}'+'-boton-'+index;        
        boton.classList.add("tabs__boton","flex-row-reverse");

        const texto = document.createElement("p");
        texto.innerText = element.dataset.titulo;
        boton.appendChild(texto);

        if(icono != ""){
            const img = document.createElement("img");
            img.src=getFontAwesomePath(icono);
            img.classList.add("h-6","w-6");
            boton.appendChild(img);
        }

        links.appendChild(boton);
        boton.addEventListener("click",function(event){
            componente.dataset.activa = nombrePanel;
            togglePanel();
        });

        index++;
    });

    togglePanel();
});


function togglePanel(){
    const componente = document.getElementById('{{ $id }}');
    let tabs = document.getElementById('{{ $id }}-content');
    let index = 0;
    let botonAsociado;

    Array.from(tabs.children).forEach((element)=>{
        botonAsociado = document.getElementById('{{ $id }}-boton-'+index);

        if(element.dataset.nombre == componente.dataset.activa){
            element.style.display = "block";
            botonAsociado.classList.add("tabs__boton--activo");
            botonAsociado.classList.remove("tabs__boton");
        }
        else{
            element.style.display = "none";
            botonAsociado.classList.add("tabs__boton");
            botonAsociado.classList.remove("tabs__boton--activo");            
        }

        index++;
    });
}


// Para que esta función funcione correctamente se necesita tener instalado el paquete owenvoke/blade-fontawesome
// y tener publicados los iconos con "php artisan vendor:publish --tag=blade-fontawesome --force"
// El parámetro de entrada estará en formato fas-cloud, far-camera, fab-.... 

function getFontAwesomePath(icono){
    var output = "vendor/blade-fontawesome/";
    const input = icono.split("-");

    if(input.lenght<1){
        return "";
    }

    switch(input[0]){
        case "fas":
            output += "solid/";
            break;
        case "far":
            output += "regular/";
            break;
        case "fab":
            output += "brands/";        
    }

    output += input[1] + '.svg';

    return output;
}

</script>