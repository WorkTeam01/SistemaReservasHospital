# PR: Enviar mensajes de Telegram por eventos de Pull Request con n8n

## Descripción
Este Pull Request implementa y prueba un flujo de trabajo automatizado usando n8n para enviar notificaciones a Telegram cuando ocurren eventos relacionados con Pull Requests (PR) en el repositorio. El objetivo es facilitar la comunicación y seguimiento de cambios importantes en el proyecto.

### Cambios principales
- Se crea un flujo en n8n que escucha eventos de PR (apertura, cierre, merge, etc.).
- El flujo envía mensajes personalizados a un chat de Telegram configurado.
- Se documenta el proceso y configuración necesaria para replicar o modificar el flujo.

## ¿Cómo probar?
1. Configura el webhook de GitHub para eventos de PR apuntando a tu instancia de n8n.
2. Configura el nodo de Telegram con tu bot y chat ID.
3. Realiza una acción sobre un PR (abrir, cerrar, merger, comentar, etc.).
4. Verifica que el mensaje llegue correctamente al chat de Telegram.

## Notas
- Este flujo es una prueba y puede ser extendido para otros eventos o plataformas.
- Asegúrate de que los tokens y credenciales estén correctamente configurados en n8n.

---

> _Este archivo es parte de una prueba de integración entre GitHub, n8n y Telegram para automatizar notificaciones de eventos de Pull Request._

