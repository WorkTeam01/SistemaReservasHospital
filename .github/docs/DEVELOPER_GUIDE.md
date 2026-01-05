# Guía de Desarrollo - Sistema de Reservas Hospital

## 📁 Estructura del Proyecto

```
SistemaReservasHospital/
├── app/                    # Núcleo de la aplicación
│   ├── Config/            # Configuraciones (Database, env, config)
│   ├── Controllers/       # Controladores (lógica de negocio)
│   ├── Core/             # Clases base (Router, Controller, Model, Middleware, Autoloader)
│   └── Models/           # Modelos (interacción con BD)
├── routes/               # Definición de rutas (web.php)
├── views/                # Vistas HTML/PHP
│   ├── dashboard/        # Vistas del dashboard
│   ├── errors/          # Páginas de error (404, 403, 500)
│   └── layouts/         # Plantillas reutilizables (header, footer, sidebar)
├── public/              # Directorio público (accesible desde web)
│   ├── index.php       # Punto de entrada único
│   ├── css/            # Estilos CSS
│   │   ├── core/       # Estilos del sistema
│   │   ├── lib/        # Librerías CSS (Bootstrap, AdminLTE, FontAwesome)
│   │   ├── modules/    # Estilos por módulo
│   │   └── plugins/    # Estilos de plugins adicionales (DataTables, Select2, etc.)
│   ├── js/             # JavaScript
│   │   ├── core/       # Scripts del sistema
│   │   ├── lib/        # Librerías JS (jQuery, Bootstrap, AdminLTE)
│   │   ├── modules/    # Scripts por módulo
│   │   └── plugins/    # Scripts de plugins adicionales (DataTables, Select2, etc.)
│   └── img/            # Imágenes y recursos
├── vendor/              # Librerías de terceros (TCPDF, PHPMailer, etc.)
├── database.sql         # Esquema de base de datos
├── queries.sql          # Consultas SQL de referencia
├── .env                # Variables de entorno (NO subir a Git)
├── .env.example        # Plantilla de variables de entorno
└── README.md           # Documentación del proyecto
```

---

## 🏗️ Arquitectura MVC

### Patrón MVC (Model-View-Controller)

#### **Model (Modelo)**

- Ubicación: `app/Models/`
- Responsabilidad: Interactuar con la base de datos
- Convención de nombres: `Dashboard.php`, `User.php`, `Patient.php`
- Extiende de: `App\Core\Model`

**Métodos CRUD Disponibles:**

Todos los modelos heredan estos métodos automáticamente:

- `all()` - Obtener todos los registros
- `find($id)` - Buscar por ID
- `create($data)` - Crear registro
- `update($id, $data)` - Actualizar registro
- `delete($id)` - Eliminar registro
- `count()` - Contar registros
- `where($conditions)` - Buscar con condiciones
- `findWhere($conditions)` - Buscar un registro con condiciones
- `query($sql, $params)` - Queries personalizadas

**Ejemplo con CRUD genérico:**

```php
<?php
namespace App\Models;

use App\Core\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Ya tienes disponible: all(), find(), create(), update(), delete()

    public function getActivePatients()
    {
        return $this->where(['is_active' => 1]);
    }
}
```

**Ejemplo con queries personalizadas:**

```php
<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Dashboard extends Model
{
    // No define $table porque usa queries personalizadas

    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE is_active = 1";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
```

#### **View (Vista)**

- Ubicación: `views/`
- Responsabilidad: Presentar la información al usuario
- Formato: Archivos PHP con HTML
- **NO debe contener lógica de negocio**

**Ejemplo:**

```php
<!-- views/dashboard/admin.php -->
<h1>Total de Usuarios: <?= $totalUsers; ?></h1>
```

#### **Controller (Controlador)**

- Ubicación: `app/Controllers/`
- Responsabilidad: Coordinar entre Model y View
- Convención de nombres: `DashboardController.php`, `UserController.php`
- Extiende de: `App\Core\Controller`

**Ejemplo:**

