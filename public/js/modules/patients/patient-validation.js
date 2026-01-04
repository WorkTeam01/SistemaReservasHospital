/**
 * ============================================================================
 * GESTIÓN DE PACIENTES - Validación de Formularios
 * ============================================================================
 * Implementa jQuery Validate para los formularios de creación y edición de pacientes.
 * Incluye validaciones en tiempo real y verificación AJAX asíncrona de DNI duplicado.
 */

$(document).ready(function () {

    // ========================================================================
    // CONFIGURACIÓN DE VALIDACIÓN DEL FORMULARIO
    // ========================================================================
    $("#patientForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            dni: {
                required: true,
                minlength: 5,
                maxlength: 20,
                remote: {
                    url: URL_BASE + "/pacientes/check-dni",
                    type: "get",
                    async: true,
                    data: {
                        dni: function () {
                            return $("input[name='dni']").val();
                        },
                        patient_id: function () {
                            // Si existe patient_id, estamos editando
                            return $("#patient_id").val() || null;
                        }
                    }
                }
            },
            phone: {
                required: true,
                minlength: 7,
                maxlength: 20,
                digits: true
            },
            email: {
                email: true,
                maxlength: 100
            },
            birth_date: {
                date: true
            }
        },
        messages: {
            name: {
                required: "El nombre es obligatorio",
                minlength: "El nombre debe tener al menos 2 caracteres",
                maxlength: "El nombre no puede exceder 100 caracteres"
            },
            last_name: {
                required: "El apellido es obligatorio",
                minlength: "El apellido debe tener al menos 2 caracteres",
                maxlength: "El apellido no puede exceder 100 caracteres"
            },
            dni: {
                required: "El DNI es obligatorio",
                minlength: "El DNI debe tener al menos 5 caracteres",
                maxlength: "El DNI no puede exceder 20 caracteres",
                remote: "El DNI ya está registrado"
            },
            phone: {
                required: "El teléfono es obligatorio",
                minlength: "El teléfono debe tener al menos 7 dígitos",
                maxlength: "El teléfono no puede exceder 20 dígitos",
                digits: "El teléfono solo puede contener números"
            },
            email: {
                email: "Ingrese un correo electrónico válido",
                maxlength: "El correo no puede exceder 100 caracteres"
            },
            birth_date: {
                date: "Ingrese una fecha válida"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');

            // Si el elemento está dentro de un input-group, colocar error después del grupo
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent('.input-group'));
            } else {
                element.closest('.mb-3').append(error);
            }
        },
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function (form) {
            // Detectar si es creación o edición
            const isEdit = $(form).find('#patient_id').length > 0;
            const $submitBtn = $(form).find('button[type="submit"]');
            const originalText = $submitBtn.html();
            const loadingText = isEdit
                ? '<i class="fas fa-spinner fa-spin me-1"></i> Actualizando...'
                : '<i class="fas fa-spinner fa-spin me-1"></i> Registrando...';
            const mensaje = isEdit ? 'Actualizando cliente...' : 'Registrando cliente...';

            // Deshabilitar el botón y mostrar spinner
            $submitBtn.prop('disabled', true).html(loadingText);

            // Mostrar toast de carga con tiempo mínimo
            ToastUtils.loadingWithMinTime(mensaje, () => {
                form.submit();
            }, 1500);

            // Restaurar botón en caso de error (por si el submit no redirige)
            setTimeout(function() {
                $submitBtn.prop('disabled', false).html(originalText);
            }, 5000);
        }
    });

    // ========================================================================
    // VALIDACIÓN EN TIEMPO REAL MEJORADA
    // ========================================================================

    // Validar nombre en tiempo real (solo letras y espacios)
    $("input[name='name'], input[name='last_name']").on('input', function() {
        // Remover caracteres no permitidos (solo letras, espacios, tildes, ñ)
        this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    });

    // Validar teléfono en tiempo real (solo números)
    $("input[name='phone']").on('input', function() {
        // Remover caracteres no numéricos
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validar DNI en tiempo real (solo números y letras, sin espacios)
    $("input[name='dni']").on('input', function() {
        // Remover caracteres especiales y espacios
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    });

    // ========================================================================
    // VALIDACIÓN DE FECHA DE NACIMIENTO
    // ========================================================================
    $("input[name='birth_date']").on('change', function() {
        const birthDate = new Date(this.value);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        // Validar que la fecha no sea futura
        if (birthDate > today) {
            ToastUtils.warning('La fecha de nacimiento no puede ser futura');
            this.value = '';
            return;
        }

        // Validar que la edad sea razonable (no mayor a 120 años)
        if (age > 120 || (age === 120 && monthDiff > 0)) {
            ToastUtils.warning('La fecha de nacimiento no es válida');
            this.value = '';
            return;
        }

        // Validar que sea mayor de edad (opcional, puede comentarse)
        // if (age < 18 || (age === 18 && monthDiff < 0)) {
        //     ToastUtils.info('El paciente es menor de edad');
        // }
    });

    // ========================================================================
    // FORMATEO AUTOMÁTICO
    // ========================================================================

    // Capitalizar primera letra de nombre y apellido al salir del campo
    $("input[name='name'], input[name='last_name']").on('blur', function() {
        if (this.value) {
            // Capitalizar cada palabra
            this.value = this.value.toLowerCase().replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }
    });

    // Convertir email a minúsculas
    $("input[name='email']").on('blur', function() {
        if (this.value) {
            this.value = this.value.toLowerCase().trim();
        }
    });

});

