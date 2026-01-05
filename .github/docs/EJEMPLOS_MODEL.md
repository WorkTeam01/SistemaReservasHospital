# Ejemplos de Uso - Model Mejorado

## 📚 Métodos CRUD Genéricos Disponibles

El `Model.php` mejorado incluye métodos CRUD que funcionan automáticamente con cualquier tabla.

---

## 🔐 Sistema de Autenticación

### Ejemplo Completo: Módulo de Login con CSRF

#### 1. Controlador de Autenticación

```php
<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware;
use App\Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Mostrar formulario de login
    public function showLogin(): void
    {
        Middleware::guest();  // Solo usuarios no autenticados
        $csrfToken = Auth::generateCsrfToken();

        $this->render('auth/login', [
            'pageTitle' => 'Iniciar Sesión',
            'csrfToken' => $csrfToken
        ]);
    }

    // Procesar login
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Validar CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Auth::validateCsrfToken($csrfToken)) {
            $_SESSION['message'] = 'Token de seguridad inválido';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
            return;
        }

        // Sanitizar datos
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validar campos
        if (empty($email) || empty($password)) {
            $_SESSION['message'] = 'Email y contraseña son obligatorios';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
            return;
        }

        // Buscar usuario activo
        $user = $this->userModel->findByEmail($email);

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user);
            $_SESSION['welcome_user'] = $user['name'];
            $this->redirect('/dashboard');
        } else {
            $_SESSION['message'] = 'Credenciales incorrectas';
            $_SESSION['icon'] = 'error';
            $this->redirect('/login');
        }
    }

    // Cerrar sesión
    public function logout(): void
    {
        Middleware::auth();
        Auth::logout();
        $_SESSION['message'] = 'Has cerrado sesión correctamente';
        $_SESSION['icon'] = 'success';
        $this->redirect('/login');
    }
}
```

#### 2. Modelo de Usuario

```php
<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    // No define $table porque usa queries personalizadas

    // Buscar usuario activo por email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return $this->query($sql, ['email' => $email])->fetch();
    }

    // Buscar usuario incluyendo inactivos (para mensajes específicos)
    public function findByEmailIncludingInactive($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->query($sql, ['email' => $email])->fetch();
    }

    // Buscar usuario por ID
    public function findById($userId)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id AND is_active = 1";
        return $this->query($sql, ['user_id' => $userId])->fetch();
    }
}
```

#### 3. Vista de Login

```php
<!-- views/auth/login.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $pageTitle ?? 'Login'; ?></title>
    <!-- CSS aquí -->
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body">
                <form id="loginForm" action="<?= URL_BASE; ?>/login" method="post">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">

                    <!-- Email -->
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" 
                               placeholder="Email" required>
                    </div>

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" 
                               placeholder="Contraseña" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-block">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- JS aquí -->
</body>
</html>
```

#### 4. Rutas de Autenticación

```php
// routes/web.php
$router->get('/login', function () {
    (new \App\Controllers\AuthController())->showLogin();
});

$router->post('/login', function () {
    (new \App\Controllers\AuthController())->login();
});

$router->get('/logout', function () {
    (new \App\Controllers\AuthController())->logout();
});
```

---

---

## 👤 Datos de Usuario Automáticos en Vistas

### Sistema Automático de Usuario

**El sistema automáticamente proporciona datos del usuario autenticado en todas las vistas con layout.**

Cuando usas `renderWithLayout()`, estas variables están disponibles automáticamente:

- `$userName` - Nombre del usuario autenticado
- `$userRole` - Rol del usuario (admin, doctor, receptionist)

#### Ejemplo en el Controlador

```php
public function index()
{
    // NO necesitas pasar userName y userRole manualmente
    $data = [
        'pageTitle' => 'Gestión de Pacientes',
        'pageStyles' => ['css/modules/patients/patients.css'],
        'pageScripts' => ['js/modules/patients/patient-validation.js'],
        'patients' => $this->patientModel->all()
    ];

    // renderWithLayout automáticamente agrega userName y userRole
    $this->renderWithLayout('patients/index', $data);
}
```

