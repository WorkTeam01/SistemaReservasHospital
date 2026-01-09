# CLAUDE.md

Este archivo proporciona orientación a Claude Code (claude.ai/code) cuando trabaja con código en este repositorio.

## Descripción del Proyecto

Sistema de Reservas Hospitalarias construido con **PHP 8.2+ Vanilla** usando arquitectura **MVC personalizada**, frontend con **AdminLTE 3**, y **PDO** para interacciones con base de datos. El sistema gestiona citas médicas, pacientes, doctores y especialidades para clínicas y hospitales.

## Entorno de Desarrollo

### Ejecutar la Aplicación

- **Servidor**: Apache (XAMPP/LAMP)
- **URL de Acceso**: `http://localhost/SistemaReservasHospital/public`
- **Punto de Entrada**: `public/index.php` (front controller)

### Configuración de Base de Datos

```bash
# Importar esquema de base de datos
mysql -u root -p hospital_db < database.sql

# Configurar entorno
cp .env.example .env
# Editar .env con tus credenciales de base de datos
```

### Configuración de Entorno

Configurar archivo `.env` con:
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` - Credenciales de base de datos
- `BASE_URL` - URL base de la aplicación (ej: `http://localhost/SistemaReservasHospital/public`)

## Arquitectura General

### Patrón MVC Personalizado

**Flujo**: Petición → Router → Middleware → Controller → Model → View → Respuesta

1. **Router** (`app/Core/Router.php`): Las rutas se registran con notación de array `[ControllerClass::class, 'method']`
2. **Middleware** (`app/Core/Middleware.php`): Guardias de autenticación (`auth`, `guest`, `admin`)
3. **Controller** (`app/Core/Controller.php`): Coordina Model y View, maneja renderizado
4. **Model** (`app/Core/Model.php`): Abstracción de base de datos con métodos CRUD genéricos
5. **View** (`views/`): Plantillas PHP con layouts opcionales

### Componentes Core Principales

**Sistema de Rutas**:
- Soporta métodos GET y POST
- Usa callbacks basados en arrays: `[ControllerClass::class, 'methodName']`
- Rutas definidas en `routes/web.php`
- Llama automáticamente a `ErrorHandler::notFound()` para rutas no coincidentes

**Sistema de Autenticación** (`app/Core/Auth.php`):
- `Auth::login($user)` - Iniciar sesión (regenera ID de sesión)
- `Auth::logout()` - Cierre de sesión seguro (limpia sesión, cookies, destruye sesión)
- `Auth::check()` - Verificar si el usuario está autenticado
- `Auth::user()` - Obtener datos del usuario actual `['id', 'name', 'role']`
- `Auth::generateCsrfToken()` - Crear token CSRF
- `Auth::validateCsrfToken($token)` - Verificar token CSRF

**Guardias Middleware**:
- `Middleware::auth()` - Requiere usuario autenticado (redirige a `/login`)
- `Middleware::guest()` - Requiere usuario NO autenticado (redirige a `/dashboard`)
- `Middleware::admin()` - Requiere rol de administrador

**CRUD del Model** (heredado por todos los modelos):
- `all()` - Obtener todos los registros
- `find($id)` - Buscar por clave primaria
- `create($data)` - Insertar registro
- `update($id, $data)` - Actualizar registro
- `delete($id)` - Eliminar registro
- `where($conditions)` - Consultar con condiciones
- `findWhere($conditions)` - Encontrar un solo registro con condiciones
- `count()` - Contar registros
- `query($sql, $params)` - Ejecutar prepared statement personalizado

**Controllers**:
- Extienden `App\Core\Controller`
- Usar `$this->render($view, $data)` para vistas sin layout (ej: página de login)
- Usar `$this->renderWithLayout($view, $data)` para vistas con layout de AdminLTE (header, sidebar, footer)
- `renderWithLayout()` proporciona automáticamente `$userName` y `$userRole` a todas las vistas
- Usar `$this->redirect($path)` para redirecciones
- Usar `$this->json($data, $status)` para respuestas JSON

**Views**:
- Los layouts están en `views/layouts/` (header, sidebar, footer)
- Pasar `pageTitle`, `pageStyles`, `pageScripts` en el array de datos para CSS/JS personalizados
- Usar clases de AdminLTE 3 y Bootstrap 4 para estilos
- Nunca incluir CSS/JS de fuera de la estructura del proyecto
- **Importante**: Usar `BASE_URL` (no `URL_BASE`) en las vistas para construir URLs

