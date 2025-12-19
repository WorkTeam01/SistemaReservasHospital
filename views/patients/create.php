<section class="content-header">
    <div class="container-fluid">
        <h1>Registrar Nuevo Paciente</h1>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <form id="patientForm" action="<?= URL_BASE ?>/pacientes/crear" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Juan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Ej: Pérez" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">DNI *</label>
                            <input type="text" name="dni" class="form-control" placeholder="Número de documento" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Teléfono *</label>
                            <div class="input-group">
                                <span class="input-group-text">+591</span>
                                <input type="text" name="phone" class="form-control" placeholder="70000000" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="birth_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Registrar Paciente
                        </button>
                        <a href="<?= URL_BASE ?>/pacientes" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
