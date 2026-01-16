$(document).ready(function () {
    console.log('Inicializando acciones de pacientes...');

    // ========================================================================
    // TOGGLE ESTADO (AJAX) - Gestión de Pacientes
    // ========================================================================
    // Usamos delegación de eventos por si la tabla se repíntase o usara paginación AJAX en el futuro
    $('#patientsTable').on('click', '.btn-toggle-patient', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const id = $btn.data('id');
        const status = $btn.data('status');

        const isDeactivating = (status == 1);
        const action = isDeactivating ? 'desactivar' : 'activar';
        const title = isDeactivating ? '¿Desactivar paciente?' : '¿Activar paciente?';
        const text = isDeactivating
            ? 'El paciente no aparecerá en búsquedas de nuevas citas.'
            : 'El paciente volverá a estar disponible para citas.';
        const confirmColor = isDeactivating ? '#f39c12' : '#17a2b8';

        // Usamos la misma lógica de UI que en otras partes, preferiblemente AlertUtils si está disponible globalmente,
        // si no, usamos Swal directamente para asegurar compatibilidad.
        if (typeof AlertUtils !== 'undefined') {
            AlertUtils.confirm(
                title,
                text,
                function () {
                    performToggle(id);
                },
                {
                    icon: 'question',
                    confirmColor: confirmColor,
                    confirmText: 'Sí, ' + action,
                    cancelText: 'Cancelar'
                }
            );
        } else {
            // Fallback a Swal directo si AlertUtils no está cargado
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, ' + action,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    performToggle(id);
                }
            });
        }
    });

    function performToggle(id) {
        // Mostrar feedback de carga
        if (typeof ToastUtils !== 'undefined') {
            ToastUtils.loading('Procesando cambios...');
        }

        $.ajax({
            url: URL_BASE + '/pacientes/toggle',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                    Swal.close(); // Cerrar loading de Swal si estaba abierto
                }

                if (response.success) {
                    if (typeof ToastUtils !== 'undefined') {
                        ToastUtils.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    // Recargar la página para reflejar cambios
                    setTimeout(() => window.location.reload(), 3500);
                } else {
                    if (typeof ToastUtils !== 'undefined') {
                        ToastUtils.error(response.message);
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            },
            error: function () {
                if (typeof ToastUtils !== 'undefined') {
                    ToastUtils.error('Ocurrió un error al cambiar el estado del paciente.');
                } else {
                    alert('Error de conexión');
                }
            }
        });
    }
});