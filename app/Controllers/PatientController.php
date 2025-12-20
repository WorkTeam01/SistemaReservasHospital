<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware;
use App\Models\Patient;

class PatientController extends Controller
{
    private $patientModel;

    public function __construct()
    {
        $this->patientModel = new Patient();
    }

    public function index()
    {
        Middleware::auth();
        // TODO: Implementar en RF04
        $this->renderWithLayout('patients/index', [
            'pageTitle' => 'Lista de Pacientes'
        ]);
    }

    public function showCreate()
    {
        Middleware::auth();
        $this->renderWithLayout('patients/create', [
            'pageTitle' => 'Registrar Nuevo Paciente',
            'pageStyles' => ['css/modules/patients/patients.css'],
            'pageScripts' => ['js/modules/patients/patients.js']
        ]);
    }

    public function create()
    {
        Middleware::auth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/pacientes/crear');
            return;
        }

        // Sanitizar inputs
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'dni' => trim($_POST['dni'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => !empty($_POST['email']) ? $_POST['email'] : null,
            'birth_date' => !empty($_POST['birth_date']) ? $_POST['birth_date'] : null,
            'is_active' => 1
        ];

        // Validaciones
        $errors = [];

        if (empty($data['name'])) $errors[] = 'El nombre es obligatorio';
        if (empty($data['last_name'])) $errors[] = 'El apellido es obligatorio';
        if (empty($data['dni'])) {
            $errors[] = 'El DNI es obligatorio';
        } else {
            // Verificar DNI único
            if ($this->patientModel->findByDni($data['dni'])) {
                $errors[] = 'El DNI ya está registrado';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/pacientes/crear');
            return;
        }

        // Guardar paciente
        try {
            $this->patientModel->create($data);
            $_SESSION['message'] = 'Paciente registrado correctamente';
            $_SESSION['icon'] = 'success';
            $this->redirect('/pacientes');
        } catch (\Exception $e) {
            $_SESSION['message'] = 'Error al registrar paciente ' . $e->getMessage();
            $_SESSION['icon'] = 'error';
            $this->redirect('/pacientes/crear');
        }
    }
    /**
     * Verifica si un DNI ya existe (vía AJAX para validación remota)
     */
    public function checkDni()
    {
        $dni = $_GET['dni'] ?? null;
        if (!$dni) {
            echo json_encode(true);
            return;
        }

        $exists = $this->patientModel->findByDni($dni);
        
        // jQuery Validate espera "true" si es válido (no existe) 
        // y "false" si ya existe
        echo json_encode($exists ? false : true);
    }
}
