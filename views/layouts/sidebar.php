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

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Panel de Navegación -->
                <li class="nav-header">PANEL</li>

                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>" class="nav-link active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Gestión Section -->
                <li class="nav-header">GESTIÓN</li>

                <!-- Usuarios -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/users" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Usuarios</p>
                    </a>
                </li>

                <!-- Pacientes -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/pacientes" class="nav-link">
                        <i class="fas fa-user-alt nav-icon"></i>
                        <p>Pacientes</p>
                    </a>
                </li>

                <!-- Citas -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/appointments" class="nav-link">
                        <i class="fas fa-calendar-day nav-icon"></i>
                        <p>Citas Médicas</p>
                    </a>
                </li>

                <!-- Especialidades -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/especialidades" class="nav-link">
                        <i class="fas fa-stethoscope nav-icon"></i>
                        <p>Especialidades</p>
                    </a>
                </li>

                <li class="nav-header">CONFIGURACIÓN</li>

                <!-- Configuración de horarios -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/configuration" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Horarios de Atención</p>
                    </a>
                </li>

                <!-- Logs de citas -->
                <li class="nav-item">
                    <a href="<?= URL_BASE; ?>/logs" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Historial de Citas</p>
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