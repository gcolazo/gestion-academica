# 📋 Tablero de Gestión de Proyecto (Kanban / Trello)

**Proyecto:** Sistema de Gestión Académica - Profesorado de Informática  
**Metodología:** Desarrollo Ágil e Iterativo por Módulos  
**Estado General:** Fase 3 Completa + Buscador Multicriterio | Listo para Presentación de Parcial / Validación con Docente  

---

## 📊 Vista General del Tablero (Trello Board)

```text
┌─────────────────────────┐  ┌─────────────────────────┐  ┌─────────────────────────┐  ┌─────────────────────────┐
│  📥 BACKLOG (PENDIENTES) │  │   🔄 EN PROGRESO (WIP)   │  │   🔍 EN REVISIÓN (QA)   │  │     ✅ HECHO (DONE)     │
├─────────────────────────┤  ├─────────────────────────┤  ├─────────────────────────┤  ├─────────────────────────┤
│ • CRUD Cursos           │  │ • Presentación Parcial  │  │ • Prueba de Búsqueda por│  │ • Docker Compose Setup  │
│ • Inscripciones y Actas │  │ • Feedback del Profesor │  │   DNI / Legajo / Texto  │  │ • DB 3FN Supertipo/Sub  │
│ • CRUD Profesores       │  │ • Validación de Alcance │  │ • Verificación PDO      │  │ • Auth / BCrypt / PDO   │
│ • Reportes / Exportación│  │                         │  │   (anti SQLi `:b1..b5`) │  │ • Buscador Multicriterio│
└─────────────────────────┘  └─────────────────────────┘  └─────────────────────────┘  └─────────────────────────┘
```

---

## 📌 Detalle de Tareas Completadas (DONE)

### 🐳 Infraestructura & Entorno Local
- [x] **Contenedores Docker**: Configuración en `compose.yaml` de servicio web (Apache + PHP 8.4) y servicio DB (MySQL 8.4 InnoDB).
- [x] **Persistencia y Redes**: Volumen persistente para MySQL, red bridge aislada entre PHP y MySQL e inicialización automática de BD.
- [x] **Recursos Estáticos Locales**: Inclusión física de Bootstrap 5.3 y Bootstrap Icons (`bootstrap.min.css`, `bootstrap-icons.min.css`, `fonts/`, `bootstrap.bundle.min.js`) en `app/public/` para funcionamiento 100% offline.

### 🗄️ Modelo de Datos & Normalización (3FN)
- [x] **Patrón Supertipo / Subtipo**: Centralización de datos personales (`apellido`, `nombre`, `email`, `telefono`, `dni`, `password_hash`) en la tabla base `usuarios`.
- [x] **Especialización de Entidades**: Relación 1:1 estricta con `alumnos` (`legajo`, `estado`) y `profesores`.
- [x] **Normalización 3FN**: Integración de tablas de catálogo (`roles`, `estados_cursada`) eliminando redundancias transitivas.
- [x] **Integridad Referencial & UTF-8**: Cotejamiento `utf8mb4_unicode_ci` y reglas `ON DELETE CASCADE` / `RESTRICT`.