**Base de Datos**:
- Patrón Singleton: `Database::getInstance()->getConnection()`
- PDO con prepared statements (automático en métodos del Model)
- Charset UTF-8 establecido por defecto

## Sistema de Notificaciones (SweetAlert2)

### AlertUtils - Alertas Modales Completas

Utilidad para mostrar alertas modales con SweetAlert2. Archivo: `public/js/core/sweetalert-utils.js`

**Métodos disponibles**:

```javascript
// Alert de éxito
AlertUtils.success('¡Éxito!', 'Operación completada correctamente');

// Alert de error
AlertUtils.error('Error', 'No se pudo completar la operación');

// Alert de advertencia
AlertUtils.warning('Advertencia', 'Por favor verifica los datos');

// Alert de información
AlertUtils.info('Información', 'Datos actualizados');

// Alert de confirmación personalizado
AlertUtils.confirm(
    '¿Confirmar operación?',
    'Esta acción requiere confirmación',
    () => { /* callback al confirmar */ },
    {
        confirmText: 'Sí, continuar',
        cancelText: 'Cancelar',
        icon: 'question'
    }
);

// Alert de confirmación para eliminar
AlertUtils.confirmDelete(
    '/pacientes/delete/5',
    '¿Está seguro de eliminar este registro?',
    'Esta acción no se puede deshacer'
);

// Alert de bienvenida (con saludo según hora del día)
AlertUtils.welcome('Juan Pérez');
```

### ToastUtils - Notificaciones Toast

Utilidad para mostrar notificaciones toast (pequeñas, esquina superior derecha).

**Métodos disponibles**:

```javascript
// Toast de éxito
ToastUtils.success('Registro guardado correctamente');

// Toast de error
ToastUtils.error('Error al guardar');

// Toast de advertencia
ToastUtils.warning('Campos incompletos');

// Toast de información
ToastUtils.info('Cambios pendientes');

// Toast de carga
ToastUtils.loading('Cargando datos...');

// Toast de carga con tiempo mínimo garantizado
ToastUtils.loadingWithMinTime(
    'Procesando...',
    (loadingToast) => {
        // Código a ejecutar después del tiempo mínimo
        loadingToast.close();
        ToastUtils.success('¡Listo!');
    },
    800 // tiempo mínimo en ms
);
```

**Uso en el sistema**:
- El archivo se carga globalmente en el footer del layout
- Usar estos métodos en lugar de `Swal.fire()` directamente
- Las funciones legacy (`showToast`, `showSuccess`, etc.) están deprecated

### Mensajes Flash desde PHP

```php
// En el controlador (PHP)
$_SESSION['message'] = 'Mensaje de éxito';
$_SESSION['icon'] = 'success'; // success, error, warning, info
$_SESSION['welcome_user'] = 'Juan'; // Opcional para mensaje de bienvenida
$this->redirect('/destino');
```

Los mensajes se muestran automáticamente con el layout y se limpian después de mostrarse.

## Tareas Comunes de Desarrollo

### Crear un Nuevo Módulo

1. **Crear Modelo** en `app/Models/`:
```php
<?php
namespace App\Models;
use App\Core\Model;

class Patient extends Model {
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    // Métodos CRUD heredados: all(), find(), create(), update(), delete()

    // Agregar métodos personalizados según sea necesario
    public function dniExists(string $dni, ?int $excludeId = null): bool {
        // Lógica personalizada
    }
}
```

2. **Crear Controlador** en `app/Controllers/`:
```php
<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Middleware;
use App\Models\Patient;

class PatientController extends Controller {
    private $patientModel;

    public function __construct() {
        $this->patientModel = new Patient();
    }

    public function index() {
        Middleware::auth();
        $this->renderWithLayout('patients/index', [
            'pageTitle' => 'Pacientes',
            'patients' => $this->patientModel->all()
        ]);
    }
}
```

3. **Crear Vistas** en `views/patients/`:
```php
<!-- views/patients/index.php -->
<section class="content-header">
    <h1>Pacientes</h1>
</section>
<section class="content">
    <!-- Estructura de card de AdminLTE -->
</section>
```

4. **Registrar Rutas** en `routes/web.php`:
```php
$router->get('/pacientes', [PatientController::class, 'index']);
$router->post('/pacientes/store', [PatientController::class, 'store']);
```

