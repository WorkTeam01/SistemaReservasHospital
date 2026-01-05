<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Pacientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= URL_BASE ?>/dashboard"><i class="fas fa-home"></i> Inicio</a></li>
                    <li class="breadcrumb-item active">Pacientes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-outline card-primary">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title">Listado de Pacientes</h3>
                    <div class="card-tools d-flex">
                        <a href="<?= URL_BASE ?>/pacientes/crear" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Nuevo Paciente
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    El listado de pacientes será implementado en el próximo módulo (RF04).
                </div>
            </div>
        </div>
    </div>
</section>
