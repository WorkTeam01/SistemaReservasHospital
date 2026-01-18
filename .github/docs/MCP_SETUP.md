# Configuración de MCP (Model Context Protocol)

Esta guía explica cómo configurar MCP en Claude Code para integrar herramientas externas como GitHub directamente en tu flujo de trabajo.

## ¿Qué es MCP?

**Model Context Protocol (MCP)** es un protocolo abierto que permite a Claude Code conectarse con servicios externos (GitHub, bases de datos, APIs, etc.) de forma segura. Con MCP puedes:

- Gestionar repositorios, issues y PRs de GitHub sin salir del terminal
- Consultar bases de datos directamente
- Integrar APIs personalizadas
- Automatizar flujos de trabajo de desarrollo

## Requisitos Previos

- **Node.js** (v18 o superior) - Requerido para ejecutar servidores MCP
- **npm/npx** - Incluido con Node.js
- **Claude Code** - CLI instalado y autenticado

Verificar instalación de Node.js:
```bash
node --version
npm --version
```

## Instalación desde Claude Code Templates

### Método 1: Comando Interactivo (Recomendado)

1. Abre Claude Code en tu terminal:
   ```bash
   claude
   ```

2. Usa el comando de instalación de MCP:
   ```
   /mcp
   ```

3. Selecciona **"Add new MCP server"** o **"Install from template"**

4. Elige el servidor que deseas instalar (ej: `github`)

5. Claude Code te guiará para configurar las credenciales necesarias

### Método 2: Configuración Manual

1. Crea el archivo `.mcp.json` en la raíz de tu proyecto:
   ```bash
   touch .mcp.json
   ```

2. Agrega la configuración del servidor MCP:
   ```json
   {
     "mcpServers": {
       "github": {
         "description": "Direct GitHub API integration for repository management, issue tracking, pull requests, and collaborative development workflows.",
         "command": "npx",
         "args": [
           "-y",
           "@modelcontextprotocol/server-github"
         ],
         "env": {
           "GITHUB_PERSONAL_ACCESS_TOKEN": "ghp_tu_token_aqui"
         }
       }
     }
   }
   ```

3. Reinicia Claude Code para que detecte la configuración

## Generar Token de Acceso de GitHub

El token de acceso personal (PAT) permite a Claude Code interactuar con la API de GitHub en tu nombre.

### Pasos para crear el token:

1. Ve a [GitHub Settings > Developer settings > Personal access tokens](https://github.com/settings/tokens)

2. Haz clic en **"Generate new token"** > **"Generate new token (classic)"**

3. Configura el token:
   - **Note**: `Claude Code MCP` (o un nombre descriptivo)
   - **Expiration**: Selecciona la duración deseada (recomendado: 90 días)
   - **Scopes**: Selecciona los permisos necesarios:

   | Scope | Descripción | Requerido |
   |-------|-------------|-----------|
   | `repo` | Acceso completo a repositorios privados | ✅ Sí |
   | `read:org` | Leer membresías de organización | ✅ Sí (si usas organizaciones) |
   | `write:discussion` | Crear/editar discusiones | Opcional |
   | `read:project` | Leer proyectos | Opcional |

4. Haz clic en **"Generate token"**

5. **IMPORTANTE**: Copia el token inmediatamente. No podrás verlo de nuevo.

### Token con Fine-grained permissions (Alternativa más segura)

1. En la misma página, selecciona **"Fine-grained tokens"**

2. Configura:
   - **Token name**: `Claude Code MCP`
   - **Expiration**: Duración deseada
   - **Repository access**: Selecciona los repositorios específicos
   - **Permissions**:
     - Contents: Read and write
     - Issues: Read and write
     - Pull requests: Read and write
     - Metadata: Read-only

3. Genera y copia el token

## Estructura del Archivo .mcp.json

```json
{
  "mcpServers": {
    "nombre-servidor": {
      "description": "Descripción del servidor",
      "command": "comando-a-ejecutar",
      "args": ["argumentos", "del", "comando"],
      "env": {
        "VARIABLE_ENTORNO": "valor"
      }
    }
  }
}
```

### Múltiples Servidores MCP

Puedes configurar varios servidores en el mismo archivo:

```json
{
  "mcpServers": {
    "github": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-github"],
      "env": {
        "GITHUB_PERSONAL_ACCESS_TOKEN": "ghp_xxx"
      }
    },
    "postgres": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-postgres"],
      "env": {
        "POSTGRES_CONNECTION_STRING": "postgresql://user:pass@localhost/db"
      }
    }
  }
}
```

## Servidores MCP Disponibles

| Servidor | Paquete | Descripción |
|----------|---------|-------------|
| GitHub | `@modelcontextprotocol/server-github` | Gestión de repos, issues, PRs |
| PostgreSQL | `@modelcontextprotocol/server-postgres` | Consultas a bases de datos PostgreSQL |
| SQLite | `@modelcontextprotocol/server-sqlite` | Consultas a bases de datos SQLite |
| Filesystem | `@modelcontextprotocol/server-filesystem` | Acceso extendido al sistema de archivos |
| Brave Search | `@modelcontextprotocol/server-brave-search` | Búsquedas web con Brave |
| Fetch | `@modelcontextprotocol/server-fetch` | Peticiones HTTP a APIs |

Ver lista completa en: https://github.com/modelcontextprotocol/servers

## Funcionalidades del MCP de GitHub

Una vez configurado, puedes realizar estas operaciones directamente desde Claude Code:

### Repositorios
- Buscar repositorios
- Crear nuevos repositorios
- Obtener contenido de archivos
- Crear ramas
- Listar commits

### Issues
- Listar issues (filtrar por estado, labels, etc.)
- Crear nuevos issues
- Actualizar issues existentes
- Agregar comentarios
- Buscar issues

### Pull Requests
- Listar PRs
- Crear nuevos PRs
- Ver archivos cambiados
- Crear reviews (aprobar, comentar, solicitar cambios)
- Mergear PRs
- Actualizar rama del PR

### Ejemplos de uso

```
# Listar issues abiertos
"Muéstrame los issues abiertos del repositorio"

# Crear un issue
"Crea un issue titulado 'Bug en login' con la descripción del problema"

# Ver PRs pendientes
"Lista los pull requests abiertos"

# Crear una rama
"Crea una rama feature/nueva-funcionalidad desde main"

# Buscar en código
"Busca archivos que contengan 'authentication' en el repo"
```

## Seguridad

### Archivo .mcp.json en .gitignore

**IMPORTANTE**: El archivo `.mcp.json` contiene credenciales sensibles. Asegúrate de que esté en `.gitignore`:

```gitignore
# Archivos de configuración
.mcp.json
```

### Buenas Prácticas

1. **Nunca compartas tu token** - Si se expone, revócalo inmediatamente
2. **Usa tokens con fecha de expiración** - Renuévalos periódicamente
3. **Permisos mínimos** - Solo otorga los scopes necesarios
4. **Un token por proyecto** - Facilita la revocación si es necesario
5. **Fine-grained tokens** - Úsalos cuando sea posible para mayor control

### Revocar un Token Comprometido

1. Ve a [GitHub Settings > Personal access tokens](https://github.com/settings/tokens)
2. Encuentra el token comprometido
3. Haz clic en **"Delete"** o **"Revoke"**
4. Genera un nuevo token
5. Actualiza tu archivo `.mcp.json`

## Solución de Problemas

### El servidor MCP no se conecta

1. Verifica que Node.js esté instalado:
   ```bash
   node --version
   ```

2. Verifica que el token sea válido:
   ```bash
   curl -H "Authorization: token ghp_tu_token" https://api.github.com/user
   ```

3. Reinicia Claude Code después de modificar `.mcp.json`

### Error de permisos

- Verifica que el token tenga los scopes necesarios
- Para repositorios privados, necesitas el scope `repo`
- Para organizaciones, necesitas `read:org`

### El archivo .mcp.json no se detecta

- Debe estar en la raíz del proyecto
- El formato JSON debe ser válido (sin comas trailing, comillas dobles)
- Reinicia Claude Code

## Recursos Adicionales

- [Documentación oficial de MCP](https://modelcontextprotocol.io/)
- [Repositorio de servidores MCP](https://github.com/modelcontextprotocol/servers)
- [GitHub REST API](https://docs.github.com/en/rest)
- [Crear tokens de acceso personal](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token)