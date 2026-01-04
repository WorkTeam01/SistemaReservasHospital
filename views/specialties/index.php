<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Especialidades</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item active">Especialidades</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Especialidades Activas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="fas fa-plus"></i> Nueva Especialidad
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="specialtiesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px">N°</th>
                                    <th>Nombre</th>
                                    <th style="width: 100px">Estado</th>
                                    <th style="width: 150px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                foreach ($specialties as $specialty): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= htmlspecialchars($specialty['name']) ?></td>
                                        <td>
                                            <?php if ($specialty['is_active']): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!-- Botón Editar con atributos data -->
                                                <button type="button" class="btn btn-success btn-sm btn-edit"
                                                    data-id="<?= $specialty['specialty_id'] ?>"
                                                    data-name="<?= htmlspecialchars($specialty['name']) ?>"
                                                    data-toggle="modal" data-target="#modal-edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Botón Toggle (Activar/Desactivar) -->
                                                <form action="/especialidades/toggle" method="POST"
                                                    class="d-inline form-toggle">
                                                    <input type="hidden" name="id"
                                                        value="<?= $specialty['specialty_id'] ?>">
                                                    <input type="hidden" name="status"
                                                        value="<?= $specialty['is_active'] ?>">
                                                    <?php if ($specialty['is_active']): ?>
                                                        <button type="submit" class="btn btn-warning btn-sm" title="Desactivar">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="btn btn-info btn-sm" title="Activar">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-outline card-info">
                        <h3 class="card-title">Información de especialidades</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-justify">
                            Las especialidades clasifican las áreas médicas del hospital. Son fundamentales para
                            organizar el personal y las citas.
                        </p>
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info"></i> Importante:</h5>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-info mr-1"></i>
                                    Los nombres deben ser <strong>únicos</strong> para evitar confusiones.
                                </li>
                                <li>
                                    <i class="fas fa-check text-info mr-1"></i>
                                    Al eliminar una especialidad, esta se <strong>desactiva</strong> pero se
                                    mantiene el historial.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Nueva Especialidad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/especialidades/crear" method="POST" id="formCreate">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre de la Especialidad</label>
                        <input type="text" class="form-control" name="name" placeholder="Ej: Cardiología" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>
                        Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnCreate"><i class="fas fa-save"></i>
                        Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Editar Especialidad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/especialidades/editar" method="POST" id="formEdit">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-name">Nombre de la Especialidad</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i>
                        Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnUpdate"><i class="fas fa-save"></i>
                        Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>