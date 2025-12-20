<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Sistema de Reservas Hospital'; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/bootstrap/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro (Local) -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/core/fonts.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/fontawesome/css/all.min.css">
    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/adminlte/adminlte.min.css">
    <!-- Custom style -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/core/style.css">
    <!-- Plugins (Select2, SweetAlert2, DataTables) -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="<?= URL_BASE; ?>/img/cita-medica.png">

    <!-- Page-specific CSS -->
    <?php if (!empty($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link rel="stylesheet" href="<?= URL_BASE; ?>/<?= $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Global JS Variables -->
    <script>
        const URL_BASE = "<?= URL_BASE; ?>";
    </script>
</head>

<body class="sidebar-mini layout-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <!-- Logo visible solo en móvil -->
                <li class="nav-item d-sm-none">
                    <a href="<?= URL_BASE; ?>" class="nav-link d-flex align-items-center">
                        <img src="<?= URL_BASE; ?>/img/cita-medica.png" alt="Logo Hospital System"
                            class="img-circle" style="width: 25px; height: 25px; margin-right: 8px;">
                        <span class="brand-text">Hospital System</span>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <div class="btn-group">
                    <button class="btn btn-link nav-link px-2" data-widget="fullscreen">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="<?= URL_BASE; ?>/logout" role="button">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->