```php
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
        // Obtener datos del modelo
        $data = [
            'pageTitle' => 'Dashboard - Sistema de Reservas',
            'pageStyles' => ['css/modules/dashboard/dashboard.css'],
            'pageScripts' => ['js/modules/dashboard/dashboard.js'],
            'totalUsers' => $this->dashboardModel->getTotalUsers(),
            'totalPatients' => $this->dashboardModel->getTotalPatients()
        ];

        // Renderizar vista con layout
        $this->renderWithLayout('dashboard/admin', $data);
    }
}
```

---

## 🛣️ Sistema de Rutas

### Definición de Rutas (`routes/web.php`)

```php
<?php
use App\Core\Middleware;

// Ruta principal
$router->get('/', function () {
    $controller = new \App\Controllers\DashboardController();
    $controller->index();
});

// Ruta con middleware de autenticación
$router->get('/usuarios', function () {
    Middleware::auth(); // Verificar que el usuario esté logueado
    $controller = new \App\Controllers\UserController();
    $controller->index();
});

// Ruta POST
$router->post('/usuarios/crear', function () {
    Middleware::auth();
    $controller = new \App\Controllers\UserController();
    $controller->store();
});
```

### Tipos de Rutas

- `$router->get($path, $callback)` - Peticiones GET
- `$router->post($path, $callback)` - Peticiones POST

---

## 🎨 Sistema de Vistas y Layouts

### Renderizar Vistas

#### Opción 1: Vista simple (sin layout)

```php
$this->render('auth/login', $data);
```

#### Opción 2: Vista con layout completo (header, sidebar, footer)

```php
$this->renderWithLayout('dashboard/admin', $data);
```

### CSS y JavaScript Específicos por Vista

Puedes cargar CSS y JS específicos para cada módulo:

```php
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
    'patients' => $patientModel->all()
];

$this->renderWithLayout('patients/index', $data);
```

**Resultado:**

- `pageTitle` se usa en `<title>` del HTML
- `pageStyles` se cargan en `<head>` después de los CSS globales
- `pageScripts` se cargan antes de `</body>` después de los JS globales

### Estructura de Layouts

**Header** (`views/layouts/header.php`):

- Carga CSS globales (Bootstrap, AdminLTE, FontAwesome)
- Carga `pageStyles` específicos de la vista
- Navbar superior
- **Inicia el HTML**

**Sidebar** (`views/layouts/sidebar.php`):

- Menú lateral de navegación
- **Abre `<main class="content-wrapper">`**

**Footer** (`views/layouts/footer.php`):

- **Cierra `</main>`**
- Carga JavaScript globales (jQuery, Bootstrap, AdminLTE)
- Carga `pageScripts` específicos de la vista
- **Cierra el HTML**

**Vista** (`views/dashboard/admin.php`):

- Solo contiene el contenido específico de la página
- NO incluye `<html>`, `<head>`, ni `<body>`

---

## 🔐 Middleware

### Tipos de Middleware

#### `Middleware::auth()`

Verifica que el usuario esté autenticado. Si no, redirige al login.

```php
$router->get('/dashboard', function () {
    Middleware::auth();
    // ... resto del código
});
```

#### `Middleware::admin()`

Verifica que el usuario sea administrador.

```php
$router->get('/usuarios', function () {
    Middleware::admin();
    // ... resto del código
});
```

#### `Middleware::guest()`

Solo permite acceso a usuarios NO autenticados (útil para login).

```php
$router->get('/login', function () {
    Middleware::guest();
    // ... resto del código
});
```

---

## 🔐 Sistema de Autenticación (Auth)

### Clase Auth (`app/Core/Auth.php`)

La clase `Auth` proporciona métodos estáticos para gestionar la autenticación de usuarios.

#### Métodos Disponibles

##### `Auth::generateCsrfToken()`

Genera un token CSRF único para proteger contra ataques CSRF.

```php
$csrfToken = Auth::generateCsrfToken();
// En el formulario HTML:
<input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
```

##### `Auth::validateCsrfToken($token)`

Valida un token CSRF contra el almacenado en sesión.

```php
$csrfToken = $_POST['csrf_token'] ?? '';
if (!Auth::validateCsrfToken($csrfToken)) {
    $_SESSION['message'] = 'Token de seguridad inválido';
    $_SESSION['icon'] = 'error';
    $this->redirect('/login');
    return;
}
```

