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
        'pageScripts' => ['js/modules/patients/patients.js'],
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
            'js/modules/patients/patients.js',
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
    <script src=".../js/modules/patients/patients.js"></script>
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

_Última actualización: Diciembre 2025_
