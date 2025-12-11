/**
 * App.js - Inicialización global de la aplicación
 * Este archivo se carga en todas las páginas del sistema
 */

$(document).ready(function () {
    // Inicialización de componentes AdminLTE
    // (AdminLTE se inicializa automáticamente)

    // Configuración global de AJAX para incluir CSRF token si es necesario
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // Aquí puedes agregar inicializaciones globales
    console.log('Hospital System - App initialized');
});
