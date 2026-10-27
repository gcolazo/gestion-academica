# Especificación del Módulo de Alumnos (Versión 2 - Matriz de Permisos Ampliada)

**Asignatura**: Programación Web II  
**Proyecto**: Sistema de Gestión Académica para Profesorado en Informática  

---

## 1. Matriz de Permisos Ajustada

Atendiendo a las nuevas definiciones funcionales, se establece la siguiente matriz de control de acceso por Rol:

| Rol | Crear Alumno | Editar Alumno | Eliminar Alumno | Ver Listado | Editar Su Propio Perfil |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Administrador** |  Sí |  Sí |  Sí |  Sí |  Sí |
| **Profesor** |  Sí |  Sí | ❌ No |  Sí |  Sí |
| **Alumno** | ❌ No | ❌ No (de otros) | ❌ No | ❌ No |  Sí (Mis Datos) |

---

## 2. Flujo Funcional por Rol

### A. Administrador (Control Total)
* **Alta de Alumnos**: Crea registro de `usuarios` + `alumnos`.
* **Edición Integral**: Modifica legajo, DNI, nombre, apellido, email, teléfono y estado (Activo/Inactivo).
* **Baja Física**: Eliminación en cascada (`DELETE FROM usuarios WHERE id = ...`).

### B. Profesor (Gestión Académica)
* **Alta de Alumnos**: Puede dar de alta alumnos en el sistema.
* **Edición de Alumnos**: Puede corregir/actualizar datos personales y de contacto de cualquier alumno.
* **Sin Permiso de Borrado**: No se muestra el botón de eliminación; la acción en backend está restringida.

### C. Alumno (Autogestión de Perfil)
* **Acceso Restringido a `alumnos.php`**: Redirección automática si intenta acceder al listado general de alumnos.
* **Vista `perfil.php`**: Formulario exclusivo donde el alumno puede visualizar y actualizar su Email, Teléfono y cambiar su Contraseña de acceso.

---

## 3. Estructura de Endpoints / Páginas PHP

1. **`app/modules/alumnos.php`**: Contiene la lógica relacional PDO (`crearAlumno`, `actualizarAlumno`, `actualizarPerfilAlumno`, `eliminarAlumno`).
2. **`app/public/alumnos.php`**: Gestión de ABM para Administradores y Profesores.
3. **`app/public/perfil.php`**: Vista Mobile-First de autogestión de perfil para el Alumno.
