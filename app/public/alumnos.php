<?php
require_once __DIR__ . '/../modules/auth.php';
require_once __DIR__ . '/../modules/alumnos.php';
requerirAuth();

$rolId = $_SESSION['rol_id'];

// Redirección: Alumnos no ven el listado general; van a su perfil
if ($rolId == 3) {
    header('Location: perfil.php');
    exit;
}

$tituloPagina = 'Gestión de Alumnos';
$paginaActiva = 'alumnos';
$mensaje = '';
$tipoMensaje = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        if (crearAlumno($pdo, $_POST)) {
            $mensaje = 'Alumno registrado correctamente.';
            $tipoMensaje = 'success';
        } else {
            $mensaje = 'Error al registrar alumno (verifique si DNI o Legajo ya existen).';
            $tipoMensaje = 'danger';
        }
    } elseif ($accion === 'editar') {
        if (actualizarAlumno($pdo, $_POST)) {
            $mensaje = 'Datos del alumno actualizados correctamente.';
            $tipoMensaje = 'success';
        } else {
            $mensaje = 'Error al actualizar los datos del alumno.';
            $tipoMensaje = 'danger';
        }
    } elseif ($accion === 'eliminar') {
        if (eliminarAlumno($pdo, $_POST['id'], $rolId)) {
            $mensaje = 'Alumno eliminado del sistema.';
            $tipoMensaje = 'success';
        } else {
            $mensaje = 'No tiene permisos para eliminar o surgió un error.';
            $tipoMensaje = 'danger';
        }
    }
}

$alumnos = obtenerAlumnos($pdo);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Alumnos</h3>
            <p class="text-muted small mb-0">Gestión de matriculados</p>
        </div>
        <!-- Admin y Profesor pueden agregar nuevos alumnos -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoAlumno">
            <i class="bi bi-person-plus-fill me-1"></i>Nuevo Alumno
        </button>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show small" role="alert">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla Principal de Alumnos -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0 table-responsive-cards">
                <thead class="table-light">
                    <tr>
                        <th>Legajo</th>
                        <th>DNI</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $al): ?>
                    <tr>
                        <td data-label="Legajo"><span class="fw-bold text-primary"><?= htmlspecialchars($al['legajo']) ?></span></td>
                        <td data-label="DNI"><?= htmlspecialchars($al['dni']) ?></td>
                        <td data-label="Nombre"><?= htmlspecialchars($al['apellido'] . ', ' . $al['nombre']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($al['email']) ?></td>
                        <td data-label="Teléfono"><?= htmlspecialchars($al['telefono'] ?? '-') ?></td>
                        <td data-label="Estado">
                            <span class="badge bg-<?= $al['estado'] === 'Activo' ? 'success' : 'secondary' ?>">
                                <?= $al['estado'] ?>
                            </span>
                        </td>
                        <td data-label="Acciones" class="text-end">
                            <!-- Botón Editar (Llama al modal ubicado fuera de la tabla) -->
                            <button class="btn btn-outline-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarAlumno_<?= $al['id'] ?>">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            <!-- SOLO el Administrador puede Eliminar -->
                            <?php if ($rolId == 1): ?>
                            <form action="alumnos.php" method="POST" class="d-inline" onsubmit="return confirm('¿Confirma eliminar definitivamente a este alumno?');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $al['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- SECCIÓN DE MODALES (Ubicados FUERA de la tabla para evitar fallos de CSS) -->
<!-- ======================================================================= -->

<!-- Modales de Edición por Alumno -->
<?php foreach ($alumnos as $al): ?>
<div class="modal fade" id="modalEditarAlumno_<?= $al['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="alumnos.php" method="POST" class="modal-content">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?= $al['id'] ?>">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Editar Alumno: <?= htmlspecialchars($al['nombre'] . ' ' . $al['apellido']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Legajo</label>
                        <input type="text" name="legajo" class="form-control" value="<?= htmlspecialchars($al['legajo']) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">DNI</label>
                        <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($al['dni']) ?>" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($al['nombre']) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Apellido</label>
                        <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($al['apellido']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($al['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($al['telefono'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="Activo" <?= $al['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= $al['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<!-- Modal Nuevo Alumno -->
<div class="modal fade" id="modalNuevoAlumno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="alumnos.php" method="POST" class="modal-content">
            <input type="hidden" name="accion" value="crear">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Registrar Nuevo Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Legajo</label>
                        <input type="text" name="legajo" class="form-control" placeholder="LEG-2026-005" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">DNI (Acceso inicial)</label>
                        <input type="text" name="dni" class="form-control" placeholder="DNI sin puntos" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Crear Alumno</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>