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
        // Obtener todos los pacientes (método heredado del Model)
        $patients = $this->patientModel->all();

        $this->renderWithLayout('patients/index', [
            'pageTitle' => 'Gestión de Pacientes',
            'patients' => $patients,
            'pageScripts' => [
                'js/modules/patients/datatable-patients.js',
                'js/modules/patients/patient-actions.js'
            ]
        ]);
    }

    public function showCreate()
    {
        Middleware::auth();
        $this->renderWithLayout('patients/create', [
            'pageTitle' => 'Registrar Nuevo Paciente',
            'pageScripts' => ['js/modules/patients/patient-validation.js']
        ]);
    }

    /**
     * Guarda un nuevo paciente en la base de datos
     */
    public function store()
    {
        Middleware::auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['message'] = 'Método no permitido';
            $_SESSION['icon'] = 'error';
            $this->redirect('/pacientes/crear');
            return;
        }

        // Sanitizar inputs
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'dni' => trim($_POST['dni'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
            'birth_date' => !empty($_POST['birth_date']) ? $_POST['birth_date'] : null,
            'is_active' => 1
        ];


        // Guardar paciente
        try {
            $result = $this->patientModel->create($data);

            if ($result) {
                $_SESSION['message'] = 'Paciente registrado correctamente';
                $_SESSION['icon'] = 'success';
                $this->redirect('/pacientes');
            } else {
                $_SESSION['message'] = 'No se pudo registrar el paciente';
                $_SESSION['icon'] = 'error';
                $this->redirect('/pacientes/crear');
            }
        } catch (\Exception $e) {
            $_SESSION['message'] = 'Error al registrar paciente: ' . $e->getMessage();
            $_SESSION['icon'] = 'error';
            $this->redirect('/pacientes/crear');
        }
    }
    /**
     * Verifica si un DNI ya existe (vía AJAX para validación remota)
     * Preparado para funcionar tanto en creación como en edición futura
     */
    public function checkDni()
    {
        header('Content-Type: application/json');

        $dni = $_GET['dni'] ?? null;
        $patientId = $_GET['patient_id'] ?? null; // Para edición futura

        // Si no hay DNI, es válido (campo vacío)
        if (!$dni) {
            echo json_encode(true);
            return;
        }

        $existingPatient = $this->patientModel->findByDni($dni);

        // Si no existe, es válido
        if (!$existingPatient) {
            echo json_encode(true);
            return;
        }

        // Si existe, pero es el mismo paciente (edición futura), es válido
        if ($patientId && $existingPatient['patient_id'] == $patientId) {
            echo json_encode(true);
            return;
        }

        // Si existe y es otro paciente, no es válido
        echo json_encode(false);
    }

    /**
     * Activa o desactiva un paciente (soft delete)
     */
    /**
     * Activa o desactiva un paciente (vía AJAX)
     */
    public function toggle()
    {
        Middleware::auth();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID de paciente inválido']);
            return;
        }

        // Obtener paciente actual
        $patient = $this->patientModel->find($id);

        if (!$patient) {
            echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
            return;
        }

        // Invertir estado
        $newState = $patient['is_active'] == 1 ? 0 : 1;

        // Actualizar
        $updated = $this->patientModel->update($id, ['is_active' => $newState]);

        if ($updated) {
            $message = $newState == 1
                ? 'Paciente activado correctamente'
                : 'Paciente desactivado correctamente';
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado']);
        }
    }
}
