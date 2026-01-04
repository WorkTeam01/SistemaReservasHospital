# Changelog

Todos los cambios notables de este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

---

## [1.0.0] - 2025-01-04

**🎉 Primer Release Funcional - Sprint 1**

Este es el primer release funcional del sistema después de completar el Sprint 1. Incluye la infraestructura base MVC y los módulos principales implementados.

### ✨ Added - Nuevas Funcionalidades

#### Core del Sistema
- Implementada arquitectura **MVC completa** con separación de responsabilidades
- Sistema de **rutas personalizado** (`Router`) con soporte para middleware y rutas RESTful
- **Autoloader PSR-4** para carga automática de clases
- Configuración centralizada con soporte para archivos `.env`
- Sistema de **middleware** para protección de rutas (auth, guest, admin)
- **Layouts reutilizables** (header, sidebar, footer) para todas las vistas
- Conexión a base de datos con **PDO** y prepared statements


#### Sistema de Autenticación Completo
- **Login** con validación de credenciales y hash de contraseñas
- **Logout seguro** con:
  - Limpieza completa de variables de sesión (`session_unset()`)
  - Destrucción segura de sesión (`session_destroy()`)
  - Regeneración de ID de sesión para prevenir session fixation
  - Limpieza de cookies de sesión
- **Protección CSRF** con tokens en formularios
- **Datos de usuario automáticos** en todas las vistas:
  - Información de usuario disponible globalmente vía `Auth::getUserData()`
  - Rol del usuario visible en el menú de usuario (header/sidebar)
  - Datos cargados automáticamente en todas las vistas protegidas
- Clase `Auth` helper con métodos estáticos para gestión de autenticación

#### Dashboard Administrativo
- **Panel de control** con estadísticas en tiempo real
- **Dashboards personalizados por rol**:
  - Vista de Administrador
  - Vista de Doctor
  - Vista de Recepcionista
- Widgets con información relevante según el rol del usuario

#### Sistema de Manejo de Errores Completo
- Implementado sistema robusto con clase `ErrorHandler` helper
- **Páginas de error personalizadas** con diseño AdminLTE:
  - **404 Not Found**: Página no encontrada con sugerencias de navegación
  - **500 Internal Server Error**: Error del servidor con modo desarrollo/producción
  - **503 Service Unavailable**: Servicio no disponible (mantenimiento programado)
- **Layout reutilizable** para todas las páginas de error (`views/errors/layout.php`)
- **Integración automática** de errores con el `Router` principal
- **Modo desarrollo** con detalles técnicos completos para debugging
- **Modo producción** con mensajes genéricos para el usuario final

#### Módulo de Pacientes (RF03 - Parcial)
- **Formulario de creación** con diseño moderno y profesional:
  - Layout de **dos columnas** (formulario principal + guía lateral)
  - **Cards colapsables** organizadas por secciones temáticas:
    - Datos Básicos (Nombre, Apellido, DNI, Fecha de Nacimiento)
    - Datos de Contacto (Teléfono, Email, Dirección)
  - **Breadcrumb completo** con navegación intuitiva
  - **Guía de registro** con información contextual útil
  - **Panel de consejos** y recomendaciones en la columna lateral
  - **Input groups con iconos** para mejor visualización
  - **Indicadores de campos obligatorios** con asterisco rojo
  - Diseño **responsive** y user-friendly
- **Validación de formularios** con jQuery Validate:
  - Validación **asíncrona** (sin bloqueo del hilo principal)
  - Mensajes de error en español
  - Validación en tiempo real
- **Campos del paciente**:
  - Nombre, Apellido (obligatorios)
  - DNI/CI (obligatorio, único)
  - Teléfono (obligatorio, formato +591)
  - Email (opcional, validado)
  - Fecha de Nacimiento (obligatoria)
  - Dirección (opcional, textarea)
- **Rutas RESTful** separadas:
  - `GET /pacientes/crear`: Mostrar formulario
  - `POST /pacientes/store`: Procesar y guardar paciente
- Vista de **listado de pacientes** (placeholder para RF04)

#### Módulo de Especialidades (RF05 - Parcial)
- **Vista de listado** con DataTables integrado
- **Sistema de modales** para operaciones CRUD:
  - Modal de creación
  - Modal de edición
  - Modal de confirmación de eliminación
- **DataTables** con:
  - Búsqueda en tiempo real
  - Ordenamiento por columnas
  - Paginación
  - Botones de acción por fila
- Modelo `Specialty` con métodos CRUD básicos

#### Gestión de Usuarios (Parcial)
- Modelo `User` con roles (Administrador, Doctor, Recepcionista)
- Sistema de roles integrado con el dashboard
- Vista personalizada según el rol del usuario

#### UI/UX y Diseño
- Integración completa de **AdminLTE 3.2.0**
- **Bootstrap 4.6** como framework CSS base
- **Font Awesome 5** para iconografía
- **jQuery 3** con plugins:
  - **jQuery Validate** para validación de formularios
  - **DataTables** para tablas interactivas
  - **SweetAlert2** para alertas y notificaciones elegantes
