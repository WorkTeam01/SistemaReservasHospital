# 🔐 Auth System - Guía Rápida

## Referencia Rápida de Métodos

### Auth::generateCsrfToken()

**Propósito**: Generar token CSRF para proteger formularios

```php
// En el controlador
$csrfToken = Auth::generateCsrfToken();

// Pasar a la vista
$this->render('auth/login', ['csrfToken' => $csrfToken]);
```

```html
<!-- En la vista -->
<form method="POST" action="/login">
  <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>" />
  <!-- Resto del formulario -->
</form>
```

---

### Auth::validateCsrfToken($token)

**Propósito**: Validar token CSRF recibido del formulario

```php
// En el método que procesa POST
$csrfToken = $_POST['csrf_token'] ?? '';

if (!Auth::validateCsrfToken($csrfToken)) {
    $_SESSION['message'] = 'Token de seguridad inválido';
    $_SESSION['icon'] = 'error';
    $this->redirect('/login');
    return;
}

// Continuar con el procesamiento...
```

---

### Auth::login($user)

**Propósito**: Iniciar sesión para un usuario

```php
// $user debe ser un array con: user_id, role, name
$user = $this->userModel->findByEmail($email);

if ($user && password_verify($password, $user['password'])) {
    Auth::login($user);
    $_SESSION['welcome_user'] = $user['name'];
    $this->redirect('/dashboard');
}
```

**¿Qué hace internamente?**

1. Regenera el ID de sesión (`session_regenerate_id(true)`)
2. Guarda `$_SESSION['user_id']`
3. Guarda `$_SESSION['user_role']`
4. Guarda `$_SESSION['user_name']`

---

### Auth::check()

**Propósito**: Verificar si hay un usuario autenticado

```php
if (Auth::check()) {
    // Usuario está logueado
    echo "Bienvenido";
} else {
    // Usuario no está logueado
    $this->redirect('/login');
}
```

**Usado internamente por**: `Middleware::auth()`

---

### Auth::user()

**Propósito**: Obtener datos del usuario autenticado

```php
$user = Auth::user();

// Retorna un array con:
// ['id' => ..., 'name' => ..., 'role' => ...]

echo $user['id'];    // ID del usuario
echo $user['name'];  // Nombre del usuario
echo $user['role'];  // Rol: admin, doctor, receptionist

// Si no está autenticado, los valores son null
```

---

### Auth::logout()

**Propósito**: Cerrar sesión de forma segura

```php
public function logout(): void
{
    Middleware::auth();  // Verificar que esté autenticado
    
    Auth::logout();
    
    $_SESSION['message'] = 'Has cerrado sesión correctamente';
    $_SESSION['icon'] = 'success';
    
    $this->redirect('/login');
}
```

**¿Qué hace internamente?**

1. Limpia todas las variables de sesión (`$_SESSION = []`)
2. Destruye la cookie de sesión del navegador
3. Destruye la sesión del servidor (`session_destroy()`)
4. Inicia una nueva sesión limpia para mensajes

---

## Middleware de Autenticación

### Middleware::auth()

**Propósito**: Solo usuarios autenticados pueden acceder

```php
$router->get('/dashboard', function () {
    Middleware::auth();
    // Si llega aquí, el usuario está autenticado
    $controller = new DashboardController();
    $controller->index();
});
```

**¿Qué hace?**

- Si el usuario **está autenticado**: Permite el acceso
- Si el usuario **NO está autenticado**: Redirige a `/login`

---

### Middleware::guest()

**Propósito**: Solo usuarios NO autenticados pueden acceder

```php
$router->get('/login', function () {
    Middleware::guest();
    // Si llega aquí, el usuario NO está autenticado
    $controller = new AuthController();
    $controller->showLogin();
});
```

**¿Qué hace?**

- Si el usuario **NO está autenticado**: Permite el acceso
- Si el usuario **está autenticado**: Redirige a `/dashboard`

**Uso típico**: Páginas de login y registro

---

### Middleware::admin()

**Propósito**: Solo usuarios con rol 'admin' pueden acceder

```php
$router->get('/usuarios', function () {
    Middleware::admin();
    // Si llega aquí, el usuario es administrador
    $controller = new UserController();
    $controller->index();
});
```

**¿Qué hace?**

- Si el usuario **es admin**: Permite el acceso
- Si el usuario **NO es admin**: Muestra error 403

