require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Para que esta función funcione correctamente se necesita tener instalado el paquete owenvoke/blade-fontawesome
// y tener publicados los iconos con "php artisan vendor:publish --tag=blade-fontawesome --force"
// El parámetro de entrada estará en formato fas-cloud, far-camera, fab-.... 

window.getFontAwesomePath = function getFontAwesomePath(icono){
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