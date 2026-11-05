# 03. Módulo de Gestión de Alumnos y Autogestión de Perfil

## 📱 Alcance Funcional y Diseño Mobile-First

El módulo de alumnos brinda una solución integral para la matriculación y administración de estudiantes, optimizada tanto para computadoras de escritorio como para dispositivos móviles mediante **Bootstrap 5**:

1. **Vistas Adaptativas (Mobile-First)**:
   * En pantallas grandes, presenta una tabla tradicional interactiva.
   * En pantallas pequeñas (< 768px), las filas se transforman dinámicamente en **tarjetas verticales (Cards)** mediante la clase CSS `.table-responsive-cards` y atributos `data-label`.
2. **Corrección de Renderizado de Modales**:
   * Los formularios de edición e inserción emergentes (`<div class="modal fade">`) se ubican al final del documento HTML, fuera de las etiquetas `<table>` y `.card-body`, garantizando fondos sólidos opacos y una interacción de formularios fluida sin solapamientos.

---

## ⚙️ Operaciones Atómicas con Transacciones PDO

Dado que la información del alumno reside de forma distribuida entre la tabla de identidad (`usuarios`) y la tabla de especialización (`alumnos`), todas las operaciones de alta y modificación utilizan **Transacciones PDO** (`beginTransaction()`, `commit()`, `rollBack()`).

### 1. Alta de Alumno (`crearAlumno`)
1. Genera el registro de usuario en `usuarios` guardando `dni`, `apellido`, `nombre`, `email`, `telefono`, asignando `rol_id = 3` y la contraseña inicial (igual al DNI encriptada con bcrypt).
2. Obtiene el ID generado (`$pdo->lastInsertId()`).
3. Inserta el registro en `alumnos` con el `legajo`, `estado` y el `usuario_id` vinculado.
4. En caso de duplicidad de DNI, Legajo o Email, la transacción realiza un `rollBack()` informando el error al usuario.

### 2. Edición de Alumno (`actualizarAlumno`)
1. Actualiza `dni`, `apellido`, `nombre`, `email` y `telefono` en la tabla `usuarios`.
2. Actualiza `legajo` y `estado` en la tabla `alumnos`.
3. Confirma los cambios mediante `commit()`.

### 3. Eliminación con Restricción de Rol (`eliminarAlumno`)
* **Seguridad de Servidor**: La función verifica que `$_SESSION['rol_id'] == 1` (Administrador).
* Si un Profesor intenta enviar una petición de borrado, la función retorna `false` y aborta la acción.
* Al ejecutar el `DELETE FROM usuarios WHERE id = ...`, la base de datos elimina automáticamente la fila correspondiente en `alumnos` por la regla **`ON DELETE CASCADE`**.

---

## 👤 Autogestión de Perfil de Alumno (`perfil.php`)

Para promover la autonomía del estudiante y la actualización de sus datos de contacto:

* **Acceso Directo**: Cuando un alumno (Rol 3) inicia sesión o intenta acceder al listado general `alumnos.php`, es redirigido automáticamente a `perfil.php`.
* **Modificación de Contacto**: El alumno puede actualizar de forma independiente su `email` y `telefono`.
* **Cambio Voluntario de Contraseña**: Permite ingresar una clave nueva para reemplazar su contraseña inicial por defecto. Si el campo se deja en blanco, la clave previa se conserva sin cambios.
