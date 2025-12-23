# 🚨 Sistema de Manejo de Errores

## Descripción General

El sistema implementa un manejo robusto de errores HTTP con páginas personalizadas para los códigos de error más comunes y captura automática de excepciones y errores PHP.

---

## 📄 Páginas de Error Disponibles

### 404 - Página No Encontrada
**Archivo**: `views/errors/404.php`

**Se muestra cuando**:
- El usuario intenta acceder a una ruta que no existe
- Una URL está mal escrita o desactualizada

**Características**:
- Icono: ⚠️ (triángulo de advertencia)
- Color: Amarillo/Warning
- Botones: Volver Atrás, Ir al Inicio

---

### 500 - Error Interno del Servidor
**Archivo**: `views/errors/500.php`

**Se muestra cuando**:
- Ocurre una excepción no capturada en el código
- Hay un error fatal de PHP
- Falla una operación crítica del servidor

**Características**:
- Icono: ❌ (círculo con X)
- Color: Rojo/Danger
- Botones: Volver Atrás, Ir al Inicio, Recargar Página
- **Modo desarrollo**: Muestra detalles técnicos de la excepción

---

### 503 - Servicio No Disponible
**Archivo**: `views/errors/503.php`

**Se muestra cuando**:
- El sistema está en mantenimiento
- La base de datos no está disponible
- El servidor está sobrecargado

**Características**:
- Icono: 🔧 (herramientas)
- Color: Azul/Info
- Botones: Ir al Inicio, Recargar Página

---

## 🏗️ Arquitectura del Sistema

### Layout Reutilizable
**Archivo**: `views/errors/layout.php`

Todas las páginas de error usan un layout común que proporciona:
- Diseño consistente
- Configuración flexible mediante variables
- Soporte para botones personalizados
- Mostrar/ocultar detalles técnicos

**Variables del Layout**:
```php
$errorCode         // Código HTTP (404, 500, 503)
$errorTitle        // Título del error
$errorMessage      // Mensaje descriptivo
$errorIcon         // Icono de Font Awesome
$errorIconColor    // Color del icono (text-warning, text-danger, text-info)
$errorTitleColor   // Color del título
$showBackButton    // Mostrar botón "Volver Atrás" (true/false)
$showHomeButton    // Mostrar botón "Ir al Inicio" (true/false)
$showRefreshButton // Mostrar botón "Recargar" (true/false)
$errorDetails      // Detalles técnicos (opcional, solo en desarrollo)
```

---

## 🔧 Clase ErrorHandler

**Archivo**: `app/Core/ErrorHandler.php`

### Métodos Públicos

#### `ErrorHandler::register()`
Registra los manejadores de errores y excepciones del sistema.

**Uso**:
```php
// En public/index.php (ya registrado automáticamente)
ErrorHandler::register();
```

**¿Qué hace?**:
- Captura excepciones no manejadas
- Convierte errores PHP en excepciones
- Maneja errores fatales en el shutdown

---

#### `ErrorHandler::notFound()`
Muestra la página de error 404.

**Uso**:
```php
// En el Router cuando no se encuentra una ruta
ErrorHandler::notFound();

// En un controlador
if (!$usuario) {
    ErrorHandler::notFound();
}
```

---

#### `ErrorHandler::serverError($exception = null)`
Muestra la página de error 500.

**Uso**:
```php
try {
    // Código que puede fallar
    $resultado = $model->operacionRiesgosa();
} catch (\Exception $e) {
    ErrorHandler::serverError($e);
}
```

---

#### `ErrorHandler::serviceUnavailable()`
Muestra la página de error 503.

**Uso**:
```php
// En modo mantenimiento
if (file_exists(__DIR__ . '/maintenance.lock')) {
    ErrorHandler::serviceUnavailable();
}
```

---

#### `ErrorHandler::showError($code, $exception = null)`
Muestra una página de error genérica.

**Parámetros**:
- `$code`: Código HTTP (404, 500, 503, etc.)
- `$exception`: Excepción opcional para modo desarrollo

**Uso**:
```php
// Error personalizado
ErrorHandler::showError(403); // Forbidden
ErrorHandler::showError(401); // Unauthorized
```

---

## 🌍 Modo Desarrollo vs Producción

### Configuración del Entorno

**Archivo**: `.env`
```env
APP_ENV=development  # Para desarrollo local
# APP_ENV=production # Para producción
```

### Comportamiento según el Entorno

| Característica | Desarrollo | Producción |
|---------------|------------|------------|
| **Errores PHP** | Visibles | Ocultos |
| **Detalles de Excepción** | Se muestran en 500 | Ocultos |
| **Error Reporting** | `E_ALL` | `0` |
| **Display Errors** | `1` (On) | `0` (Off) |
| **Logs** | Consola + archivo | Solo archivo |

### Detalles Técnicos en Página 500 (Solo Desarrollo)

Cuando `APP_ENV=development`, la página 500 muestra:
```
Mensaje: Division by zero
Archivo: /opt/lampp/htdocs/.../PatientController.php
Línea: 45
```

---

## 🎯 Captura Automática de Errores

### Excepciones No Capturadas

```php
// En cualquier controlador
public function index()
{
    // Si lanzas una excepción sin catch
    throw new \Exception("Algo salió mal");
    
    // El ErrorHandler la capturará automáticamente
    // y mostrará la página 500
}
```

### Errores Fatales