- **Diseño 100% consistente** usando únicamente estilos de AdminLTE y Bootstrap
- Eliminación de CSS personalizado conflictivo

#### Base de Datos
- Esquema completo con tablas:
  - `users`: Usuarios del sistema con roles
  - `patients`: Pacientes con datos personales y de contacto
  - `specialties`: Especialidades médicas
  - `appointments`: Citas médicas (estructura preparada)
- Columna `address` agregada a tabla `patients`
- Índices y restricciones de integridad referencial

#### Sistema de Mensajes
- **Mensajes flash** con variables de sesión
- Integración con **SweetAlert2** para notificaciones:
  - Success (éxito)
  - Error
  - Warning (advertencia)
  - Info (información)
- Helper para mostrar mensajes en vistas

### 🐛 Fixed - Correcciones de Bugs

#### Validación de Formularios
- **Solucionado warning de jQuery Validate**: "Synchronous XMLHttpRequest on the main thread is deprecated"
  - Implementada validación **asíncrona** con `submitHandler`
  - Eliminadas llamadas síncronas a AJAX que bloqueaban el hilo principal del navegador
  - Mejor experiencia de usuario sin bloqueos

#### Creación de Pacientes
- Corregido problema donde **no se podía crear pacientes**:
  - Se agregaron campos faltantes a la tabla `patients` en la base de datos
  - Mejorado manejo de errores en el controlador
  - Eliminadas validaciones redundantes

#### Manejo de Errores
- Eliminado sistema **redundante de mensajes** con `alert-danger`
- Removida variable de sesión `$_SESSION['errors']` obsoleta
- Implementado manejo **unificado** de errores vía SweetAlert2
- Mejor feedback visual para el usuario

### 🗑️ Removed - Eliminaciones

- Eliminado archivo `public/css/modules/patients/patients.css` que causaba **conflictos con AdminLTE**
- Eliminada carpeta `public/css/modules/patients/` (vacía)
- Removido sistema de mensajes de error con variable `$_SESSION['errors']`
- Eliminadas referencias a `pageStyles` innecesarias en controladores

### 📚 Documentation - Documentación

- **README.md** completo con:
  - Guía de instalación paso a paso
  - Estructura del proyecto documentada
  - Características implementadas listadas
  - Enlaces a documentación técnica
- **CHANGELOG.md** creado siguiendo estándar [Keep a Changelog](https://keepachangelog.com/)
- **[DEVELOPER_GUIDE.md](.github/docs/DEVELOPER_GUIDE.md)** con arquitectura y convenciones
- **[AUTH_QUICK_REFERENCE.md](.github/docs/AUTH_QUICK_REFERENCE.md)** para referencia rápida
- **[ERROR_HANDLING.md](.github/docs/ERROR_HANDLING.md)** con guía completa de errores
- Templates de GitHub:
  - Issue templates (bug report, feature request)
  - Pull request template
  - Sistema de labels organizado

### 🔒 Security - Seguridad

- **Protección CSRF** en todos los formularios
- **Hashing de contraseñas** con `password_hash()` y `password_verify()`
- **Prepared statements** en todas las consultas SQL (prevención de SQL injection)
- **Validación de sesiones** y regeneración de ID
- **Middleware de autenticación** para rutas protegidas
- **Sanitización de inputs** en controladores

### 👥 Contribuidores

Este release fue posible gracias al trabajo del equipo WorkTeam01:

- **Jose Andres Meneces Lopez** (@Jandres25)
  - Infraestructura base del proyecto (MVC, Router, Autoloader)
  - Sistema de login y autenticación inicial
  - Sistema de manejo de errores (404, 500, 503)
  - Mejoras en autenticación y seguridad
  - Documentación técnica (DEVELOPER_GUIDE, README, CHANGELOG)

- **Alex Tapia** (@alexricardotapiacarita-ai)
  - Implementación de logout y gestión segura de sesiones
  - Módulo de Especialidades con CRUD completo
  - Integración de DataTables y modales

- **Jhoseph Orozco** (@Jhos3ph)
  - Módulo de Pacientes con formulario de registro
  - Validaciones con jQuery Validate
  - Diseño UI/UX del formulario de pacientes

---

## Tipos de Cambios

- **✨ Added**: Nuevas funcionalidades
- **🔧 Changed**: Cambios en funcionalidades existentes
- **🗑️ Deprecated**: Funcionalidades obsoletas que serán removidas
- **🗑️ Removed**: Funcionalidades eliminadas
- **🐛 Fixed**: Correcciones de bugs
- **🔒 Security**: Correcciones de seguridad
- **📚 Documentation**: Cambios en documentación

---

## Links

- **Repositorio**: https://github.com/WorkTeam01/SistemaReservasHospital
- **Issues**: https://github.com/WorkTeam01/SistemaReservasHospital/issues
- **Pull Requests**: https://github.com/WorkTeam01/SistemaReservasHospital/pulls

---

_Desarrollado por **WorkTeam01** 🚀_