#### Uso en Header

```php
<!-- views/layouts/header.php -->
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span class="d-none d-md-inline mr-2"><?= $userName; ?></span>
        <i class="fas fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="user-header">
            <img src="<?= URL_BASE ?>/img/user-default.jpg" 
                 class="img-circle" alt="User Image">
            <p>
                <?= $userName; ?>
                <small><?= ucfirst($userRole); ?></small>
            </p>
        </li>
        <li class="user-footer">
            <a href="<?= URL_BASE; ?>/perfil" class="btn btn-default btn-flat">
                Perfil
            </a>
            <a href="<?= URL_BASE; ?>/logout" class="btn btn-default btn-flat float-right">
                Cerrar Sesión
            </a>
        </li>
    </ul>
</li>
```

#### Uso en Vistas

```php
<!-- views/dashboard/admin.php -->
<section class="content-header">
    <h1>Bienvenido, <?= $userName; ?></h1>
    <p>Rol: <?= ucfirst($userRole); ?></p>
</section>

<section class="content">
    <!-- Tu contenido aquí -->
</section>
```

### Cómo Funciona Internamente

```php
// En app/Core/Controller.php
protected function renderWithLayout($view, $data = [])
{
    // Obtener datos del usuario autenticado
    $user = Auth::user();
    
    // Agregar userName y userRole automáticamente
    if (!isset($data['userName'])) {
        $data['userName'] = $user['name'] ?? 'Usuario';
    }
    if (!isset($data['userRole'])) {
        $data['userRole'] = $user['role'] ?? 'Usuario';
    }
    
    // Continuar con el renderizado...
}
```

### Sobrescribir Datos de Usuario (Opcional)

Si necesitas mostrar datos de otro usuario en una vista específica:

```php
public function showProfile($userId)
{
    $profileUser = $this->userModel->find($userId);
    
    $this->renderWithLayout('users/profile', [
        'pageTitle' => 'Perfil de Usuario',
        // Sobrescribir userName para mostrar el perfil de otro usuario
        'profileName' => $profileUser['name'],
        'profileRole' => $profileUser['role'],
        // $userName y $userRole siguen siendo del usuario autenticado
    ]);
}
```

---

## 🎯 Ejemplo 1: Modelo de Pacientes

```php
<?php
namespace App\Models;
use App\Core\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Ahora tienes acceso automático a:
    // - all()
    // - find($id)
    // - create($data)
    // - update($id, $data)
    // - delete($id)
    // - count()
    // - where($conditions)
    // - findWhere($conditions)
}
```

---

## 📝 Uso en Controlador

### Obtener todos los pacientes

```php
$patientModel = new Patient();
$patients = $patientModel->all();
// SELECT * FROM patients
```

### Buscar por ID

```php
$patient = $patientModel->find(5);
// SELECT * FROM patients WHERE patient_id = 5
```

### Crear paciente

```php
$data = [
    'name' => 'Juan',
    'last_name' => 'Pérez',
    'email' => 'juan@example.com',
    'phone' => '123456789'
];
$patientModel->create($data);
// INSERT INTO patients (name, last_name, email, phone) VALUES (...)
```

### Actualizar paciente

```php
$data = [
    'phone' => '987654321',
    'email' => 'nuevoemail@example.com'
];
$patientModel->update(5, $data);
// UPDATE patients SET phone = '987654321', email = '...' WHERE patient_id = 5
```

### Eliminar paciente

```php
$patientModel->delete(5);
// DELETE FROM patients WHERE patient_id = 5
```

### Contar registros

```php
$total = $patientModel->count();
// SELECT COUNT(*) as total FROM patients
```

### Buscar con condiciones

```php
$activos = $patientModel->where(['is_active' => 1]);
// SELECT * FROM patients WHERE is_active = 1

$patient = $patientModel->findWhere(['email' => 'juan@example.com']);
// SELECT * FROM patients WHERE email = 'juan@example.com' LIMIT 1
```

---

