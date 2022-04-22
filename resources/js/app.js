require('./bootstrap');

import Alpine from 'alpinejs';


window.Alpine = Alpine;

Alpine.start();


function sweetAlert(icon, message) {
    Swal.fire({
        icon: icon,
        title: message,
    })
}

function sweetAlertAutoClose(icon, message) {
    let timerInterval
    Swal.fire({
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 1500,
        willClose: () => {
            clearInterval(timerInterval)
        }
    });
}

