# 🏥 Sistema de Reservas Hospitalarias (MVP)

Sistema de gestión básica para clínicas u hospitales, permitiendo la administración de citas médicas, pacientes, doctores y especialidades. Desarrollado en **PHP Vanilla** con arquitectura MVC y diseño **AdminLTE 3.2.0**.

---

## 🚀 Tecnologías Utilizadas

- **Lenguaje**: PHP 8.2+
- **Base de Datos**: MySQL / MariaDB
- **Frontend**:
  - HTML5 / CSS3
  - Bootstrap 4 (via AdminLTE)
  - AdminLTE 3 (Plantilla Administrativa)
  - FontAwesome 5 (Iconos)
- **Servidor**: Apache (XAMPP/LAMP recomendado)

---

## 📋 Requisitos Previos

1.  **Servidor Web**: XAMPP, Laragon, o LAMP Stack instalado.
2.  **Versión PHP**: Mínimo 8.0 (Recomendado 8.2+).
3.  **Configuración PHP**: Habilitar extensión `pdo_mysql`.

---

## 🔧 Instalación y Configuración

Sigue estos pasos para levantar el proyecto en tu entorno local:

### 1. Clonar o Descargar el Proyecto

Coloca la carpeta del proyecto en tu directorio de servidor web (ej. `htdocs` en XAMPP o `/var/www/html` en Linux).

### 2. Base de Datos

1.  Abre tu gestor de base de datos (ej. phpMyAdmin).
2.  Crea una nueva base de datos llamada `hospital_db` (o el nombre que prefieras).
3.  Importa el archivo script SQL ubicado en la raíz del proyecto:
    - Archivo: `database.sql`
4.  Esto creará las tablas necesarias (`users`, `patients`, `appointments`, `specialties`, etc) y creará un usuario administrador por defecto.

### 3. Configuración de Entorno (.env)

1.  Ubica el archivo `.env.example` en la raíz del proyecto.
2.  Duplícalo o renómbralo a `.env`.
3.  Edita el archivo `.env` con tus credenciales locales:

```ini
# Configuración del Sistema
BASE_URL="http://localhost/SistemaReservasHospital/public"

# Base de Datos
DB_HOST="localhost"
DB_NAME="hospital_db"
DB_USER="root"
DB_PASS=""
```

> **Nota**: Asegúrate de que `BASE_URL` apunte correctamente a la carpeta `public` de tu proyecto.

---

## 📂 Estructura del Proyecto

- **`/config`**: Archivos de configuración y conexión a BD (`db.php`, `env.php`).
- **`/public`**: Punto de entrada (`index.php`) y assets públicos (CSS, JS, Imágenes).
- **`/views`**: Vistas HTML organizadas por módulos (`dashboard`, `layouts`, etc).
- **`/app`**: (En desarrollo) Controladores y Modelos.
- **`database.sql`**: Script de creación de la base de datos.

---

## 👤 Acceso por Defecto

Una vez instalada la base de datos, puedes acceder con el siguiente usuario administrador (si usas los datos de prueba del script SQL):

- **Usuario/Email**: `admin@example.com`
- **Contraseña**: `password` (El hash en la BD corresponde a "password" por defecto en Laravel/Standard bcrypt).

---

## ✨ Características (MVP)

- [x] Panel de Control (Dashboard) con AdminLTE.
- [x] Verificación de conexión a Base de Datos en el Dashboard.
- [ ] Gestión de Usuarios (Doctores, Recepcionistas).
- [ ] Gestión de Pacientes.
- [ ] Agendamiento de Citas Médicas.