## 🎨 pageStyles y pageScripts

### En el Controlador

```php
public function index()
{
    $data = [
        'pageTitle' => 'Gestión de Pacientes',
        'pageStyles' => [
            'css/modules/patients/patients.css',
            'css/plugins/datatables/datatables.min.css'
        ],
        'pageScripts' => [
            'js/modules/patients/patient-validation.js',
            'js/plugins/datatables/datatables.min.js'
        ],
        'patients' => $this->patientModel->all()
    ];

    $this->renderWithLayout('patients/index', $data);
}
```

### Resultado en HTML

**Header:**

```html
<head>
  <title>Gestión de Pacientes</title>
  <!-- CSS globales -->
  <link rel="stylesheet" href=".../bootstrap.min.css" />
  <link rel="stylesheet" href=".../adminlte.min.css" />

  <!-- CSS específicos de la página -->
  <link rel="stylesheet" href=".../css/modules/patients/patients.css" />
  <link rel="stylesheet" href=".../css/plugins/datatables/datatables.min.css" />
</head>
```

**Footer:**

```html
    <!-- JS globales -->
    <script src=".../jquery.min.js"></script>
    <script src=".../bootstrap.bundle.min.js"></script>
    <script src=".../adminlte.min.js"></script>

    <!-- JS específicos de la página -->
    <script src=".../js/modules/patients/patient-validation.js"></script>
    <script src=".../js/plugins/datatables/datatables.min.js"></script>
</body>
```

---

## 🔧 Ejemplo Completo: Módulo de Usuarios

### 1. Modelo

```php
// app/Models/User.php
<?php
namespace App\Models;
use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    public function getAdmins()
    {
        return $this->where(['role_id' => 1, 'is_active' => 1]);
    }

    public function getDoctors()
    {
        return $this->where(['role_id' => 2, 'is_active' => 1]);
    }
}
```

### 2. Controlador

```php
// app/Controllers/UserController.php
<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Gestión de Usuarios',
            'pageStyles' => ['css/modules/users/users.css'],
            'pageScripts' => ['js/modules/users/users.js'],
            'users' => $this->userModel->all()
        ];

        $this->renderWithLayout('users/index', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'role_id' => $_POST['role_id']
            ];

            $this->userModel->create($data);
            $this->redirect('/usuarios?success=1');
        }

        $this->renderWithLayout('users/create');
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];

            $this->userModel->update($id, $data);
            $this->redirect('/usuarios?updated=1');
        }

        $user = $this->userModel->find($id);
        $this->renderWithLayout('users/edit', ['user' => $user]);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        $this->redirect('/usuarios?deleted=1');
    }
}
```

### 3. Ruta

```php
// routes/web.php
$router->get('/usuarios', function() {
    Middleware::auth();
    (new \App\Controllers\UserController())->index();
});

$router->get('/usuarios/crear', function() {
    Middleware::admin();
    (new \App\Controllers\UserController())->create();
});

$router->post('/usuarios/crear', function() {
    Middleware::admin();
    (new \App\Controllers\UserController())->create();
});
```

### 4. Vista

```php
<!-- views/users/index.php -->
<section class="content-header">
    <h1>Gestión de Usuarios</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="<?= URL_BASE; ?>/usuarios/crear" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['user_id']; ?></td>
                            <td><?= $user['name']; ?></td>
                            <td><?= $user['email']; ?></td>
                            <td><?= $user['role_id']; ?></td>
                            <td>
                                <a href="<?= URL_BASE; ?>/usuarios/editar/<?= $user['user_id']; ?>"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
```

### 5. CSS del Módulo

```css
/* public/css/modules/users/users.css */
.table tbody tr:hover {
  background-color: #f8f9fa;
  cursor: pointer;
}

.btn-group-actions {
  display: flex;
  gap: 5px;
}
```

### 6. JS del Módulo

```javascript
// public/js/modules/users/users.js
$(document).ready(function () {
  console.log("Users module loaded");

  // Inicializar DataTable si está disponible
  if ($.fn.DataTable) {
    $(".table").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
      },
    });
  }
});
```