##### `Auth::login($user)`

Inicia sesión para un usuario. Regenera el ID de sesión por seguridad.

```php
// $user debe ser un array con: user_id, role, name
Auth::login($user);
```

##### `Auth::check()`

Verifica si hay un usuario autenticado.

```php
if (Auth::check()) {
    // Usuario está logueado
}
```

##### `Auth::user()`

Obtiene los datos del usuario actual de la sesión.

```php
$user = Auth::user();
// Retorna: ['id' => ..., 'name' => ..., 'role' => ...]
echo $user['name'];  // Nombre del usuario
echo $user['role'];  // Rol del usuario (admin, doctor, receptionist)
```

##### `Auth::logout()`

Cierra la sesión del usuario de forma segura.

- Limpia todas las variables de sesión (`$_SESSION = []`)
- Destruye la cookie de sesión
- Destruye la sesión del servidor
- Inicia una nueva sesión limpia para mensajes de redirección

```php
Auth::logout();
$_SESSION['message'] = 'Has cerrado sesión correctamente';
$this->redirect('/login');
```

### Flujo de Login Completo

```php
// 1. Mostrar formulario de login
public function showLogin(): void
{
    Middleware::guest();  // Solo usuarios no autenticados
    $csrfToken = Auth::generateCsrfToken();
    
    $this->render('auth/login', [
        'pageTitle' => 'Iniciar Sesión',
        'csrfToken' => $csrfToken
    ]);
}

// 2. Procesar login
public function login(): void
{
    // Validar CSRF
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Auth::validateCsrfToken($csrfToken)) {
        $_SESSION['message'] = 'Token de seguridad inválido';
        $_SESSION['icon'] = 'error';
        $this->redirect('/login');
        return;
    }

    // Obtener credenciales
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Buscar usuario
    $user = $this->userModel->findByEmail($email);

    // Verificar contraseña
    if ($user && password_verify($password, $user['password'])) {
        // Login exitoso
        Auth::login($user);
        $_SESSION['welcome_user'] = $user['name'];
        $this->redirect('/dashboard');
    } else {
        $_SESSION['message'] = 'Credenciales incorrectas';
        $_SESSION['icon'] = 'error';
        $this->redirect('/login');
    }
}

// 3. Cerrar sesión
public function logout(): void
{
    Middleware::auth();  // Solo usuarios autenticados
    Auth::logout();
    $_SESSION['message'] = 'Has cerrado sesión correctamente';
    $_SESSION['icon'] = 'success';
    $this->redirect('/login');
}
```

### Protección de Contraseñas

**Siempre usar `password_hash()` y `password_verify()`:**

```php
// Al crear usuario
$data = [
    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
];

// Al verificar login
if (password_verify($password, $user['password'])) {
    // Contraseña correcta
}
```

### Datos de Usuario en Todas las Vistas

**El sistema automáticamente proporciona `$userName` y `$userRole` en todas las vistas con layout:**

```php
// En el controlador (ya no necesitas pasar userName y userRole manualmente)
$this->renderWithLayout('patients/index', [
    'pageTitle' => 'Gestión de Pacientes',
    'patients' => $this->patientModel->all()
]);

// En la vista (header.php automáticamente tiene acceso a)
<?= $userName; ?>  // Nombre del usuario autenticado
<?= $userRole; ?>  // Rol del usuario (admin, doctor, receptionist)
```

El método `renderWithLayout()` en `Controller.php` automáticamente obtiene los datos del usuario con `Auth::user()` y los pasa a todas las vistas.

---

## 📝 Convenciones de Código

### Nombres de Archivos y Clases

✅ **Correcto:**

- `DashboardController.php` - PascalCase para clases
- `Dashboard.php` - PascalCase para modelos
- `admin.php` - snake_case para vistas

❌ **Incorrecto:**

- `dashboardController.php`
- `DashboardModel.php` (el sufijo Model es redundante)
- `Admin.php` (vistas en minúsculas)

### Namespaces

Todos los archivos PHP deben usar namespaces:

```php
<?php
namespace App\Controllers;  // Para controladores
namespace App\Models;       // Para modelos
namespace App\Core;         // Para clases del núcleo
```

### Variables y Métodos

```php
// Variables: camelCase
$totalUsers = 10;
$userName = 'Juan';

// Métodos: camelCase
public function getTotalUsers() { }
public function createAppointment() { }

// Constantes: MAYÚSCULAS
define('URL_BASE', 'http://localhost/app');
```

### Comentarios

```php
/**
 * Obtiene el total de usuarios activos
 *
 * @return int
 */
public function getTotalUsers()
{
    // Implementación
}
```

---

## 🗄️ Base de Datos

### Métodos CRUD del Model

Todos los modelos heredan estos métodos automáticamente:

#### Obtener Registros

```php
// Todos los registros
$patients = $model->all();

// Buscar por ID
$patient = $model->find(5);

// Buscar con condiciones
$activos = $model->where(['is_active' => 1, 'role_id' => 2]);

// Buscar un solo registro
$user = $model->findWhere(['email' => 'admin@example.com']);

// Contar registros
$total = $model->count();
```

#### Crear Registro

```php
$data = [
    'name' => 'Juan Pérez',
    'email' => 'juan@example.com',
    'phone' => '123456789'
];

$model->create($data);
```

#### Actualizar Registro

```php
$data = [
    'phone' => '987654321',
    'email' => 'nuevoemail@example.com'
];

$model->update(5, $data);
```

#### Eliminar Registro

```php
$model->delete(5);
```

### Queries Personalizadas

**Cuando necesites queries complejas, usa el método `query()`:**

```php
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $this->query($sql, ['id' => $userId]);
```

**❌ NO usar directamente PDO:**

```php
// EVITAR ESTO
$stmt = $this->db->prepare($sql);
```

### Prepared Statements

**Siempre usar parámetros preparados:**

```php
// ✅ CORRECTO
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $this->query($sql, ['email' => $email]);

// ❌ INCORRECTO (Vulnerable a SQL Injection)
$sql = "SELECT * FROM users WHERE email = '$email'";
```

---

## 📂 Organización de Archivos CSS/JS

### CSS

```
public/css/
├── core/           # Estilos del sistema
│   ├── style.css
│   └── fonts.css
├── lib/            # Librerías externas
│   ├── bootstrap/
│   ├── adminlte/
│   └── fontawesome/
└── modules/        # Estilos por módulo
    ├── errors/
    │   └── errors.css
    └── dashboard/
        └── dashboard.css
```

### JavaScript

```
public/js/
├── core/           # Scripts del sistema
│   └── app.js
├── lib/            # Librerías externas
│   ├── jquery/
│   ├── bootstrap/
│   └── adminlte/
└── modules/        # Scripts por módulo
    └── dashboard/
        └── dashboard.js
```

---

## 🔄 Flujo de una Petición

1. **Usuario accede a una URL** → `http://localhost/SistemaReservasHospital/public/usuarios`

2. **`index.php`** recibe la petición y carga el autoloader, configuraciones y rutas

3. **Router** busca la ruta en `routes/web.php`

4. **Middleware** verifica permisos (si aplica)

5. **Controlador** se ejecuta:

   - Instancia el modelo
   - Obtiene datos del modelo
   - Pasa datos a la vista

6. **Vista** renderiza el HTML con los datos

7. **Respuesta** se envía al navegador

---

## 🚀 Cómo Crear un Nuevo Módulo

### Ejemplo: Módulo de Pacientes

#### 1. Crear el Modelo

```php
// app/Models/Patient.php
<?php
namespace App\Models;

use App\Core\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Métodos CRUD heredados automáticamente:
    // all(), find($id), create($data), update($id, $data), delete($id)

    public function getActivePatients()
    {
        return $this->where(['is_active' => 1]);
    }

    public function findByEmail($email)
    {
        return $this->findWhere(['email' => $email]);
    }
}
```

#### 2. Crear el Controlador

