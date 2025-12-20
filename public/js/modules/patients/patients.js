/**
 * Patient Form Validation
 */

$(document).ready(function () {
    $('#patientForm').validate({
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            last_name: {
                required: true,
                maxlength: 100
            },
            dni: {
                required: true,
                minlength: 5,
                maxlength: 20,
                remote: {
                    url: URL_BASE + "/pacientes/check-dni",
                    type: "get",
                    data: {
                        dni: function () {
                            return $("#patientForm [name='dni']").val();
                        }
                    }
                }
            },
            phone: {
                required: true,
                minlength: 7,
                maxlength: 20
            },
            email: {
                email: true,
                maxlength: 100
            }
        },
        messages: {
            name: {
                required: "Por favor ingrese el nombre",
                maxlength: "El nombre es demasiado largo"
            },
            last_name: {
                required: "Por favor ingrese el apellido",
                maxlength: "El apellido es demasiado largo"
            },
            dni: {
                required: "El DNI es obligatorio",
                minlength: "DNI no válido",
                maxlength: "DNI demasiado largo",
                remote: "El DNI ya está registrado"
            },
            phone: {
                required: "El teléfono es obligatorio",
                minlength: "Teléfono no válido"
            },
            email: {
                email: "Ingrese un correo válido",
                maxlength: "El correo es demasiado largo"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Procesando...');

            ToastUtils.loading('Registrando paciente...');
            form.submit();
        }
    });
});
