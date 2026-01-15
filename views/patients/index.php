<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Pacientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= URL_BASE ?>/dashboard"><i class="fas fa-home"></i>
                            Inicio</a></li>
                    <li class="breadcrumb-item active">Pacientes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Principal: Tabla -->
            <div class="col-md-9">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h3 class="card-title">Listado de Pacientes</h3>
                            <div class="card-tools">
                                <a href="<?= URL_BASE ?>/pacientes/crear" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nuevo Paciente
                                </a>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="patientsTable" class="table table-bordered table-striped table-hover table-sm"
                            style="visibility: hidden">
                            <thead>
                                <tr>
                                    <th style="width: 50px">N°</th>
                                    <th>Nombre Completo</th>
                                    <th style="width: 120px">DNI</th>
                                    <th style="width: 120px">Teléfono</th>
                                    <th style="width: 180px">Email</th>
                                    <th style="width: 100px">Estado</th>
                                    <th style="width: 150px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                foreach ($patients as $patient):
                                    ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= htmlspecialchars($patient['name'] . ' ' . $patient['last_name']) ?></td>
                                        <td><?= htmlspecialchars($patient['dni']) ?></td>
                                        <td><?= htmlspecialchars($patient['phone']) ?></td>
                                        <td><?= htmlspecialchars($patient['email'] ?? 'N/A') ?></td>
                                        <td class="text-center">
                                            <?php if ($patient['is_active']): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <!-- Botón Ver Detalle -->
                                                <a href="<?= URL_BASE ?>/pacientes/ver/<?= $patient['patient_id'] ?>"
                                                    class="btn btn-info" title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Botón Editar -->
                                                <a href="<?= URL_BASE ?>/pacientes/editar/<?= $patient['patient_id'] ?>"
                                                    class="btn btn-success" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Botón Toggle Estado -->
                                                <!-- Botón Toggle Estado -->
                                                <?php if ($patient['is_active']): ?>
                                                    <button type="button" class="btn btn-warning btn-toggle-patient"
                                                        data-id="<?= $patient['patient_id'] ?>" data-status="1"
                                                        title="Desactivar">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-info btn-toggle-patient"
                                                        data-id="<?= $patient['patient_id'] ?>" data-status="0" title="Activar">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral: Información -->
            <div class="col-md-3">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-justify">
                            Gestiona la información de todos los pacientes registrados en el sistema.
                        </p>
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info"></i> Funciones:</h5>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-search text-info mr-1"></i>
                                    <strong>Buscar:</strong> Por nombre, DNI o teléfono
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-eye text-info mr-1"></i>
                                    <strong>Ver:</strong> Detalle completo del paciente
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-edit text-info mr-1"></i>
                                    <strong>Editar:</strong> Actualizar información
                                </li>
                                <li>
                                    <i class="fas fa-toggle-on text-info mr-1"></i>
                                    <strong>Estado:</strong> Activar/Desactivar
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>