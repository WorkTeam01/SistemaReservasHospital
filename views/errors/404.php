<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página No Encontrada | Hospital System</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/bootstrap/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/fontawesome/css/all.min.css">

    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/adminlte/adminlte.min.css">

    <!-- Error Pages Styles -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/modules/errors/errors.css">

    <!-- Icon -->
    <link rel="icon" type="image/png" href="<?= URL_BASE; ?>/img/cita-medica.png">
</head>

<body class="hold-transition bg-light">
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-md-8 text-center">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                            <h1 class="display-4 mt-3 text-warning font-weight-bold">404</h1>
                            <h2 class="h4 text-warning mb-4">¡Página No Encontrada!</h2>
                        </div>

                        <p class="lead text-muted mb-5">
                            No pudimos encontrar la página que estás buscando.
                            La página puede haber sido movida o eliminada.
                        </p>

                        <div class="d-flex justify-content-around flex-wrap" style="gap: 1rem;">
                            <button onclick="window.history.back()" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-left mr-2"></i> Volver Atrás
                            </button>
                            <a href="<?= URL_BASE; ?>" class="btn btn-primary px-4">
                                <i class="fas fa-home mr-2"></i> Ir al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= URL_BASE; ?>/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= URL_BASE; ?>/js/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= URL_BASE; ?>/js/lib/adminlte/adminlte.min.js"></script>
</body>

</html>