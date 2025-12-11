<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Middleware;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new Dashboard();
    }

    /**
     * Muestra el dashboard según el rol del usuario
     * Redirige a la vista correspondiente (admin, doctor, receptionist)
     */
    public function index()
    {
        // Proteger ruta - solo usuarios autenticados
        Middleware::auth();
        $userName = Auth::userName();

        // Obtener rol del usuario desde sesión
        $role = $_SESSION['user_role'];

        // Obtener estadísticas del dashboard
        try {
            $data = [
                'totalUsers' => $this->dashboardModel->getTotalUsers(),
                'totalPatients' => $this->dashboardModel->getTotalPatients(),
                'pendingAppointments' => $this->dashboardModel->getPendingAppointments(),
                'todayAppointments' => $this->dashboardModel->getTodayAppointments(),
                'upcomingAppointments' => $this->dashboardModel->getUpcomingAppointments(5)
            ];
        } catch (\Exception $e) {
            // Si hay error en BD, usar valores por defecto
            $data = [
                'totalUsers' => 0,
                'totalPatients' => 0,
                'pendingAppointments' => 0,
                'todayAppointments' => 0,
                'upcomingAppointments' => []
            ];
        }

        // Renderizar vista según rol
        switch ($role) {
            case 'admin':
                $this->renderWithLayout('dashboard/admin', array_merge($data, [
                    'pageTitle' => 'Dashboard Administrador',
                    'userName' => $userName,
                    'pageStyles' => ['css/modules/dashboard/dashboard.css'],
                    'pageScripts' => ['js/modules/dashboard/dashboard.js']
                ]));
                break;
            case 'doctor':
                $this->renderWithLayout('dashboard/doctor', array_merge($data, [
                    'pageTitle' => 'Dashboard Doctor',
                    'userName' => $userName,
                    'pageStyles' => ['css/modules/dashboard/dashboard.css'],
                    'pageScripts' => ['js/modules/dashboard/dashboard.js']
                ]));
                break;
            case 'receptionist':
                $this->renderWithLayout('dashboard/receptionist', array_merge($data, [
                    'pageTitle' => 'Dashboard Recepcionista',
                    'userName' => $userName,
                    'pageStyles' => ['css/modules/dashboard/dashboard.css'],
                    'pageScripts' => ['js/modules/dashboard/dashboard.js']
                ]));
                break;
            default:
                // Si el rol no es válido, cerrar sesión y redirigir
                session_destroy();
                $this->redirect('/login');
                break;
        }
    }
}
