# 💬 Templates de Comentarios para Code Review

## Cómo usar

Copia y pega estos templates al hacer code review del Sistema de Reservas Hospital. Ajusta según necesites.

---

## ✅ Aprobación

```markdown
✅ LGTM (Looks Good To Me). Código limpio y bien documentado.
```

```markdown
✅ Aprobado. Funcionalidad probada correctamente y la lógica es clara.
```

```markdown
✅ Excelente trabajo! El modelo sigue las convenciones del DEVELOPER_GUIDE.
```

---

## 💡 Sugerencia (No bloqueante)

```markdown
💡 Sugerencia: Podrías simplificar este query SQL:

[código actual]

Alternativa:
[código sugerido]
```

```markdown
💡 Opcional: Este nombre de método podría ser más descriptivo.
¿Qué tal `getActivePatientsBySpecialty()` en lugar de `getPatients()`?
```

```markdown
💡 Tip: Considera usar el método CRUD heredado `all()` en vez de escribir
un query personalizado para obtener todos los registros.
```

---

## ⚠️ Cambio Necesario (Bloqueante)

```markdown
⚠️ Cambio requerido: Falta validación para [campo del formulario].

Sugerencia:
[código de validación]
```

```markdown
⚠️ Importante: Este query SQL es vulnerable a SQL injection.
Debes usar prepared statements con el método `query()` del modelo.
```

```markdown
⚠️ Seguridad: Falta sanitización de entrada del usuario en [línea X].
Usa `htmlspecialchars()` o valida con filter_var().
```

---

## ❓ Pregunta

```markdown
❓ ¿Por qué decidiste usar un query personalizado en lugar del método CRUD `where()`?
```

```markdown
❓ Pregunta: ¿Esta función maneja el caso cuando [condición]?
```

---

## 🐛 Bug Detectado

```markdown
🐛 Bug: Esta condición falla cuando [caso específico].

Sugerencia de fix:
[código corregido]
```

```markdown
🐛 Posible error: Estás modificando la lista mientras iteras.
Esto puede causar comportamiento inesperado.
```

---

## 🎓 Educativo

```markdown
🎓 Tip: En Python es mejor usar `is None` en lugar de `== None`.

# Menos recomendado

if x == None:

# Recomendado

if x is None:
```

```markdown
🎓 Info: Python tiene una función built-in para esto:
[ejemplo de código]
```

---

## 📝 Documentación

```markdown
📝 Por favor agrega docstring explicando qué hace esta función.
```

```markdown
📝 Sugerencia: Este parámetro no está documentado en el docstring.
```

---

## 🎨 Estilo (Nitpick)

```markdown
🎨 Nitpick: Por consistencia, usamos snake_case para funciones.

# Tu código

def calculateTotal():

# Estilo del proyecto

def calculate_total():
```

```markdown
🎨 Detalle menor: Falta una línea en blanco entre estas funciones (PEP 8).
```

---

## 🏷️ Etiquetas de Prioridad

Agregar al inicio del comentario:

- `[CRÍTICO]` - Debe corregirse inmediatamente
- `[IMPORTANTE]` - Debería corregirse antes de mergear
- `[SUGERENCIA]` - Opcional
- `[PREGUNTA]` - Necesita aclaración
- `[NITPICK]` - Detalle menor

**Ejemplo:**

```markdown
[IMPORTANTE] ⚠️ Falta validación de entrada. Por favor agrega check
para cuando el parámetro sea None.
```

---

## 📋 Respuestas del Autor

```markdown
✅ Hecho. Cambié [X] por [Y] como sugeriste.
```

```markdown
❓ No entiendo este comentario. ¿Puedes explicar más?
```

```markdown
💡 Buena idea, pero prefiero mantener [X] porque [razón].
¿Qué opinas?
```

```markdown
🙏 Gracias por la sugerencia. Lo implementé en [commit/línea].
```

---

## 🎯 Plantilla de Aprobación Final

```markdown
✅ **Aprobado**

**Cambios verificados:**

- [x] Lógica correcta
- [x] Tests pasando
- [x] Documentación actualizada

Excelente trabajo! 🎉
```
