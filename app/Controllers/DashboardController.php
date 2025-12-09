<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        // Simular sesión si no existe (para desarrollo)
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_name'])) {
            $_SESSION['user_name'] = 'Usuario Dev';
        }

        // Obtener estadísticas del modelo con manejo de errores
        try {
            $data = [
                'pageTitle' => 'Dashboard - Sistema de Reservas',
                'pageStyles' => ['css/modules/dashboard/dashboard.css'],
                'pageScripts' => ['js/modules/dashboard/dashboard.js'],
                'totalUsers' => $this->dashboardModel->getTotalUsers(),
                'totalPatients' => $this->dashboardModel->getTotalPatients(),
                'pendingAppointments' => $this->dashboardModel->getPendingAppointments(),
                'todayAppointments' => $this->dashboardModel->getTodayAppointments(),
                'upcomingAppointments' => []  // Comentado temporalmente hasta verificar estructura BD
            ];
        } catch (\Exception $e) {
            // Si hay error en BD, usar valores por defecto
            $data = [
                'pageTitle' => 'Dashboard - Sistema de Reservas',
                'pageStyles' => ['css/modules/dashboard/dashboard.css'],
                'pageScripts' => ['js/modules/dashboard/dashboard.js'],
                'totalUsers' => 0,
                'totalPatients' => 0,
                'pendingAppointments' => 0,
                'todayAppointments' => 0,
                'upcomingAppointments' => []
            ];
        }

        // Renderizar vista con layout completo (header, sidebar, footer)
        $this->renderWithLayout('dashboard/admin', $data);
    }
}