---

## 🏥 Ejemplo Completo: Módulo de Pacientes

### Modelo Patient con Métodos Personalizados

```php
<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Heredados: all(), find(), create(), update(), delete(), count(), where()

    /**
     * Verificar si un DNI ya existe en la base de datos
     * @param string $dni DNI a verificar
     * @param int|null $excludeId ID del paciente a excluir (para edición)
     * @return bool True si existe, False si no existe
     */
    public function dniExists(string $dni, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            // Para edición: excluir el registro actual
            $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                    WHERE dni = :dni AND {$this->primaryKey} != :id";
            $stmt = $this->query($sql, ['dni' => $dni, 'id' => $excludeId]);
        } else {
            // Para creación: buscar cualquier coincidencia
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE dni = :dni";
            $stmt = $this->query($sql, ['dni' => $dni]);
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Obtener solo pacientes activos
     * @return array Lista de pacientes activos
     */
    public function getActivePatients(): array
    {
        return $this->where(['is_active' => 1]);
    }

    /**
     * Buscar pacientes por nombre o DNI
     * @param string $search Término de búsqueda
     * @return array Pacientes encontrados
     */
    public function searchPatients(string $search): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (name LIKE :search OR last_name LIKE :search OR dni LIKE :search) 
                AND is_active = 1
                ORDER BY name ASC";
        
        $stmt = $this->query($sql, ['search' => "%{$search}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener historial de citas de un paciente
     * @param int $patientId ID del paciente
     * @return array Citas del paciente
     */
    public function getAppointmentHistory(int $patientId): array
    {
        $sql = "SELECT 
                    a.appointment_id, 
                    a.date_time, 
                    a.status,
                    CONCAT(u.name, ' ', u.last_name) as doctor_name,
                    s.name as specialty_name
                FROM appointments a
                INNER JOIN users u ON a.doctor_id = u.user_id
                INNER JOIN specialties s ON a.specialty_id = s.specialty_id
                WHERE a.patient_id = :patient_id
                ORDER BY a.date_time DESC";
        
        $stmt = $this->query($sql, ['patient_id' => $patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

### Controlador PatientController

```php
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

    /**
     * Listar todos los pacientes
     */
    public function index()
    {
        Middleware::auth();

        $patients = $this->patientModel->getActivePatients();

        $this->renderWithLayout('patients/index', [
            'pageTitle' => 'Lista de Pacientes',
            'patients' => $patients,
            'pageScripts' => ['js/modules/patients/datatable-patients.js']
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function showCreate()
    {
        Middleware::auth();

        $this->renderWithLayout('patients/create', [
            'pageTitle' => 'Registrar Nuevo Paciente',
            'pageScripts' => ['js/modules/patients/patient-validation.js']
        ]);
    }

    /**
     * Guardar nuevo paciente
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

        // Sanitizar y validar inputs
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'dni' => trim($_POST['dni'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
            'birth_date' => !empty($_POST['birth_date']) ? $_POST['birth_date'] : null,
            'address' => !empty($_POST['address']) ? trim($_POST['address']) : null,
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
     * Validación remota AJAX para DNI único
     * Usado por jQuery Validate
     */
    public function checkDni()
    {
        header('Content-Type: application/json');
        
        $dni = $_POST['dni'] ?? '';
        $exists = $this->patientModel->dniExists($dni);
        
        // jQuery Validate espera 'true' para válido, 'false' para inválido
        echo json_encode(!$exists);
        exit;
    }

    /**
     * Buscar pacientes vía AJAX
     */
    public function search()
    {
        header('Content-Type: application/json');
        Middleware::auth();

        $search = $_GET['q'] ?? '';
        $patients = $this->patientModel->searchPatients($search);

        echo json_encode($patients);
        exit;
    }

    /**
     * Ver detalle de un paciente
     */
    public function show($id)
    {
        Middleware::auth();

        $patient = $this->patientModel->find($id);

        if (!$patient) {
            $_SESSION['message'] = 'Paciente no encontrado';
            $_SESSION['icon'] = 'error';
            $this->redirect('/pacientes');
            return;
        }

        $appointments = $this->patientModel->getAppointmentHistory($id);

        $this->renderWithLayout('patients/show', [
            'pageTitle' => 'Detalle del Paciente',
            'patient' => $patient,
            'appointments' => $appointments
        ]);
    }
}
```

### Validación JavaScript con jQuery Validate

```javascript
// public/js/modules/patients/patient-validation.js

$(document).ready(function () {
    $('#formPatient').validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            dni: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 20,
                // ✅ Validación remota asíncrona
                remote: {
                    url: BASE_URL + '/pacientes/check-dni',
                    type: 'POST',
                    data: {
                        dni: function() {
                            return $('#dni').val();
                        }
                    }
                }
            },
            phone: {
                required: true,
                minlength: 7,
                maxlength: 20
            },
            email: {
                email: true,
                maxlength: 100
            },
            birth_date: {
                required: true,
                date: true
            }
        },
        messages: {
            name: {
                required: 'Por favor ingrese el nombre del paciente',
                minlength: 'El nombre debe tener al menos 2 caracteres',
                maxlength: 'El nombre no debe exceder 100 caracteres'
            },
            last_name: {
                required: 'Por favor ingrese el apellido del paciente',
                minlength: 'El apellido debe tener al menos 2 caracteres'
            },
            dni: {
                required: 'Por favor ingrese el DNI/CI',
                digits: 'El DNI debe contener solo números',
                remote: 'Este DNI ya está registrado en el sistema'
            },
            phone: {
                required: 'Por favor ingrese el teléfono',
                minlength: 'El teléfono debe tener al menos 7 dígitos'
            },
            email: {
                email: 'Por favor ingrese un email válido'
            },
            birth_date: {
                required: 'Por favor ingrese la fecha de nacimiento',
                date: 'Formato de fecha inválido'
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // ✅ Submit asíncrono (evita warning en consola)
        submitHandler: function(form) {
            form.submit();
        }
    });
});
```

**Nota importante**: El uso de `submitHandler` evita el warning "Synchronous XMLHttpRequest on the main thread is deprecated" que aparecía con la validación remota.

### Rutas en web.php

```php
// Listado
$router->get('/pacientes', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->index();
});