5. **Agregar al Menú** en `views/layouts/sidebar.php`:
```php
<li class="nav-item">
    <a href="<?= BASE_URL; ?>/pacientes" class="nav-link">
        <i class="nav-icon fas fa-hospital-user"></i>
        <p>Pacientes</p>
    </a>
</li>
```

### Convenciones de Rutas RESTful

- `GET /recurso` - Listar todos los recursos (`index()`)
- `GET /recurso/crear` - Mostrar formulario de creación (`showCreate()`)
- `POST /recurso/store` - Guardar nuevo recurso (`store()`)
- `GET /recurso/editar/:id` - Mostrar formulario de edición (`showEdit($id)`)
- `POST /recurso/update/:id` - Actualizar recurso (`update($id)`)
- `POST /recurso/delete/:id` - Eliminar recurso (`destroy($id)`)
- `POST /recurso/toggle` - Cambiar estado (activo/inactivo) (`toggle()`)
- Endpoints AJAX: `POST /recurso/check-field` para validación remota
- Endpoints AJAX: `GET /recurso/show` para obtener datos en JSON

### Validación de Formularios con jQuery Validate

Usar validación asíncrona para evitar bloquear el hilo principal:

```javascript
$('#formPatient').validate({
    rules: {
        dni: {
            required: true,
            remote: {
                url: BASE_URL + '/pacientes/check-dni',
                type: 'POST',
                data: { dni: function() { return $('#dni').val(); } }
            }
        }
    },
    messages: {
        dni: {
            required: 'Por favor ingrese el DNI',
            remote: 'Este DNI ya está registrado'
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },
    submitHandler: function(form) {
        form.submit(); // Procesamiento asíncrono
    }
});
```

Endpoint AJAX en el controlador:
```php
public function checkDni() {
    header('Content-Type: application/json');
    $dni = $_POST['dni'] ?? '';
    $exists = $this->patientModel->dniExists($dni);
    echo json_encode(!$exists); // true = válido, false = inválido
    exit;
}
```

### Implementar Autenticación

**Flujo de Login**:
```php
public function showLogin() {
    Middleware::guest();
    $this->render('auth/login', [
        'csrfToken' => Auth::generateCsrfToken()
    ]);
}

public function login() {
    if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['message'] = 'Token de seguridad inválido';
        $_SESSION['icon'] = 'error';
        $this->redirect('/login');
        return;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $user = $this->userModel->findByEmail($email);

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
```

**Flujo de Logout**:
```php
public function logout() {
    Middleware::auth();
    Auth::logout();

    // Iniciar nueva sesión para mensajes flash
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['message'] = 'Sesión cerrada correctamente';
    $_SESSION['icon'] = 'success';
    $this->redirect('/login');
}
```

**Seguridad de Contraseñas**:
- Siempre hashear: `password_hash($password, PASSWORD_BCRYPT)`
- Siempre verificar: `password_verify($input, $hash)`

### Protección CSRF

Todos los formularios POST deben incluir token CSRF:

```php
// Controlador
$csrfToken = Auth::generateCsrfToken();
$this->renderWithLayout('form', ['csrfToken' => $csrfToken]);

// Vista
<input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">

// Procesamiento
if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
    // Rechazar petición
}
```

### Manejo de Errores

**Páginas de Error Incorporadas**:
- `ErrorHandler::notFound()` - Error 404
- `ErrorHandler::serverError()` - Error 500
- `ErrorHandler::serviceUnavailable()` - Error 503
- `ErrorHandler::showError($code)` - Error genérico

**Uso**:
```php
$patient = $this->patientModel->find($id);
if (!$patient) {
    ErrorHandler::notFound();
}

try {
    // Operación crítica
} catch (\Exception $e) {
    error_log("Error: " . $e->getMessage());
    ErrorHandler::serverError();
}
```

**Configuración de Modo**:
- Establecer `APP_ENV=development` en `.env` para mensajes de error detallados
- Establecer `APP_ENV=production` para mensajes amigables al usuario

### Toggle de Estado (Activar/Desactivar)

Patrón común para cambiar el estado de registros (activo/inactivo):