```php
// app/Controllers/PatientController.php
<?php
namespace App\Controllers;

use App\Core\Controller;
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
        $data = [
            'pageTitle' => 'Gestión de Pacientes',
            'pageStyles' => ['css/modules/patients/patients.css'],
            'pageScripts' => ['js/modules/patients/patient-validation.js'],
            'patients' => $this->patientModel->all()  // Usa método CRUD heredado
        ];
        $this->renderWithLayout('patients/index', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'is_active' => 1
            ];

            $this->patientModel->create($data);  // Método CRUD heredado
            $this->redirect('/pacientes?success=1');
        }

        $this->renderWithLayout('patients/create');
    }
}
```

#### 3. Crear la Vista

```php
// views/patients/index.php
<section class="content-header">
    <h1>Lista de Pacientes</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= $patient['name']; ?></td>
                            <td><?= $patient['phone']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
```

#### 4. Definir la Ruta

```php
// routes/web.php
$router->get('/pacientes', function () {
    Middleware::auth();
    $controller = new \App\Controllers\PatientController();
    $controller->index();
});
```

#### 5. Agregar al Menú

```php
// views/layouts/sidebar.php
<li class="nav-item">
    <a href="<?= URL_BASE; ?>/pacientes" class="nav-link">
        <i class="nav-icon fas fa-hospital-user"></i>
        <p>Pacientes</p>
    </a>
</li>
```

---

## ⚠️ Errores Comunes

### 1. Olvidar el namespace

```php
❌ INCORRECTO:
<?php
class Dashboard extends Model { }

✅ CORRECTO:
<?php
namespace App\Models;
use App\Core\Model;
class Dashboard extends Model { }
```

### 2. Usar rutas relativas incorrectas

```php
❌ INCORRECTO:
require_once 'config.php';

✅ CORRECTO:
require_once __DIR__ . '/../config.php';
```

### 3. No escapar datos en vistas

```php
❌ INCORRECTO:
<h1><?= $user_name ?></h1>

✅ CORRECTO:
<h1><?= htmlspecialchars($user_name); ?></h1>
// O usando la forma corta de PHP:
<h1><?= $user_name; ?></h1> (si confías en el origen)
```

### 4. Mezclar lógica de negocio en vistas

```php
❌ INCORRECTO (en la vista):
<?php
$users = $db->query("SELECT * FROM users")->fetchAll();
foreach ($users as $user) { ... }
?>

✅ CORRECTO:
// En el controlador:
$data['users'] = $model->getAll();
$this->render('users/index', $data);

// En la vista:
<?php foreach ($users as $user): ?>
    ...
<?php endforeach; ?>
```

---

## 🔧 Variables de Entorno

**Archivo `.env`** (NO subir a Git):

```env
DB_HOST=localhost
DB_NAME=hospital_db
DB_USER=root
DB_PASS=
URL_BASE=http://localhost/SistemaReservasHospital/public
```

**Uso en código:**

```php
$dbHost = $_ENV['DB_HOST'];
$urlBase = URL_BASE; // Constante ya definida
```

---

## 📚 Recursos Adicionales

