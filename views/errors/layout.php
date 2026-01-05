<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $errorCode ?? '500'; ?> - <?= $errorTitle ?? 'Error'; ?> | Hospital System</title>

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
                            <i class="fas <?= $errorIcon ?? 'fa-exclamation-triangle'; ?> <?= $errorIconColor ?? 'text-warning'; ?>" style="font-size: 4rem;"></i>
                            <h1 class="display-4 mt-3 <?= $errorTitleColor ?? 'text-warning'; ?> font-weight-bold"><?= $errorCode ?? '500'; ?></h1>
                            <h2 class="h4 <?= $errorTitleColor ?? 'text-warning'; ?> mb-4"><?= $errorTitle ?? 'Error del Servidor'; ?></h2>
                        </div>

                        <p class="lead text-muted mb-5">
                            <?= $errorMessage ?? 'Ha ocurrido un error inesperado.'; ?>
                        </p>

                        <?php if (!empty($errorDetails)): ?>
                            <div class="alert alert-light border text-left mb-4">
                                <small class="text-muted"><?= $errorDetails; ?></small>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-around flex-wrap" style="gap: 1rem;">
                            <?php if ($showBackButton ?? true): ?>
                                <button onclick="window.history.back()" class="btn btn-secondary px-4">
                                    <i class="fas fa-arrow-left mr-2"></i> Volver Atrás
                                </button>
                            <?php endif; ?>
                            <?php if ($showHomeButton ?? true): ?>
                                <a href="<?= URL_BASE; ?>" class="btn btn-primary px-4">
                                    <i class="fas fa-home mr-2"></i> Ir al Inicio
                                </a>
                            <?php endif; ?>
                            <?php if ($showRefreshButton ?? false): ?>
                                <button onclick="location.reload()" class="btn btn-info px-4">
                                    <i class="fas fa-sync-alt mr-2"></i> Recargar Página
                                </button>
                            <?php endif; ?>
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