### 🔐 Seguridad & Control de Acceso
- [x] **Hashing de Contraseñas**: Encriptación mediante `password_hash()` con algoritmo BCrypt por defecto y login por DNI.
- [x] **Prevención SQL Injection**: Sentencias preparadas PDO obligatorias con emulación desactivada (`PDO::ATTR_EMULATE_PREPARES => false`).
- [x] **Sanitización XSS**: Escape sistemático de variables de salida con `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.
- [x] **Control de Sesiones**: Inicio seguro con `session_regenerate_id(true)` y destrucción completa en `logout.php`.
- [x] **Matriz RBAC por Roles**: Restricción de acceso a vistas según rol (`1: Administrador`, `2: Profesor`, `3: Alumno`).

### 📱 Interfaz Mobile-First & Layout
- [x] **Encabezado y Footer Modulares**: Plantillas reutilizables en `app/includes/header.php` y `app/includes/footer.php`.
- [x] **Navegación Dinámica (`navbar.php`)**: Menú responsive que adapta sus enlaces según el rol del usuario en sesión.
- [x] **Panel de Control (`index.php`)**: Dashboard con tarjetas KPI para métricas principales (Total Alumnos, Cursos, Profesores).

### 👨‍🎓 Módulo de Alumnos & Búsqueda
- [x] **Listado Adaptativo (`alumnos.php`)**: Transformación mediante CSS `@media` de tabla tradicional (Desktop) a tarjetas individuales (Móviles).
- [x] **Alta de Alumno**: Registro mediante Modal con creación de usuario y asignación automática de clave inicial (igual al DNI).
- [x] **Edición de Alumno**: Corrección de posicionamiento renderizando los modales fuera de la estructura de la tabla para evitar transparencias o bloqueos visuales.
- [x] **Eliminación Restringida**: Botón de borrado visible y ejecutable únicamente para el Administrador (Profesor no puede eliminar).
- [x] **[FUN-02.1] Buscador Multicriterio de Alumnos**:
  - Formulario `GET` Mobile-First con botón de limpieza (`bi-x-lg`) y feedback visual.
  - Filtrado simultáneo por DNI, Legajo, Nombre, Apellido y Email.
  - Binding seguro con marcadores PDO independientes (`:b1` a `:b5`) previniendo `SQLSTATE[HY093]`.
  - Vista limpia de estado cuando no existen coincidencias.

### 👤 Autogestión de Perfil (`perfil.php`)
- [x] **Edición Universal**: Permite a cualquier usuario (Admin, Profesor o Alumno) actualizar su email y teléfono de contacto.
- [x] **Cambio de Contraseña**: Permite cambiar la clave de acceso re-encriptándola en BCrypt.
- [x] **Redirección de Alumnos**: Si un alumno intenta acceder a `alumnos.php`, el sistema lo redirige automáticamente a `perfil.php`.

### 📄 Documentación Técnica Completa
- [x] `README.md` (Portal principal del proyecto e instrucciones Docker).
- [x] `doc/00-tablero-proyecto.md` (Metodología y seguimiento Kanban).
- [x] `doc/01-modelo-datos.md` (Modelo Entidad-Relación Mermaid y 3FN).
- [x] `doc/02-seguridad.md` (Mecanismos de seguridad y diagramas de secuencia).
- [x] `doc/03-alumnos.md` (Especificación del módulo de estudiantes y buscador).

---

## 🔍 En Revisión / Pruebas (QA)

- [x] **Prueba de Búsqueda Multicriterio**: Verificación de filtrado correcto por DNI, Legajo, Nombre, Apellido y Email.
- [x] **Verificación PDO Strict**: Validación de compatibilidad con `PDO::ATTR_EMULATE_PREPARES => false` evitando errores de binding.
- [/] **Auditoría de Permisos RBAC**: Comprobación del comportamiento para Admin, Profesor y Alumno.

---

## 🔄 En Proceso (WIP)

- [ ] **Validación de Parcial con el Profesor**: Presentación de propuesta de arquitectura, normalización 3FN y demostración del sistema funcionando hasta la Fase 3 + Buscador.
- [ ] **Revisión de Alcance para el Final**: Determinar junto al docente si se requiere implementar el módulo de Cursos e Inscripciones o si el alcance actual satisface el requerimiento final.

---

## 📥 Backlog (Pendientes para la Entrega Final / Fase 4)

- [ ] **CRUD de Cursos / Materias**: Creación y gestión de asignaturas y períodos lectivos.
- [ ] **Asignación Docente (`curso_profesor`)**: Vinculación de profesores a cursos.
- [ ] **Inscripción de Alumnos (`inscripciones`)**: Matriculación de estudiantes en materias.
- [ ] **Carga de Calificaciones y Acta**: Registro de nota final y estado de cursada (Promocionó, Regular, Libre).
- [ ] **Reportes e Historia Académica**: Exportación y consulta de historial académico del estudiante.

---