- [Documentación AdminLTE](https://adminlte.io/docs)
- [Bootstrap 4 Docs](https://getbootstrap.com/docs/4.6)
- [PHP PDO](https://www.php.net/manual/es/book.pdo.php)
- [Font Awesome Icons](https://fontawesome.com/icons)

---

## 🤝 Buenas Prácticas

### Desarrollo General

1. ✅ **Siempre usar control de versiones (Git)**
2. ✅ **Comentar código complejo**
3. ✅ **Usar nombres descriptivos para variables y funciones**
4. ✅ **Validar datos de entrada del usuario**
5. ✅ **Probar el código antes de hacer commit**
6. ✅ **Documentar cambios importantes en commits**

### Base de Datos

7. ✅ **Usar prepared statements para SQL** (evitar SQL injection)
8. ✅ **Usar métodos CRUD heredados cuando sea posible** (`all()`, `find()`, `create()`, etc.)
9. ✅ **Solo usar `query()` para queries complejas personalizadas**
10. ✅ **Definir `$table` y `$primaryKey` en modelos CRUD**

### Arquitectura MVC

11. ✅ **Separar la lógica de negocio de la presentación**
12. ✅ **Mantener los controladores delgados, los modelos gordos**
13. ✅ **NO incluir HTML en controladores**
14. ✅ **NO incluir lógica de negocio en vistas**
15. ✅ **Usar `renderWithLayout()` para vistas con menú**
16. ✅ **Usar `render()` solo para páginas sin layout (login, 404)**

### CSS/JS

17. ✅ **Organizar CSS/JS por módulo** (`css/modules/pacientes/`, `js/modules/pacientes/`)
18. ✅ **Usar `pageStyles` y `pageScripts` para cargar assets específicos**
19. ✅ **NO duplicar CSS/JS globales**
20. ✅ **Hacer hard refresh (Ctrl+F5) después de cambios CSS**

### Convenciones de Código

21. ✅ **PascalCase para clases** (`PatientController`, `Patient`)
22. ✅ **camelCase para métodos y variables** (`getTotalUsers`, `$patientModel`)
23. ✅ **snake_case para vistas** (`admin.php`, `index.php`)
24. ✅ **NO usar sufijo "Model"** en nombres de modelos (`Patient`, no `PatientModel`)

---

## 🏥 Módulo de Pacientes - Ejemplo Completo

### Estructura del Módulo

El módulo de pacientes es un ejemplo completo de CRUD con diseño moderno, validación asíncrona y arquitectura RESTful.

#### Rutas RESTful

```php
// routes/web.php

// Listado de pacientes
$router->get('/pacientes', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->index();
});

// Mostrar formulario de creación (GET)
$router->get('/pacientes/crear', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->showCreate();
});

// Procesar creación de paciente (POST)
$router->post('/pacientes/store', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->store();
});

// Validación remota AJAX
$router->post('/pacientes/check-dni', function () {
    Middleware::auth();
    $controller = new PatientController();
    $controller->checkDni();
});
```

**Convención de rutas**:
- `GET /recurso` - Listado
- `GET /recurso/crear` - Formulario de creación
- `POST /recurso/store` - Guardar registro
- `GET /recurso/editar/:id` - Formulario de edición
- `POST /recurso/update/:id` - Actualizar registro
- `POST /recurso/delete/:id` - Eliminar registro

#### Controlador

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

        // Sanitizar inputs
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
}
```

#### Modelo

```php
<?php
namespace App\Models;

use App\Core\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Ya tiene disponibles: all(), find(), create(), update(), delete()

    /**
     * Verificar si un DNI ya existe
     */
    public function dniExists(string $dni, ?int $excludeId = null): bool
    {
        $conditions = ['dni' => $dni];
        
        if ($excludeId) {
            // Para edición: excluir el registro actual
            $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                    WHERE dni = :dni AND {$this->primaryKey} != :id";
            $stmt = $this->query($sql, ['dni' => $dni, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE dni = :dni";
            $stmt = $this->query($sql, ['dni' => $dni]);
        }
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Obtener pacientes activos
     */
    public function getActivePatients(): array
    {
        return $this->where(['is_active' => 1]);
    }
}
```

#### Validación Asíncrona con jQuery Validate

**Problema**: Validación síncrona bloquea el hilo principal del navegador (warning en consola).

**Solución**: Usar `submitHandler` para procesar el formulario de forma asíncrona.

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
                // Validación remota asíncrona
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
            dni: {
                required: 'Por favor ingrese el DNI/CI',
                digits: 'El DNI debe contener solo números',
                remote: 'Este DNI ya está registrado'
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
        // ✅ Procesar submit de forma asíncrona
        submitHandler: function(form) {
            form.submit();
        }
    });
});
```

**Ventajas de este enfoque**:
- ✅ No bloquea el hilo principal del navegador
- ✅ Validación remota funciona correctamente
- ✅ Mensajes de error en tiempo real
- ✅ No genera warnings en consola

#### Vista con Diseño de Dos Columnas

La vista de creación utiliza un **diseño de dos columnas**:
- **Columna izquierda (8/12)**: Formulario principal con cards colapsables
- **Columna derecha (4/12)**: Guía de registro y consejos

```php
<!-- views/patients/create.php -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Principal: Formulario -->
            <div class="col-md-8">
                <form id="formPatient" action="<?= BASE_URL ?>/pacientes/store" method="post">
                    
                    <!-- Card: Datos Básicos -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user"></i> Datos Básicos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Campos del formulario -->
                        </div>
                    </div>

                    <!-- Card: Datos de Contacto -->
                    <div class="card card-primary card-outline">
                        <!-- ... -->
                    </div>

                    <!-- Botones de acción en el footer de la última card -->
                </form>
            </div>

            <!-- Columna Lateral: Guía y Consejos -->
            <div class="col-md-4">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-question-circle"></i> Guía de Registro</h3>
                    </div>
                    <div class="card-body">
                        <!-- Información contextual -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

**Características del diseño**:
- ✅ Cards colapsables para mejor organización
- ✅ Breadcrumb completo para navegación
- ✅ Input groups con iconos de Font Awesome
- ✅ Indicadores visuales de campos obligatorios (*)
- ✅ Guía contextual en la columna lateral
- ✅ Diseño responsive (se apila en móviles)
- ✅ 100% AdminLTE y Bootstrap (sin CSS personalizado)

---

## 🚨 Manejo de Errores

### Sistema de Errores HTTP

El sistema implementa páginas personalizadas para los errores HTTP más comunes:

#### Páginas Disponibles

- **404** - Página no encontrada
- **500** - Error interno del servidor
- **503** - Servicio no disponible

Todas las páginas usan un layout reutilizable (`views/errors/layout.php`) para mantener consistencia visual.

### Clase ErrorHandler

**Ubicación**: `app/Core/ErrorHandler.php`

Es una clase helper que proporciona métodos convenientes para mostrar páginas de error. **No captura errores automáticamente**, solo centraliza la lógica de visualización.

#### Métodos Disponibles

```php
// Mostrar error 404
ErrorHandler::notFound();

// Mostrar error 500
ErrorHandler::serverError();

// Mostrar error 503
ErrorHandler::serviceUnavailable();

// Error genérico
ErrorHandler::showError(403);
```

### Uso en el Router

El Router usa `ErrorHandler::notFound()` cuando no encuentra una ruta:

```php
// En app/Core/Router.php
if ($callback) {
    // Ejecutar callback
} else {
    ErrorHandler::notFound();
}
```

### Ejemplo de Uso

```php
public function show($id)
{
    $patient = $this->patientModel->find($id);
    
    if (!$patient) {
        // Mostrar 404 si no existe
        ErrorHandler::notFound();
    }
    
    $this->renderWithLayout('patients/show', [
        'patient' => $patient
    ]);
}
```

### Manejo Manual de Errores

Para errores en operaciones críticas, usa try-catch:

```php
public function processPayment()
{
    try {
        $result = $this->paymentService->process();
        
        if (!$result) {
            throw new \Exception("Payment failed");
        }
        
        $_SESSION['message'] = 'Pago procesado';
        $this->redirect('/payments');
        
    } catch (\Exception $e) {
        // Log del error
        error_log("Error en pago: " . $e->getMessage());
        
        // Mostrar página de error
        ErrorHandler::serverError();
    }
}
```

### Modo Desarrollo vs Producción

**Configurar en `.env`**:
```env
APP_ENV=development  # Muestra detalles técnicos en errores 500
# APP_ENV=production # Oculta detalles técnicos
```

**Comportamiento**:
- **Desarrollo**: Muestra mensaje, archivo y línea de error en página 500
- **Producción**: Solo muestra mensaje genérico amigable al usuario

### Documentación Completa

📘 **[Guía Completa de Manejo de Errores](ERROR_HANDLING.md)** - Incluye:
- Arquitectura del sistema de errores
- Layout reutilizable
- Personalización de páginas
- Ejemplos de implementación

---

## 📖 Documentación Adicional

- 📘 [Ejemplos de Uso del Model](EJEMPLOS_MODEL.md)
- 🔐 [Auth System - Guía Rápida](AUTH_QUICK_REFERENCE.md)
- 🚨 [Sistema de Manejo de Errores](ERROR_HANDLING.md)
- 📋 [Changelog](CHANGELOG.md)
- 📚 [README del Proyecto](../../README.md)

---

_Última actualización: Enero 2025_
