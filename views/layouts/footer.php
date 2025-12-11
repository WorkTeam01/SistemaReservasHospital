  </main>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
          <div class="text-muted">
              <small>
                  <i class="fas fa-tag"></i> Versión 1.0.0
              </small>
          </div>
      </div>
      <div class="footer-content">
          <strong>Copyright &copy; <?= date('Y'); ?>
              <a href="#" class="text-decoration-none">Hospital System</a>
          </strong>
          - Sistema de Reservas de Citas
      </div>
  </footer>

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="<?= URL_BASE; ?>/js/lib/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= URL_BASE; ?>/js/lib/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= URL_BASE; ?>/js/lib/adminlte/adminlte.min.js"></script>
  <!-- Plugins (Select2, SweetAlert2, DataTables) -->
  <script src="<?= URL_BASE; ?>/js/plugins/sweetalert2/sweetalert2.all.min.js"></script>

  <!-- Custom JS (Core) -->
  <script src="<?= URL_BASE; ?>/js/core/sweetalert-utils.js"></script>
  <script src="<?= URL_BASE; ?>/js/core/app.js"></script>

  <!-- Flash Messages -->
  <?php require_once __DIR__ . '/messages.php'; ?>

  <!-- Page-specific JS -->
  <?php if (!empty($pageScripts)): ?>
      <?php foreach ($pageScripts as $script): ?>
          <script src="<?= URL_BASE; ?>/<?= $script; ?>"></script>
      <?php endforeach; ?>
  <?php endif; ?>
  </body>

  </html>