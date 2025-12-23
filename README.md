# 🏥 Sistema de Reservas Hospitalarias

Sistema de gestión para clínicas y hospitales, permitiendo la administración de citas médicas, pacientes, doctores y especialidades. Desarrollado en **PHP Vanilla** con arquitectura **MVC** y diseño **AdminLTE**.

---

## 🚀 Tecnologías

- **Backend**: PHP 8.2+ (Arquitectura MVC)
- **Base de Datos**: MySQL / MariaDB (PDO)
- **Frontend**:
  - Bootstrap 4 (vía AdminLTE)
  - AdminLTE 3.2.0
  - FontAwesome 5
  - jQuery 3
- **Servidor**: Apache (XAMPP/LAMP)

---

## 📋 Requisitos

- PHP 8.0+ (Recomendado 8.2+)
- MySQL 5.7+ / MariaDB 10+
- Apache con mod_rewrite
- Extensión PHP: `pdo_mysql`

---

## 🔧 Instalación Rápida

### 1. Clonar el Repositorio

```bash
git clone https://github.com/WorkTeam01/SistemaReservasHospital.git
cd SistemaReservasHospital
```

### 2. Configurar Base de Datos

1. Crear base de datos: `hospital_db`
2. Importar: `database.sql`

### 3. Configurar Variables de Entorno

```bash
cp .env.example .env
```

Editar `.env`:

```ini
BASE_URL="http://localhost/SistemaReservasHospital/public"
DB_HOST="localhost"
DB_NAME="hospital_db"
DB_USER="root"
DB_PASS=""
```

### 4. Acceder al Sistema

Abrir en navegador: `http://localhost/SistemaReservasHospital/public`

**Usuario por defecto:**

- Email: `admin@example.com`
- Contraseña: `password`

---

## 📁 Estructura del Proyecto

```
SistemaReservasHospital/
├── .github/              # GitHub configs y documentación
│   ├── ISSUE_TEMPLATE/       # Plantillas de issues
│   ├── PULL_REQUEST_TEMPLATE/ # Plantillas de pull requests
│   ├── docs/         # Documentación del proyecto
│   └── sync-labels.sh  # Script para sincronizar labels en GitHub
├── app/                  # Lógica de la aplicación
│   ├── Config/          # Configuraciones
│   ├── Controllers/     # Controladores MVC
│   ├── Core/           # Clases base (Router, Model, etc.)
│   └── Models/         # Modelos de datos
├── routes/              # Definición de rutas
│   └── web.php
├── views/               # Vistas HTML/PHP
│   ├── dashboard/
│   ├── errors/
│   └── layouts/
├── public/              # Punto de entrada web
│   ├── index.php       # Front controller
│   ├── css/
│   ├── js/
│   └── img/
├── vendor/              # Librerías de terceros
├── .gitignore           # Archivos ignorados por Git
├── LICENSE              # Licencia del proyecto
├── README.md            # Documentación principal
├── database.sql         # Esquema de BD
└── .env.example         # Variables de entorno de ejemplo
```

---

## 📚 Documentación

### Para Desarrolladores

📖 **[Guía de Desarrollo Completa](.github/docs/DEVELOPER_GUIDE.md)**

Incluye:

- Arquitectura MVC detallada
- Convenciones de código
- Cómo crear nuevos módulos
- Sistema de rutas y middleware
- Buenas prácticas

🔐 **[Auth System - Guía Rápida](.github/docs/AUTH_QUICK_REFERENCE.md)**

Referencia rápida del sistema de autenticación:

- Métodos disponibles (login, logout, CSRF)
- Middleware de protección
- Patrones comunes
- Checklist de seguridad

📋 **[Changelog](.github/docs/CHANGELOG.md)**

Historial de cambios y mejoras del sistema.

🚨 **[Sistema de Manejo de Errores](.github/docs/ERROR_HANDLING.md)**

Guía completa del manejo de errores:

- Páginas personalizadas (404, 500, 503)
- Captura automática de excepciones
- Modo desarrollo vs producción
- Logging de errores

### Características Implementadas

- ✅ **Arquitectura MVC** - Separación clara de responsabilidades
- ✅ **Sistema de Rutas** - Router personalizado con middleware
- ✅ **Autoloader PSR-4** - Carga automática de clases
- ✅ **Layouts Reutilizables** - Sistema de plantillas (header, sidebar, footer)
- ✅ **Dashboard Administrativo** - Panel con estadísticas en tiempo real
- ✅ **Sistema de Autenticación Completo**:
  - Login con validación de credenciales
  - Protección CSRF con tokens
  - Logout seguro (limpia sesión, cookies y variables)
  - Datos de usuario automáticos en todas las vistas
- ✅ **Middleware de Autenticación** - Protección de rutas (auth, guest, admin)
- ✅ **Gestión de Pacientes** - Creación de pacientes con validación
- ✅ **Sistema de Manejo de Errores**:
  - Páginas personalizadas (404, 500, 503)
  - Layout reutilizable para errores
  - ErrorHandler helper class
  - Modo desarrollo con detalles técnicos
- ✅ **Base de Datos** - PDO con prepared statements
- ✅ **Mensajes Flash** - Sistema de notificaciones con SweetAlert2

### En Desarrollo

- 🚧 Gestión de Usuarios (Doctores, Recepcionistas, Admins)
- 🚧 Gestión de Pacientes
- 🚧 Agendamiento de Citas Médicas
- 🚧 Calendario de Citas
- 🚧 Gestión de Especialidades
- 🚧 Sistema de Reportes

---

## 🛠️ Tecnologías y Librerías

### Backend

- **PHP 8.2+** - Lenguaje del servidor
- **PDO** - Capa de abstracción de base de datos
- **MVC Pattern** - Arquitectura del proyecto

### Frontend

- **AdminLTE 3** - Template administrativo
- **Bootstrap 4.6** - Framework CSS
- **jQuery 3** - Manipulación del DOM
- **Font Awesome 5** - Iconos

---

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

**Consulta la [Guía de Desarrollo](.github/docs/DEVELOPER_GUIDE.md) antes de contribuir.**

### 🏷️ Sistema de Labels

El proyecto usa un sistema organizado de labels para issues y PR:

- **Priority**: `critical`, `high`, `medium`, `low`
- **Type**: `bug`, `feature`, `enhancement`, `documentation`, `testing`, `refactor`, `security`
- **Status**: `ready`, `in-progress`, `needs-review`, `blocked`, `on-hold`
- **Module**: `auth`, `usuarios`, `pacientes`, `citas`, `especialidades`, `dashboard`, `reportes`, `core`
- **Effort**: `small` (<1 día), `medium` (1-3 días), `large` (>3 días)

Al crear un issue usando los templates, los labels se aplican automáticamente.

---

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 👥 Equipo

**WorkTeam01** - [GitHub](https://github.com/WorkTeam01)

---

## 📞 Soporte

Para preguntas o problemas:

- 📧 Crear un [Issue](https://github.com/WorkTeam01/SistemaReservasHospital/issues)
- 📖 Consultar la [Documentación](.github/docs/DEVELOPER_GUIDE.md)

---

_Sistema de Reservas Hospitalarias - Versión 1.0.0_
