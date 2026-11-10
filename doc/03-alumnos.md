# Especificación del Módulo de Alumnos (Versión 3 - Buscador Multicriterio Integrado)

**Asignatura**: Programación Web II  
**Proyecto**: Sistema de Gestión Académica para Profesorado en Informática  

---

## 1. Matriz de Permisos

| Rol | Crear Alumno | Editar Alumno | Eliminar Alumno | Ver Listado / Buscar | Editar Su Propio Perfil |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Administrador** |  Sí |  Sí |  Sí |  Sí |  Sí |
| **Profesor** |  Sí |  Sí | ❌ No |  Sí |  Sí |
| **Alumno** | ❌ No | ❌ No (de otros) | ❌ No | ❌ No (Redirige) |  Sí (Mis Datos) |

---

## 2. Buscador Multicriterio de Alumnos (Novedad)

### A. Descripción Funcional
Se integra un **Buscador Unificado Mobile-First** en la cabecera de `app/public/alumnos.php`. En lugar de requerir que el usuario seleccione el criterio de búsqueda (DNI, legajo, etc.) mediante menús desplegables, la barra acepta cualquier término y evalúa simultáneamente:
* **DNI**: Búsqueda por documento (coincidencia parcial o exacta).
* **Legajo**: Búsqueda por código de legajo estudiantil.
* **Nombre / Apellido**: Búsqueda por coincidencia parcial de texto.
* **Email**: Búsqueda por correo de contacto.

### B. Implementación Técnica y Seguridad PDO
Para garantizar la prevención estricta de Inyecciones SQL con las sentencias preparadas nativas de MySQL (`PDO::ATTR_EMULATE_PREPARES => false`), la función `obtenerAlumnos()` asigna marcadores de parámetros individuales (`:b1` a `:b5`) en lugar de reutilizar una misma etiqueta. Esto evita el error de PDO `SQLSTATE[HY093]: Invalid parameter number`.

```php
function obtenerAlumnos($pdo, $busqueda = '') {
    $sql = "
        SELECT a.id, a.usuario_id, a.legajo, a.estado,
               u.dni, u.apellido, u.nombre, u.email, u.telefono
        FROM alumnos a
        INNER JOIN usuarios u ON a.usuario_id = u.id
    ";

    $params = [];
    if (!empty(trim($busqueda))) {
        $term = '%' . trim($busqueda) . '%';
        $sql .= " WHERE u.dni LIKE :b1 
                     OR u.nombre LIKE :b2 
                     OR u.apellido LIKE :b3 
                     OR a.legajo LIKE :b4 
                     OR u.email LIKE :b5";
        
        $params = [
            'b1' => $term,
            'b2' => $term,
            'b3' => $term,
            'b4' => $term,
            'b5' => $term
        ];
    }

    $sql .= " ORDER BY u.apellido ASC, u.nombre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
```

### C. Componente UI Mobile-First
El formulario de búsqueda utiliza el método `GET` para conservar el parámetro `?q=` en la URL al recargar o compartir la vista. Incluye un botón dinámico de limpieza (`bi-x-lg`) que aparece únicamente cuando hay un filtro activo.

```html
<!-- Formulario de Búsqueda -->
<form action="alumnos.php" method="GET" class="row g-2 mb-3">
    <div class="col">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="search" name="q" class="form-control border-start-0 ps-0" 
                   placeholder="Buscar por DNI, Legajo, Nombre o Apellido..." 
                   value="<?= htmlspecialchars($busqueda) ?>">
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="alumnos.php" class="btn btn-outline-secondary" title="Limpiar filtro">
                <i class="bi bi-x-lg"></i>
            </a>
        <?php endif; ?>
    </div>
</form>
```

---

## 3. Flujo Funcional por Rol

### A. Administrador (Control Total)
* **Alta de Alumnos**: Crea registro de `usuarios` + `alumnos`.
* **Edición Integral**: Modifica legajo, DNI, nombre, apellido, email, teléfono y estado en modales aislados.
* **Baja Física**: Eliminación en cascada (`DELETE FROM usuarios WHERE id = ...`).
* **Búsqueda**: Filtra la nómina completa por cualquier atributo.

### B. Profesor (Gestión Académica)
* **Alta y Edición**: Registro y corrección de datos de estudiantes.
* **Sin Permiso de Borrado**: Botón oculto y acción restringida en backend.
* **Búsqueda**: Localiza rápidamente estudiantes por DNI o apellido.

### C. Alumno (Autogestión de Perfil)
* **Acceso Restringido**: Redirección automática a `perfil.php`.
* **Vista Perfil**: Formulario exclusivo para consultar datos y actualizar contraseña, email y teléfono.

---

## 4. Estructura de Páginas PHP

1. **`app/modules/alumnos.php`**: Lógica de consulta relacional y binding seguro de parámetros PDO (`obtenerAlumnos`, `crearAlumno`, `actualizarAlumno`, `eliminarAlumno`).
2. **`app/public/alumnos.php`**: Interfaz ABM con buscador integrado y modales aislados para prevenir bloqueos CSS.
3. **`app/public/perfil.php`**: Vista adaptativa de autogestión de cuenta.