---

## Patrones Comunes

### Patrón 1: Login Completo con CSRF

```php
// Mostrar formulario
public function showLogin(): void
{
    Middleware::guest();
    $csrfToken = Auth::generateCsrfToken();
    $this->render('auth/login', [
        'csrfToken' => $csrfToken
    ]);
}

// Procesar login
public function login(): void
{
    // Validar CSRF
    if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['message'] = 'Token inválido';
        $_SESSION['icon'] = 'error';
        $this->redirect('/login');
        return;
    }

    // Obtener y validar credenciales
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['message'] = 'Campos requeridos';
        $_SESSION['icon'] = 'error';
        $this->redirect('/login');
        return;
    }

    // Buscar usuario
    $user = $this->userModel->findByEmail($email);

    // Verificar
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

---

### Patrón 2: Verificar Autenticación en Ruta

```php
// routes/web.php
$router->get('/pacientes', function () {
    Middleware::auth();  // Solo usuarios autenticados
    (new PatientController())->index();
});
```

---

### Patrón 3: Obtener Datos del Usuario Actual

```php
public function profile(): void
{
    Middleware::auth();
    
    // Obtener datos del usuario autenticado
    $user = Auth::user();
    $userId = $user['id'];
    
    // Obtener información completa de la BD
    $userDetails = $this->userModel->find($userId);
    
    $this->renderWithLayout('users/profile', [
        'userDetails' => $userDetails
    ]);
}
```

---

### Patrón 4: Formulario con Protección CSRF

```php
// Controlador - mostrar formulario
public function create(): void
{
    Middleware::auth();
    $csrfToken = Auth::generateCsrfToken();
    
    $this->renderWithLayout('patients/create', [
        'csrfToken' => $csrfToken
    ]);
}

// Vista - incluir token
<form method="POST" action="<?= URL_BASE; ?>/pacientes/crear">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
    <!-- Campos del formulario -->
    <button type="submit">Guardar</button>
</form>

// Controlador - procesar formulario
public function store(): void
{
    Middleware::auth();
    
    // Validar CSRF
    if (!Auth::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['message'] = 'Token inválido';
        $_SESSION['icon'] = 'error';
        $this->redirect('/pacientes/crear');
        return;
    }
    
    // Procesar datos...
}
```

---

## Seguridad: Contraseñas

### ❌ NUNCA hagas esto

```php
// ❌ INCORRECTO - Contraseña en texto plano
$data = [
    'password' => $_POST['password']
];
```

### ✅ Siempre haz esto

```php
// ✅ CORRECTO - Hash de contraseña
$data = [
    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
];

// ✅ CORRECTO - Verificar contraseña
if (password_verify($inputPassword, $user['password'])) {
    // Contraseña correcta
}
```

---

## Datos de Usuario Automáticos

**El sistema automáticamente proporciona estas variables en todas las vistas con layout:**

```php
// En cualquier controlador
public function index(): void
{
    // NO necesitas hacer esto:
    // $user = Auth::user();
    // 'userName' => $user['name']
    // 'userRole' => $user['role']
    
    $this->renderWithLayout('patients/index', [
        'pageTitle' => 'Pacientes',
        'patients' => $this->patientModel->all()
    ]);
    // $userName y $userRole se agregan automáticamente
}
```

```php
<!-- En cualquier vista con layout -->
<h1>Bienvenido, <?= $userName; ?></h1>
<p>Tu rol: <?= ucfirst($userRole); ?></p>
```

---

## Checklist de Seguridad

Al crear un nuevo módulo con autenticación:

- ✅ Usar `Middleware::auth()` en rutas protegidas
- ✅ Usar `Middleware::guest()` en login/registro
- ✅ Usar `Middleware::admin()` en rutas administrativas
- ✅ Generar token CSRF en formularios: `Auth::generateCsrfToken()`
- ✅ Validar token CSRF al procesar: `Auth::validateCsrfToken($token)`
- ✅ Hashear contraseñas: `password_hash($password, PASSWORD_BCRYPT)`
- ✅ Verificar contraseñas: `password_verify($input, $hash)`
- ✅ Sanitizar inputs: `filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)`
- ✅ Usar prepared statements en queries (automático en Model)
- ✅ Validar permisos antes de operaciones críticas

---

_Para más información, consulta la [Guía de Desarrollo](DEVELOPER_GUIDE.md) completa._

