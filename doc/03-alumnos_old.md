# Especificación del Módulo de Alumnos

**Asignatura**: Programación Web II  
**Proyecto**: Sistema de Gestión Académica para Profesorado en Informática  
**Módulo**: 03 - Gestión de Alumnos  

---

## 1. Descripción del Módulo

El módulo de **Gestión de Alumnos** permite administrar el ciclo de vida de los estudiantes matriculados en la institución. Siguiendo el requerimiento de diseño **Mobile-First**, la interfaz adapta la visualización de datos priorizando tarjetas (*Cards*) en dispositivos móviles y tablas responsivas en pantallas de escritorio.

---

## 2. Alcance y Permisos por Rol

| Acceso / Acción | Administrador | Profesor | Alumno |
| :--- | :---: | :---: | :---: |
| **Listar Alumnos** | ✅ Completo | ✅ Solo de sus Cursos | ❌ Sin acceso |
| **Alta de Alumno** | ✅ Habilitado | ❌ Sin acceso | ❌ Sin acceso |
| **Edición de Datos** | ✅ Habilitado | ❌ Sin acceso | ❌ Sin acceso |
| **Eliminación Físicas** | ✅ Habilitado (`CASCADE`) | ❌ Sin acceso | ❌ Sin acceso |

---

## 3. Estructura de Datos y Validaciones

Cada registro de Alumno requiere los siguientes atributos:

* **ID**: Clave Primaria Autoincremental (`INT`).
* **Legajo**: Identificador académico único (`LEG-YYYY-XXX`, `UNIQUE`).
* **Apellido y Nombre**: Cadenas de texto obligatorias (`VARCHAR(100)`).
* **DNI**: Identificador de inicio de sesión mapeado a la tabla `usuarios` (`VARCHAR(20)`).
* **Email**: Correo institucional o personal único (`VARCHAR(150)`).
* **Teléfono**: Contacto telefónico opcional (`VARCHAR(50)`).
* **Estado**: Estado de matrícula (`ENUM('Activo', 'Inactivo')`).

### Integridad Referencial
La creación de un Alumno implica la inserción atómica (Transacción PDO):
1. Inserción en la tabla `usuarios` (generando su credencial con contraseña predeterminada por DNI).
2. Inserción en la tabla `alumnos` vinculada mediante `usuario_id` (`FOREIGN KEY`).
3. El borrado de un alumno ejecuta un `DELETE` sobre la tabla `usuarios`, desencadenando `ON DELETE CASCADE` hacia la ficha de alumno y sus inscripciones.

---

## 4. Diseño de Interfaz (Mobile-First)

* **Vista Móvil (< 768px)**: Presentación en tarjetas colapsables con botones flotantes o accesos directos para llamar/enviar mail.
* **Vista Escritorio (≥ 768px)**: Tabla compacta con ordenamiento y buscador rápido en tiempo real.
* **Formulario Modal**: Alta y edición mediante un cuadro modal para evitar recargas completas de página.