```php
// En el controlador
public function toggle() {
    Middleware::auth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['message'] = 'Método no permitido';
        $_SESSION['icon'] = 'error';
        $this->redirect('/especialidades');
        return;
    }

    $id = $_POST['id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    if (!$id || !in_array($newStatus, ['0', '1'])) {
        $_SESSION['message'] = 'Datos inválidos';
        $_SESSION['icon'] = 'error';
        $this->redirect('/especialidades');
        return;
    }

    $result = $this->specialtyModel->update($id, ['is_active' => $newStatus]);

    if ($result) {
        $_SESSION['message'] = $newStatus == '1'
            ? 'Especialidad activada correctamente'
            : 'Especialidad desactivada correctamente';
        $_SESSION['icon'] = 'success';
    } else {
        $_SESSION['message'] = 'Error al cambiar el estado';
        $_SESSION['icon'] = 'error';
    }

    $this->redirect('/especialidades');
}
```

**En la vista con JavaScript**:
```javascript
function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus == 1 ? 0 : 1;
    const action = newStatus == 1 ? 'activar' : 'desactivar';

    AlertUtils.confirm(
        `¿Está seguro de ${action} esta especialidad?`,
        'Puede revertir esta acción en cualquier momento',
        () => {
            // Crear y enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = BASE_URL + '/especialidades/toggle';
            // Agregar campos y enviar
        }
    );
}
```

### DataTables para Listados

Patrón para listados con DataTables (búsqueda, paginación, ordenamiento):

```javascript
// public/js/modules/specialties/datatable-specialties.js
$(document).ready(function () {
    $('#tableSpecialties').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'asc']], // Ordenar por primera columna
        columnDefs: [
            {
                targets: -1, // Última columna (acciones)
                orderable: false,
                searchable: false
            }
        ]
    });
});
```

**En la vista**:
```php
<table id="tableSpecialties" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specialties as $specialty): ?>
        <tr>
            <td><?= htmlspecialchars($specialty['name']); ?></td>
            <td><?= $specialty['is_active'] ? 'Activo' : 'Inactivo'; ?></td>
            <td><!-- Botones de acción --></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

**Cargar scripts necesarios**:
```php
$this->renderWithLayout('specialties/index', [
    'pageTitle' => 'Especialidades',
    'pageScripts' => [
        'js/modules/specialties/datatable-specialties.js'
    ]
]);
```

## Convenciones de Código

### Nomenclatura

- **Clases**: PascalCase (`PatientController`, `Patient`)
- **Métodos/Variables**: camelCase (`getTotalUsers`, `$patientModel`)
- **Vistas**: snake_case (`admin.php`, `index.php`)
- **Constantes**: MAYÚSCULAS (`BASE_URL`, `DB_HOST`)

### Namespaces

Todas las clases PHP usan namespaces:
- Controllers: `namespace App\Controllers;`
- Models: `namespace App\Models;`
- Core: `namespace App\Core;`
- Config: `namespace App\Config;`

### Organización de Archivos

```
app/
├── Config/          # Configuración de base de datos y entorno
├── Controllers/     # Manejadores de peticiones
├── Core/           # Núcleo del framework (Router, Model, Auth, etc.)
└── Models/         # Modelos de base de datos

routes/
└── web.php         # Todas las definiciones de rutas

views/
├── layouts/        # Header, sidebar, footer
├── errors/         # Páginas de error (404, 500, 503)
└── [module]/       # Vistas específicas del módulo

public/
├── index.php       # Punto de entrada
├── css/
│   ├── core/       # Estilos del sistema
│   ├── lib/        # Librerías externas (Bootstrap, AdminLTE)
│   ├── modules/    # Estilos específicos de módulos
│   └── plugins/    # Estilos de plugins (DataTables, Select2)
└── js/
    ├── core/       # Scripts del sistema (app.js, sweetalert-utils.js)
    ├── lib/        # Librerías externas
    ├── modules/    # Scripts específicos de módulos
    └── plugins/    # Scripts de plugins
```

## Patrones Importantes

### Datos de Usuario en Vistas

`renderWithLayout()` proporciona automáticamente:
- `$userName` - Nombre del usuario autenticado
- `$userRole` - Rol del usuario (admin, doctor, receptionist)

No es necesario pasarlos manualmente en el array de datos.

### Definición de Modelos

Modelos con CRUD genérico solo necesitan:
```php
class Patient extends Model {
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';
}
```

Modelos con queries personalizadas pueden omitir `$table` y usar `$this->query($sql, $params)`.

### Formularios de Dos Columnas

Usar grid 8/4 de AdminLTE para formularios:
- **8 columnas**: Formulario principal con cards colapsables
- **4 columnas**: Ayuda contextual y guías

```php
<div class="row">
    <div class="col-md-8">
        <!-- Formulario principal -->
    </div>
    <div class="col-md-4">
        <!-- Guía y consejos -->
    </div>
