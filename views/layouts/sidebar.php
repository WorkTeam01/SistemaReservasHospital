<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-2">
    <!-- Brand Logo -->
    <a href="<?= URL_BASE; ?>" class="brand-link">
        <img src="<?= URL_BASE; ?>/img/cita-medica.png" alt="Hospital System Logo"
            class="brand-image img-circle elevation-1" loading="eager">
        <span class="brand-text">Hospital System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= URL_BASE; ?>/img/user-default.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $userName ?? 'Usuario'; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header">MÓDULOS DEL SISTEMA</li>

                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Panel</p>
                    </a>
                </li>

                <!-- Gestión de Usuarios -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/usuarios" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/usuarios/crear" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Usuario</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Gestión de Pacientes -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-hospital-user"></i>
                        <p>
                            Pacientes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/pacientes" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Pacientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/pacientes/crear" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Paciente</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Gestión de Citas -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Citas Médicas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/citas" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lista de Citas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/citas/crear" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nueva Cita</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/citas/calendario" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Calendario</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Especialidades -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/especialidades" class="nav-link">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>Especialidades</p>
                    </a>
                </li>

                <!-- Reportes -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Reportes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/reportes/citas" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reporte de Citas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= URL_BASE; ?>/reportes/pacientes" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reporte de Pacientes</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">CONFIGURACIÓN</li>

                <!-- Configuración -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/configuracion" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Configuración</p>
                    </a>
                </li>

                <!-- Logs del Sistema -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/logs" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Historial de Cambios</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- Content Wrapper. Contains page content -->
<main class="content-wrapper">