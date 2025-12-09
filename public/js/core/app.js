/**
 * App.js - Funciones globales del sistema
 */

$(document).ready(function () {
    console.log('Hospital System - App loaded');
});

/**
 * Muestra un mensaje de éxito usando SweetAlert2 o alert nativo
 */
function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

/**
 * Muestra un mensaje de error usando SweetAlert2 o alert nativo
 */
function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    } else {
        alert(message);
    }
}

/**
 * Confirma una acción usando SweetAlert2 o confirm nativo
 */
function confirmAction(message, callback) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    } else {
        if (confirm(message)) {
            if (typeof callback === 'function') {
                callback();
            }
        }
    }
}
