/**
 * ============================================================================
 * GESTIÓN DE ESPECIALIDADES - Operaciones CRUD con Modales
 * ============================================================================
 * Maneja las operaciones de crear, editar y gestionar especialidades mediante
 * modales y AJAX usando ToastUtils para feedback visual.
 */

// Variable global para prevenir envíos múltiples
let isSubmitting = false;

$(document).ready(function () {

    // ========================================================================
    // VALIDACIÓN CON JQUERY VALIDATE - FORMULARIO CREAR
    // ========================================================================
    $('#formCreate').validate({
        rules: {
            name: {
                required: true,
                maxlength: 100,
                remote: {
                    url: URL_BASE + '/especialidades/check-name',
                    type: 'POST',
                    data: {
                        name: function() {
                            return $('#name').val();
                        },
                        id: function() {
                            return null;
                        }
                    }
                }
            }
        },
        messages: {
            name: {
                required: 'El nombre es obligatorio',
                maxlength: 'El nombre no puede exceder 100 caracteres',
                remote: 'La especialidad ya existe'
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function () {
            crearEspecialidad();
        }
    });

    // ========================================================================
    // VALIDACIÓN CON JQUERY VALIDATE - FORMULARIO EDITAR
    // ========================================================================
    $('#formEdit').validate({
        rules: {
            name: {
                required: true,
                maxlength: 100,
                remote: {
                    url: URL_BASE + '/especialidades/check-name',
                    type: 'POST',
                    data: {
                        name: function() {
                            return $('#edit-name').val();
                        },
                        id: function() {
                            return $('#edit-id').val();
                        }
                    }
                }
            }
        },
        messages: {
            name: {
                required: 'El nombre es obligatorio',
                maxlength: 'El nombre no puede exceder 100 caracteres',
                remote: 'La especialidad ya existe'
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function () {
            actualizarEspecialidad();
        }
    });

    // ========================================================================
    // CREAR ESPECIALIDAD
    // ========================================================================
    function crearEspecialidad() {
        if (isSubmitting) return;

        const formData = $('#formCreate').serialize();
        const submitBtn = $('#btnCreate');
        const originalText = submitBtn.html();

        isSubmitting = true;
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

        ToastUtils.loadingWithMinTime('Guardando especialidad...', (loadingToast) => {
            $.ajax({
                url: URL_BASE + '/especialidades/crear',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    loadingToast.close();
                    if (response.success) {
                        $('#modal-create').modal('hide');
                        $('#formCreate')[0].reset();
                        ToastUtils.success(response.message);
                        setTimeout(() => window.location.reload(), 3000);
                    } else {
                        isSubmitting = false;
                        submitBtn.prop('disabled', false).html(originalText);
                        ToastUtils.error(response.message);
                    }
                },
                error: function () {
                    loadingToast.close();
                    isSubmitting = false;
                    submitBtn.prop('disabled', false).html(originalText);
                    ToastUtils.error('Error al guardar');
                }
            });
        }, 2000);
    }

    // ========================================================================
    // CARGAR DATOS PARA EDITAR (AJAX)
    // ========================================================================
    $('#specialtiesTable').on('click', '.btn-edit', function () {
        if ($(this).data('processing')) return;

        const $button = $(this);
        const id = $button.data('id');

        $button.data('processing', true);
        $button.prop('disabled', true);

        ToastUtils.loadingWithMinTime('Cargando datos...', (loadingToast) => {
            $.ajax({
                url: URL_BASE + '/especialidades/show?id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    loadingToast.close();
                    $button.data('processing', false);
                    $button.prop('disabled', false);

                    if (response.success) {
                        const data = response.data;
                        $('#edit-id').val(data.specialty_id);
                        $('#edit-name').val(data.name);

                        // Resetear errores previos
                        $('#formEdit').validate().resetForm();
                        $('#formEdit').find('.is-invalid').removeClass('is-invalid');

                        $('#modal-edit').modal('show');
                    } else {
                        ToastUtils.error(response.message);
                    }
                },
                error: function () {
                    loadingToast.close();
                    $button.data('processing', false);
                    $button.prop('disabled', false);
                    ToastUtils.error('Error al cargar datos');
                }
            });
        }, 1500);
    });

    // ========================================================================
    // ACTUALIZAR ESPECIALIDAD
    // ========================================================================
    function actualizarEspecialidad() {
        if (isSubmitting) return;

        const formData = $('#formEdit').serialize();
        const submitBtn = $('#btnUpdate');
        const originalText = submitBtn.html();

        isSubmitting = true;
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

        ToastUtils.loadingWithMinTime('Actualizando...', (loadingToast) => {
            $.ajax({
                url: URL_BASE + '/especialidades/editar',
                type: 'POST',
                data: formData, // El ID va incluido en el formData
                dataType: 'json',
                success: function (response) {
                    loadingToast.close();
                    if (response.success) {
                        $('#modal-edit').modal('hide');
                        ToastUtils.success(response.message);
                        setTimeout(() => window.location.reload(), 3000);
                    } else {
                        isSubmitting = false;
                        submitBtn.prop('disabled', false).html(originalText);
                        ToastUtils.error(response.message);
                    }
                },
                error: function () {
                    loadingToast.close();
                    isSubmitting = false;
                    submitBtn.prop('disabled', false).html(originalText);
                    ToastUtils.error('Error al actualizar');
                }
            });
        }, 2000);
    }

    // ========================================================================
    // ELIMINAR (AJAX) - Adaptado para usar delegación
    // ========================================================================
    // ========================================================================
    // TOGGLE ESTADO (AJAX) - Con enlaces simples
    // ========================================================================
    $('#specialtiesTable').on('click', '.btn-toggle-specialty', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const status = $(this).data('status');

        const isDeactivating = (status === 1);
        const title = isDeactivating ? '¿Desactivar especialidad?' : '¿Activar especialidad?';
        const text = isDeactivating
            ? "La especialidad no estará disponible para nuevas citas."
            : "La especialidad volverá a estar disponible.";

        AlertUtils.confirm(
            title,
            text,
            function() {
                // Callback cuando se confirma
                $.ajax({
                    url: URL_BASE + '/especialidades/toggle',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            ToastUtils.success(response.message);
                            setTimeout(() => window.location.reload(), 3000);
                        } else {
                            ToastUtils.error(response.message);
                        }
                    },
                    error: function () {
                        ToastUtils.error('Error al cambiar estado');
                    }
                });
            },
            {
                icon: 'warning',
                confirmColor: isDeactivating ? '#d33' : '#17a2b8',
                cancelColor: '#6c757d',
                confirmText: isDeactivating ? 'Sí, desactivar' : 'Sí, activar',
                cancelText: 'Cancelar'
            }
        );
    });

    // ========================================================================
    // RESETEAR FORMULARIOS AL CERRAR
    // ========================================================================
    $('.modal').on('hidden.bs.modal', function () {
        isSubmitting = false;
        $(this).find('form')[0].reset();
        $(this).find('form').validate().resetForm();
        $(this).find('.is-invalid').removeClass('is-invalid');

        // Reset botones
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', false);
        // Restaurar icono si es necesario (simple reset)
        if ($btn.attr('id') === 'btnCreate') $btn.html('Guardar');
        if ($btn.attr('id') === 'btnUpdate') $btn.html('Actualizar');
    });

});