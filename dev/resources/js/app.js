require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Importación de sweet alert 2

import Swal from 'sweetalert2'
window.Swal = Swal;

// Código para notificaciones toast
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

window.Notyf = new Notyf({
    types: [
        {
            type: 'success',
            className: 'notyf__toast--success',
            backgroundColor: '#3dc763',
            icon: {
                className: 'notyf__icon--success',
                tagName: 'i',
            },
        },
        {
            type: 'info',
            className: 'notyf__toast--success',
            backgroundColor: '#3E4AEC',
            icon: {
                className: 'notyf__icon--success',
                tagName: 'i',
            },
        },        
        {
            type: 'error',
            className: 'notyf__toast--error',
            backgroundColor: '#ed3d3d',
            icon: {
                className: 'notyf__icon--error',
                tagName: 'i',
            },
        },
    ],}
);


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