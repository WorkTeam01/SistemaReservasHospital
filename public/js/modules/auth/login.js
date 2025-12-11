/**
 * Login Form Validation
 * Usa jQuery Validate para validación client-side
 */

$(document).ready(function () {
    // Configurar jQuery Validate
    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 25
            }
        },
        messages: {
            email: {
                required: "Por favor ingrese su email",
                email: "Por favor ingrese un email válido",
                maxlength: "El email no puede exceder 100 caracteres"
            },
            password: {
                required: "Por favor ingrese su contraseña",
                minlength: "La contraseña debe tener al menos 6 caracteres",
                maxlength: "La contraseña no puede exceder 255 caracteres"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function (form) {
            // Deshabilitar botón y mostrar spinner de Bootstrap
            const $submitBtn = $(form).find('button[type="submit"]');
            $submitBtn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Iniciando sesión...');

            // Mostrar loading toast con tiempo mínimo para evitar flashes
            ToastUtils.loadingWithMinTime('Iniciando sesión...', () => {
                form.submit();
            }, 1500);
        }
    });
});

/**
 * Toggle password visibility
 */
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
