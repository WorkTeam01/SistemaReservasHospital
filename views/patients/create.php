<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0">Registrar Paciente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= URL_BASE; ?>"><i class="fas fa-home"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= URL_BASE; ?>/pacientes"><i class="fas fa-user-injured"></i> Pacientes</a></li>
                    <li class="breadcrumb-item active">Registrar</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <!-- Columna Principal -->
            <div class="col-md-8">
                <form id="patientForm" action="<?= URL_BASE ?>/pacientes/store" method="POST">
                    <!-- Datos Básicos -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user"></i> Datos Básicos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Nombre -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nombre <span class="text-danger">*</span></label>
                                        <input type="text"
                                            id="name"
                                            name="name"
                                            class="form-control"
                                            placeholder="Ej: Juan"
                                            maxlength="100"
                                            required>
                                    </div>
                                </div>

                                <!-- Apellido -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Apellido <span class="text-danger">*</span></label>
                                        <input type="text"
                                            id="last_name"
                                            name="last_name"
                                            class="form-control"
                                            placeholder="Ej: Pérez"
                                            maxlength="100"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- DNI -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dni">DNI (Cédula de Identidad) <span class="text-danger">*</span></label>
                                        <input type="text"
                                            id="dni"
                                            name="dni"
                                            class="form-control"
                                            placeholder="Número de documento"
                                            maxlength="20"
                                            required>
                                    </div>
                                </div>

                                <!-- Fecha de Nacimiento -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_date">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                        <input type="date"
                                            id="birth_date"
                                            name="birth_date"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de Contacto -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-address-book"></i> Datos de Contacto</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Teléfono -->
                            <div class="form-group">
                                <label for="phone">Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i> +591</span>
                                    </div>
                                    <input type="text"
                                        id="phone"
                                        name="phone"
                                        class="form-control"
                                        placeholder="Ej: 70000000"
                                        maxlength="20"
                                        required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="ejemplo@correo.com"
                                        maxlength="100">
                                </div>
                                <small class="form-text text-muted">Opcional</small>
                            </div>

                            <!-- Dirección -->
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <textarea id="address"
                                    name="address"
                                    class="form-control"
                                    rows="2"
                                    placeholder="Ingrese la dirección completa"
                                    maxlength="500"></textarea>
                                <small class="form-text text-muted">Opcional</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                    <a href="<?= URL_BASE ?>/pacientes" class="btn btn-default btn-block">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                </div>
                                <div class="col-12 col-sm-auto">
                                    <button type="submit" class="btn btn-primary btn-block" id="btnCreatePatient">
                                        <i class="fas fa-save"></i> Registrar Paciente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Columna de Guía -->
            <div class="col-md-4">
                <!-- Guía de Registro -->
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-question-circle"></i> Guía de Registro</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info-circle"></i> Campos Obligatorios</h5>
                            <p class="mb-0">Los campos marcados con <span class="text-danger">*</span> son obligatorios para registrar al paciente.</p>
                        </div>

                        <h6 class="mt-3"><i class="fas fa-id-card text-primary"></i> DNI</h6>
                        <p class="text-sm text-muted">
                            Ingrese el número de Cédula de Identidad del paciente. Este dato es único y obligatorio.
                        </p>

                        <h6 class="mt-3"><i class="fas fa-birthday-cake text-info"></i> Fecha de Nacimiento</h6>
                        <p class="text-sm text-muted">
                            La fecha de nacimiento es obligatoria para el registro médico y cálculo de edad del paciente.
                        </p>

                        <h6 class="mt-3"><i class="fas fa-phone text-success"></i> Teléfono</h6>
                        <p class="text-sm text-muted">
                            Número de contacto del paciente. Es fundamental para recordatorios de citas y comunicaciones importantes.
                        </p>

                        <h6 class="mt-3"><i class="fas fa-envelope text-warning"></i> Email</h6>
                        <p class="text-sm text-muted">
                            El correo electrónico es opcional, pero útil para enviar notificaciones y confirmaciones de citas.
                        </p>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lightbulb"></i> Consejos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="text-sm text-muted pl-3">
                            <li class="mb-2">Verifique que el nombre y apellido estén correctamente escritos.</li>
                            <li class="mb-2">Asegúrese de que el DNI sea válido y no esté duplicado.</li>
                            <li class="mb-2">El teléfono debe ser válido para futuras comunicaciones.</li>
                            <li class="mb-2">La fecha de nacimiento debe ser coherente con la edad del paciente.</li>
                            <li class="mb-2">Puede agregar la dirección para ubicación en caso de emergencias.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
