<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Login'; ?> | Hospital System</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/core/fonts.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/fontawesome/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/lib/adminlte/adminlte.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Custom Login CSS -->
    <link rel="stylesheet" href="<?= URL_BASE; ?>/css/modules/auth/login.css">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="<?= URL_BASE; ?>/img/cita-medica.png">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= URL_BASE; ?>" class="h1"><b>Hospital</b>System</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Inicia sesión para comenzar</p>

                <form id="loginForm" action="<?= URL_BASE; ?>/login" method="post">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">

                    <!-- Email -->
                    <div class="input-group mb-3">
                        <input type="email"
                            class="form-control"
                            name="email"
                            id="email"
                            placeholder="Email"
                            autocomplete="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password"
                            class="form-control"
                            name="password"
                            id="password"
                            placeholder="Contraseña"
                            autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text toggle-password" onclick="togglePassword()">
                                <span class="fas fa-eye" id="toggleIcon"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1 text-center">
                    <small class="text-muted">Hospital System v1.0.0</small>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= URL_BASE; ?>/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= URL_BASE; ?>/js/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- jQuery Validation -->
    <script src="<?= URL_BASE; ?>/js/lib/jquery/jquery.validate.min.js"></script>
    <script src="<?= URL_BASE; ?>/js/lib/jquery/jquery.validate.messages_es.min.js"></script>
    <!-- Toastr -->
    <script src="<?= URL_BASE; ?>/js/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- SweetAlert2 Utils -->
    <script src="<?= URL_BASE; ?>/js/core/sweetalert-utils.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= URL_BASE; ?>/js/lib/adminlte/adminlte.min.js"></script>
    <!-- Custom Login JS -->
    <script src="<?= URL_BASE; ?>/js/modules/auth/login.js"></script>

    <!-- Messages (SweetAlert2) -->
    <?php require_once __DIR__ . '/../layouts/messages.php'; ?>

</body>

</html>