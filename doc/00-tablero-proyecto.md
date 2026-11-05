# Plan de Proyecto y Tablero Kanban (Trello)

**Asignatura:** Programación Web II / Profesorado de Informática  
**Proyecto:** Sistema de Gestión Académica Mobile-First  
**Criterio de Evaluación Target:** 10/10 (Rigor Metodológico, 3FN, Seguridad y Mobile-First)

---

## 📊 Tablero Kanban del Proyecto

```text
┌───────────────────────────────────┬───────────────────────────────────┬───────────────────────────────────┐
│       📥 BACKLOG / PENDIENTE      │        🔄 EN PROGRESO (WIP)       │          ✅ HECHO (DONE)          │
├───────────────────────────────────┼───────────────────────────────────┼───────────────────────────────────┤
│ • Módulo de Cursos / Materias     │ • Presentación de propuesta para  │ • Orquestación Docker Compose     │
│   (Alta, modificación y bajas).   │   el Parcial.                     │   (PHP 8.4 + MySQL 8.4 InnoDB).   │
│ • Asignación de Profesores        │ • Solicitud de Feedback y         │ • Modelo DB Normalizado (3FN)     │
│   a Cursos/Comisiones (Relación   │   validación con el Profesor.     │   Patrón Supertipo/Subtipo.       │
│   M:N `curso_profesor`).          │                                   │ • Autenticación y Seguridad       │
│ • Inscripción de Alumnos a        │                                   │   (Login por DNI + Hash BCrypt).  │
│   Cursos (Relación M:N            │                                   │ • Layout Base Mobile-First        │
│   `inscripciones`).               │                                   │   (Bootstrap 5.3 + Icons Local).  │
│ • Acta de Calificaciones y Carga  │                                   │ • CRUD de Alumnos Adaptativo      │
│   de Notas Finales.               │                                   │   (Modales aislados + Permisos).  │
│ • Reportes y Consultas de         │                                   │ • Autogestión de Perfil           │
│   Rendimiento Académico.          │                                   │   (`perfil.php` para todos).      │
│                                   │                                   │ • Documentación Técnica Completa  │
│                                   │                                   │   (README, 01, 02, 03 y 00).      │
└───────────────────────────────────┴───────────────────────────────────┴───────────────────────────────────┘
```

---

## 📑 Detalle de Columnas del Tablero

### 1ª Columna: 📥 BACKLOG / PENDIENTE
Requerimientos planificados para la siguiente fase o de cara al **Examen Final**:
* **HU-05: Gestión de Cursos / Materias**: CRUD de asignaturas, comisiones y períodos lectivos.
* **HU-06: Asignación Docente**: Asociación M:N entre profesores y asignaturas dictadas (`curso_profesor`).
* **HU-07: Matriculación de Alumnos**: Inscripción M:N de estudiantes a cursos (`inscripciones`).
* **HU-08: Carga de Calificaciones**: Registro de estados de cursada (Regular, Promocionado, Libre) y notas finales.
* **HU-09: Reportes Académicos**: Vista consolidada de historia académica por alumno y planilla de cursada por docente.

### 2ª Columna: 🔄 EN PROGRESO (WIP)
Tareas en ejecución para la entrega e hito del **Parcial**:
* **HU-04: Presentación e Integración de Documentación**: Consolidación del repositorio, portal `README.md` y guía de navegación en `doc/`.
* **HU-00: Solicitud de Feedback al Profesor**: Envío de la propuesta técnica y modelo de datos para validación docente.

### 3ª Columna: ✅ HECHO (DONE)
Entregables técnicos finalizados, probados y verificados:
* **INF-01: Containerización Docker**: Configuración de `compose.yaml` y `Dockerfile` con PHP 8.4 (`pdo_mysql`) y MySQL 8.4.
* **BD-01: Modelo Relacional 3FN**: Implementación del esquema de base de datos con patrón **Supertipo / Subtipo** (`usuarios` centralizado con `alumnos` y `profesores` en 1:1), cotejamiento `utf8mb4_unicode_ci` e integridad referencial (`ON DELETE CASCADE` / `RESTRICT`).
* **SEG-01: Módulo de Seguridad y Login**: Inicio de sesión por DNI, hashing de contraseñas con **BCrypt**, sentencias preparadas PDO estrictas (anti SQLi), prevención XSS (`htmlspecialchars`) y regeneración de ID de sesión.
* **UI-01: Layout Base Mobile-First**: Plantilla adaptativa responsive con Bootstrap 5.3 y Bootstrap Icons totalmente alojados en local para soporte offline.
* **ALU-01: Listado Adaptativo de Alumnos**: Conversión dinámica mediante CSS de tabla de escritorio a tarjetas en dispositivos móviles.
* **ALU-02: Alta y Edición de Alumnos**: Modales Bootstrap independientes fuera de la tabla para evitar solapamientos y problemas de transparencia CSS.
* **ALU-03: Control de Permisos por Rol (RBAC)**: Eliminación de registros restringida exclusivamente al Administrador (Profesor puede crear/editar; Alumno redirigido a perfil).
* **PER-01: Autogestión de Perfil (`perfil.php`)**: Actualización de correo, teléfono y cambio opcional de clave para Administradores, Profesores y Alumnos.
* **DOC-01: Documentación Técnica Markdown**: Elaboración de `README.md`, `doc/01-modelo-datos.md`, `doc/02-seguridad.md`, `doc/03-alumnos.md` y `doc/00-tablero-proyecto.md`.

---

## 🎯 Criterios de Evaluación y Cumplimiento (Target 10/10)

| Criterio Evaluado por el Profesor | Estado | Evidencia / Ubicación |
| :--- | :---: | :--- |
| **1. Normalización de DB (3FN estricta)** |  **100%** | Patrón Supertipo/Subtipo en `usuarios` + 3FN en `doc/01-modelo-datos.md` y `database/schema.sql`. |
| **2. Seguridad y Hash de Contraseñas** |  **100%** | `password_hash` BCrypt, PDO anti-SQLi y sanitización XSS en `doc/02-seguridad.md` y `app/modules/auth.php`. |
| **3. UI Mobile-First Real** |  **100%** | Cards responsivas en móviles, Bootstrap 5 local en `app/includes/` y `doc/03-alumnos.md`. |
| **4. Entorno Containerizado (Docker)** |  **100%** | Orquestación en `compose.yaml` e instrucciones de despliegue en `README.md`. |
| **5. Gestión de Proyecto y Documentación** |  **100%** | Tablero Kanban Trello en `doc/00-tablero-proyecto.md` e índice navegable en `README.md`. |