```php
// Error fatal de sintaxis, memoria, etc.
$array = ['a', 'b', 'c'];
echo $array[100000000]; // Error de memoria

// El ErrorHandler lo capturará en el shutdown
// y mostrará la página 500
```

### Errores de PHP

```php
// Errores comunes de PHP
echo $variableNoDefinida;  // Notice
1 / 0;                     // Warning

// Convertidos automáticamente en excepciones
// y mostrados como página 500
```

---

## 📚 Ejemplos de Uso

### Ejemplo 1: Verificar Recurso Existe

```php
// En el controlador
public function show($id)
{
    $patient = $this->patientModel->find($id);
    
    if (!$patient) {
        // Recurso no encontrado
        ErrorHandler::notFound();
    }
    
    // Continuar si existe
    $this->renderWithLayout('patients/show', ['patient' => $patient]);
}
```

---

### Ejemplo 2: Operación Riesgosa

```php
public function procesarPago()
{
    try {
        $resultado = $this->paymentService->procesar();
        
        if (!$resultado) {
            throw new \Exception("El pago no pudo procesarse");
        }
        
        $_SESSION['message'] = 'Pago procesado correctamente';
        $this->redirect('/pagos');
        
    } catch (\Exception $e) {
        // Mostrar error 500 con detalles
        ErrorHandler::serverError($e);
    }
}
```

---

### Ejemplo 3: Modo Mantenimiento

```php
// En public/index.php (antes de cargar rutas)

// Verificar si existe archivo de mantenimiento
if (file_exists(__DIR__ . '/../maintenance.lock')) {
    ErrorHandler::serviceUnavailable();
}
```

---

### Ejemplo 4: Validación de Permisos

```php
public function deleteUser($id)
{
    Middleware::auth();
    
    $currentUser = Auth::user();
    
    // Solo admins pueden eliminar usuarios
    if ($currentUser['role'] !== 'admin') {
        // Podrías crear una página 403 personalizada
        ErrorHandler::showError(403);
    }
    
    // Continuar con la eliminación
    $this->userModel->delete($id);
}
```

---

## 🎨 Personalizar Páginas de Error

### Crear Nueva Página de Error (ej: 403)

**1. Crear vista** `views/errors/403.php`:
```php
<?php
$errorCode = '403';
$errorTitle = '¡Acceso Prohibido!';
$errorMessage = 'No tienes permisos para acceder a este recurso.';
$errorIcon = 'fa-ban';
$errorIconColor = 'text-danger';
$errorTitleColor = 'text-danger';
$showBackButton = true;
$showHomeButton = true;

require_once __DIR__ . '/layout.php';
```

**2. Actualizar ErrorHandler.php**:
```php
public static function showError(int $code = 500, ?\Throwable $exception = null): void
{
    http_response_code($code);
    
    $errorView = match($code) {
        404 => __DIR__ . '/../../views/errors/404.php',
        403 => __DIR__ . '/../../views/errors/403.php',  // Nueva
        503 => __DIR__ . '/../../views/errors/503.php',
        default => __DIR__ . '/../../views/errors/500.php',
    };
    
    // ... resto del código
}
```

**3. Crear método helper**:
```php
public static function forbidden(): void
{
    self::showError(403);
}
```

---

## 🧪 Probar el Sistema de Errores

### Probar Error 404
1. Acceder a una URL inexistente: `http://localhost/SistemaReservasHospital/public/ruta-que-no-existe`
2. Debe aparecer la página 404 con diseño amarillo

### Probar Error 500
1. Crear una ruta de prueba que lance una excepción
2. Acceder a esa ruta
3. Debe aparecer la página 500 con diseño rojo
4. Si `APP_ENV=development`, se verán detalles técnicos

### Probar Error 503
1. Crear una ruta que llame a `ErrorHandler::serviceUnavailable()`
2. Acceder a esa ruta
3. Debe aparecer la página 503 con diseño azul

---

## 🔍 Logging de Errores

Los errores se registran automáticamente en el log de PHP:

**Ubicación típica en XAMPP/LAMP**:
- `/opt/lampp/logs/php_error_log` (Linux)
- `C:\xampp\apache\logs\error.log` (Windows)

**Formato de log**:
```
[23-Dec-2025 10:30:45] Exception: Division by zero en /opt/.../Controller.php línea 42
[23-Dec-2025 10:31:12] Error Fatal: Call to undefined function en /opt/.../Model.php línea 15
```

---

## ✅ Checklist de Implementación

- ✅ Layout de errores creado (`layout.php`)
- ✅ Página 404 actualizada para usar layout
- ✅ Página 500 creada con modo desarrollo
- ✅ Página 503 creada para mantenimiento
- ✅ Clase ErrorHandler implementada (métodos helper)
- ✅ Router actualizado para usar ErrorHandler
- ✅ Variable `APP_ENV` agregada a configuración
- ✅ Control de display_errors según entorno

---

## 🚀 Próximas Mejoras

- 📧 Enviar emails a admins cuando ocurran errores 500
- 📊 Dashboard de errores con estadísticas
- 🔔 Notificaciones en tiempo real de errores críticos
- 📝 Logs estructurados en archivos separados por tipo
- 🎯 Página 403 (Forbidden) personalizada
- 🔒 Página 401 (Unauthorized) para API

---

_Para más información, consulta la [Guía de Desarrollo](DEVELOPER_GUIDE.md)._

