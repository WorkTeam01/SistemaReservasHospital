<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Panel Administrador</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Panel</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 class="info-box-number" id="total-users"><?= $totalUsers ?? 0; ?></h3>
                        <p>Usuarios Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="<?= URL_BASE; ?>/usuarios" class="small-box-footer">
                        Más info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class="info-box-number" id="total-patients"><?= $totalPatients ?? 0; ?></h3>
                        <p>Pacientes Registrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?= URL_BASE; ?>/pacientes" class="small-box-footer">
                        Más info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 class="info-box-number" id="pending-appointments"><?= $pendingAppointments ?? 0; ?></h3>
                        <p>Citas Pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="<?= URL_BASE; ?>/citas?status=pending" class="small-box-footer">
                        Más info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 class="info-box-number" id="today-appointments"><?= $todayAppointments ?? 0; ?></h3>
                        <p>Citas de Hoy</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <a href="<?= URL_BASE; ?>/citas?date=today" class="small-box-footer">
                        Más info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </div>
</section>
<!-- /.content -->