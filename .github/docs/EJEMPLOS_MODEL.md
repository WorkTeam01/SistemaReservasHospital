# Ejemplos de Uso - Model Mejorado

## 📚 Métodos CRUD Genéricos Disponibles

El `Model.php` mejorado incluye métodos CRUD que funcionan automáticamente con cualquier tabla.

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