</div>
```

### Evitar Sobre-Ingeniería

- No agregar características más allá de los requisitos
- No refactorizar código que no está siendo modificado
- No agregar comentarios a código sin cambios
- No crear abstracciones para operaciones de una sola vez
- Confiar en el código interno - solo validar en fronteras del sistema (entrada de usuario, APIs externas)
- Eliminar código no usado completamente (sin `_vars`, sin comentarios `// removed`)

## Checklist de Seguridad

- ✅ Usar `Middleware::auth()` en rutas protegidas
- ✅ Usar `Middleware::guest()` en rutas de login/registro
- ✅ Usar `Middleware::admin()` en rutas solo para administradores
- ✅ Generar tokens CSRF en formularios: `Auth::generateCsrfToken()`
- ✅ Validar tokens CSRF: `Auth::validateCsrfToken($token)`
- ✅ Hashear contraseñas: `password_hash($password, PASSWORD_BCRYPT)`
- ✅ Verificar contraseñas: `password_verify($input, $hash)`
- ✅ Sanitizar entradas: `filter_input()`, `trim()`, prepared statements
- ✅ Usar métodos CRUD del Model (prepared statements automáticos)
- ✅ Validar y sanitizar datos en validaciones AJAX
- ✅ Verificar método HTTP en endpoints (`$_SERVER['REQUEST_METHOD']`)

## Referencias de Documentación

- **Guía del Desarrollador**: `.github/docs/DEVELOPER_GUIDE.md` - Arquitectura MVC completa, convenciones, ejemplos
- **Referencia de Autenticación**: `.github/docs/AUTH_QUICK_REFERENCE.md` - Métodos de Auth, middleware, patrones de seguridad
- **Manejo de Errores**: `.github/docs/ERROR_HANDLING.md` - Sistema de errores, páginas personalizadas, logging
- **Ejemplos de Models**: `.github/docs/EJEMPLOS_MODEL.md` - Ejemplos de uso de modelos
- **Changelog**: `CHANGELOG.md` - Historial de versiones
- **README**: `README.md` - Resumen del proyecto, instalación

## Estado Actual de Implementación

**Completado**:
- Arquitectura MVC con clases base personalizadas Router, Model, Controller
- Sistema de autenticación (login, logout, protección CSRF)
- Middleware (auth, guest, admin)
- Dashboard con vistas basadas en roles
- Gestión de Pacientes (creación con validación)
- Gestión de Especialidades (CRUD completo con toggle de estado)
- Manejo de errores (páginas personalizadas 404, 500, 503)
- Layout de AdminLTE 3 con diseño responsive
- Sistema de notificaciones (AlertUtils y ToastUtils)
- DataTables para listados

**En Progreso**:
- Listado completo de Pacientes con DataTables
- Funcionalidad de edición/actualización de Pacientes
- Gestión de Usuarios (doctores, recepcionistas)
- Citas médicas
- Calendario de citas
- Reportes

## Flujo de Trabajo Git

- **Rama principal**: `main`
- **Rama de desarrollo**: `dev`
- **Ramas de características**: Crear desde `dev`, fusionar de vuelta mediante PR
- **Sistema de labels**: Priority, type, status, module, effort (ver README para detalles)

## Notas Adicionales

### Variables Globales Disponibles

En todas las vistas se tiene acceso a:
- `BASE_URL` - URL base de la aplicación (definida en config.php desde .env)
- `$userName` - Nombre del usuario autenticado (solo en vistas con layout)
- `$userRole` - Rol del usuario (solo en vistas con layout)
- `$pageTitle` - Título de la página (definido en el controlador)
- `$pageStyles` - Array de CSS específicos de la página
- `$pageScripts` - Array de JS específicos de la página

### Estructura de Commits

Seguir Conventional Commits:
- `feat(modulo): descripción` - Nueva característica
- `fix(modulo): descripción` - Corrección de bug
- `refactor(modulo): descripción` - Refactorización
- `docs: descripción` - Cambios en documentación
- `chore: descripción` - Tareas de mantenimiento

### Scripts JavaScript Core

- `public/js/core/app.js` - Inicialización global de la aplicación
- `public/js/core/sweetalert-utils.js` - Utilidades de notificaciones (AlertUtils, ToastUtils)

Estos se cargan automáticamente en todas las páginas mediante el footer del layout.