// Formulario creación
$router->get('/pacientes/crear', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->showCreate();
});

// Guardar paciente
$router->post('/pacientes/store', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->store();
});

// Validación AJAX DNI
$router->post('/pacientes/check-dni', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->checkDni();
});

// Búsqueda AJAX
$router->get('/pacientes/search', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->search();
});

// Ver detalle
$router->get('/pacientes/ver/:id', function ($id) {
    Middleware::auth();
    $controller = new PatientController();
    $controller->show($id);
});
```

---

## ✅ Ventajas del Nuevo Sistema

1. ✅ **Menos código repetitivo** - CRUD genérico en Model base
2. ✅ **CSS/JS por módulo** - Carga solo lo necesario
3. ✅ **Títulos personalizados** - pageTitle dinámico
4. ✅ **Queries personalizadas** - Método `query()` sigue disponible
5. ✅ **Flexible** - Puedes sobrescribir cualquier método
6. ✅ **Seguro** - Prepared statements en todos los métodos

---

## 🎯 Cuándo usar cada método

| Método                   | Cuándo Usarlo                           |
| ------------------------ | --------------------------------------- |
| `all()`                  | Obtener todos los registros             |
| `find($id)`              | Buscar por ID                           |
| `create($data)`          | Insertar nuevo registro                 |
| `update($id, $data)`     | Actualizar registro                     |
| `delete($id)`            | Eliminar registro                       |
| `count()`                | Contar registros                        |
| `where($conditions)`     | Buscar con múltiples condiciones        |
| `findWhere($conditions)` | Buscar un solo registro con condiciones |
| `query($sql, $params)`   | Queries personalizadas complejas        |

---

_Última actualización: Enero 2025_
