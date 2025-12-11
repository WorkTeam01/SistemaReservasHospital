<?php

/**
 * Flash Messages - Muestra mensajes de sesión usando ToastUtils y AlertUtils
 * Soporta dos formatos:
 * 1. $_SESSION['welcome_user'] - Mensaje de bienvenida especial (AlertUtils.welcome)
 * 2. $_SESSION['message'] + $_SESSION['icon'] - Mensajes flash estándar (ToastUtils)
 */

// Caso 1: Mensaje de Bienvenida Especial (Login)
if (isset($_SESSION['welcome_user'])) {
    $userName = addslashes($_SESSION['welcome_user']);
?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar cualquier toast de loading que esté abierto
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }

            // Usar AlertUtils.welcome para mensaje de bienvenida
            if (typeof AlertUtils !== 'undefined') {
                AlertUtils.welcome('<?= $userName; ?>');
            } else {
                // Fallback por si no carga el script
                Swal.fire({
                    icon: 'success',
                    title: 'Bienvenido, <?= $userName; ?>'
                });
            }
        });
    </script>
<?php
    unset($_SESSION['welcome_user']);
}

// Caso 2: Mensajes Flash Estándar - Formato 'message' + 'icon'
if ((isset($_SESSION['message'])) && (isset($_SESSION['icon']))) {
    $message = addslashes($_SESSION['message']);
    $icon = $_SESSION['icon'];
?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Usar ToastUtils según el tipo de icono
            if (typeof ToastUtils !== 'undefined') {
                <?php if ($icon === 'success'): ?>
                    ToastUtils.success('<?= $message; ?>');
                <?php elseif ($icon === 'error'): ?>
                    ToastUtils.error('<?= $message; ?>');
                <?php elseif ($icon === 'warning'): ?>
                    ToastUtils.warning('<?= $message; ?>');
                <?php elseif ($icon === 'info'): ?>
                    ToastUtils.info('<?= $message; ?>');
                <?php endif; ?>
            } else {
                // Fallback
                Swal.fire({
                    icon: '<?= $icon; ?>',
                    title: '<?= $message; ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    </script>
<?php
    unset($_SESSION['message']);
    unset($_SESSION['icon']);
}
?>