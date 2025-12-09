<?php

if ((isset($_SESSION['message'])) && (isset($_SESSION['icon']))) {
    $response = $_SESSION['message'];
    $icon = $_SESSION['icon']; ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "<?= $icon; ?>",
            title: "<?= $response; ?>"
        });
    </script>
<?php
    unset($_SESSION['message']);
    unset($_SESSION['icon']);
} ?>