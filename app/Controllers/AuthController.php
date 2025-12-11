<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware;
use App\Core\Auth;
use App\Models\User;

/**
 * AuthController - Manejo de autenticación de usuarios
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Muestra el formulario de login
     * Solo accesible para usuarios no autenticados (guests)
     */
    public function showLogin()
    {
        Middleware::guest();
        $csrfToken = Auth::generateCsrfToken();

        $this->render('auth/login', [
            'pageTitle' => 'Iniciar Sesión',
            'csrfToken' => $csrfToken
        ]);
    }

    /**
     * Procesa el formulario de login
     * Valida credenciales y crea sesión si son correctas
     */
    public function login()
    {
        // Solo aceptar POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Validar CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Auth::validateCsrfToken($csrfToken)) {
            $_SESSION['message'] = 'Token de seguridad inválido. Por favor, intente nuevamente.';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
            return;
        }

        // Sanitizar y obtener datos del formulario
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        if (empty($email) || empty($password)) {
            $_SESSION['message'] = 'Email y contraseña son obligatorios';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
            return;
        }

        // Buscar usuario en la base de datos (solo activos)
        $user = $this->userModel->findByEmail($email);

        // Verificar si el usuario existe (incluyendo inactivos) para dar mensaje específico
        $userInactive = $this->userModel->findByEmailIncludingInactive($email);

        // Validar password con hash
        if ($user && password_verify($password, $user['password'])) {
            // Login exitoso
            Auth::login($user);

            // Mensaje de bienvenida especial
            $_SESSION['welcome_user'] = $user['name'];

            $this->redirect('/dashboard');
        } elseif ($userInactive && !$userInactive['is_active'] && password_verify($password, $userInactive['password'])) {
            // Usuario existe pero está desactivado
            $_SESSION['message'] = 'Tu cuenta ha sido desactivada. Contacta al administrador.';
            $_SESSION['icon'] = 'warning';
            $this->redirect('/login');
        } else {
            // Credenciales incorrectas o usuario no existe
            $_SESSION['message'] = 'Credenciales incorrectas';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
        }
    }
}
