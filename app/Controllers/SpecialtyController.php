<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Specialty;
use App\Core\Auth;
use App\Core\Middleware;

class SpecialtyController extends Controller
{
    private $specialtyModel;

    public function __construct()
    {
        // Se requiere estar autenticado para cualquier acción
        Middleware::auth();
        $this->specialtyModel = new Specialty();
    }

    /**
     * Muestra el listado de especialidades
     */
    public function index()
    {
        $specialties = $this->specialtyModel->all(); // Mostrar TODAS las especialidades
        $this->renderWithLayout('specialties/index', [
            'pageTitle' => 'Gestión de Especialidades',
            'specialties' => $specialties,
            'pageScripts' => ["js/modules/specialties/datatable-specialties.js", "js/modules/specialties/modal-specialties.js"]
        ]);
    }

    /**
     * Verifica si el nombre ya existe (AJAX)
     */
    public function checkName()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $id = $_POST['id'] ?? null;

        $exists = false;
        $specialty = $this->specialtyModel->findByName($name);

        if ($specialty) {
            // Si es edición, verificar que no sea el mismo ID
            if ($id) {
                if ($specialty['specialty_id'] != $id) {
                    $exists = true;
                }
            } else {
                $exists = true;
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists]);
    }

    /**
     * Obtiene los datos de una especialidad (AJAX)
     */
    public function show()
    {
        $id = $_GET['id'] ?? null;
        $specialty = $this->specialtyModel->find($id);

        if ($specialty) {
            $this->jsonResponse(true, 'Datos recuperados', $specialty);
        } else {
            $this->jsonResponse(false, 'Especialidad no encontrada');
        }
    }

    /**
     * Guarda una nueva especialidad
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/especialidades');
            return;
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $this->handleError('El nombre es obligatorio');
            return;
        }

        if ($this->specialtyModel->findByName($name)) {
            $this->handleError('La especialidad ya existe');
            return;
        }

        $saved = $this->specialtyModel->create(['name' => $name]);

        if ($saved) {
            $this->handleSuccess('Especialidad creada correctamente');
        } else {
            $this->handleError('Error al guardar la especialidad');
        }
    }

    /**
     * Actualiza una especialidad existente
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/especialidades');
            return;
        }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');

        if (!$id || empty($name)) {
            $this->handleError('Datos incompletos');
            return;
        }

        // Validar duplicados excluyendo el propio
        $existing = $this->specialtyModel->findByName($name);
        if ($existing && $existing['specialty_id'] != $id) {
            $this->handleError('Ya existe otra especialidad con este nombre');
            return;
        }

        $updated = $this->specialtyModel->update($id, ['name' => $name]);

        if ($updated) {
            $this->handleSuccess('Especialidad actualizada correcta');
        } else {
            $this->handleError('Error al actualizar');
        }
    }

    /**
     * Alternar estado (Activar/Desactivar) - Antes Eliminar
     */
    public function toggle()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/especialidades');
            return;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->handleError('ID no válido');
            return;
        }

        $specialty = $this->specialtyModel->find($id);
        if (!$specialty) {
            $this->handleError('Especialidad no encontrada');
            return;
        }

        // Invertir estado (Si es 1, pasa a 0; si es 0, pasa a 1)
        $newState = $specialty['is_active'] == 1 ? 0 : 1;
        $updated = $this->specialtyModel->update($id, ['is_active' => $newState]);

        if ($updated) {
            $message = $newState == 1 ? 'Especialidad activada correctamente' : 'Especialidad desactivada correctamente';
            $this->handleSuccess($message);
        } else {
            $this->handleError('Error al cambiar el estado');
        }
    }

    // Helper functions para respuestas consistentes
    private function handleSuccess($message)
    {
        if ($this->isAjax()) {
            $this->jsonResponse(true, $message);
        } else {
            $_SESSION['message'] = $message;
            $_SESSION['icon'] = 'success';
            $this->redirect('/especialidades');
        }
    }

    private function handleError($message)
    {
        if ($this->isAjax()) {
            $this->jsonResponse(false, $message);
        } else {
            $_SESSION['message'] = $message;
            $_SESSION['icon'] = 'error';
            $this->redirect('/especialidades');
        }
    }

    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    private function jsonResponse($success, $message, $data = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
