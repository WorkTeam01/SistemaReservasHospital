  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-2">
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
                  <a href="#" class="d-block">Usuario Dev</a>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                  <li class="nav-header">MÓDULOS DEL SISTEMA</li>

                  <li class="nav-item">
                      <a href="<?= URL_BASE; ?>" class="nav-link">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p>Dashboard (Home)</p>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a href="<?= URL_BASE; ?>/login-demo" class="nav-link">
                          <i class="nav-icon fas fa-user"></i>
                          <p>Login (Maqueta)</p>
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