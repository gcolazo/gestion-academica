<?php
require_once __DIR__ . '/../modules/auth.php';
requerirAuth();

$tituloPagina = 'Mi Perfil';
$paginaActiva = 'perfil';
$usuarioId = $_SESSION['user_id'];
$mensaje   = '';
$tipoMensaje = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (actualizarMiPerfil($pdo, $usuarioId, $_POST)) {
        $mensaje = 'Tus datos fueron actualizados correctamente.';
        $tipoMensaje = 'success';
    } else {
        $mensaje = 'Ocurrió un error al actualizar tus datos.';
        $tipoMensaje = 'danger';
    }
}

// Consultar datos unificados del usuario + legajo (si aplica)
$stmt = $pdo->prepare("
    SELECT u.dni, u.apellido, u.nombre, u.email, u.telefono, r.nombre AS rol_nombre, a.legajo
    FROM usuarios u
    INNER JOIN roles r ON u.rol_id = r.id
    LEFT JOIN alumnos a ON a.usuario_id = u.id
    WHERE u.id = :uid
");
$stmt->execute(['uid' => $usuarioId]);
$usuario = $stmt->fetch();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container content-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-person-badge-fill me-2"></i>Mi Perfil - <?= htmlspecialchars($usuario['rol_nombre']) ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show small" role="alert">
                            <?= htmlspecialchars($mensaje) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="perfil.php" method="POST">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small text-muted fw-bold">DNI</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario['dni']) ?>" readonly>
                            </div>
                            <?php if (!empty($usuario['legajo'])): ?>
                            <div class="col-6">
                                <label class="form-label small text-muted fw-bold">Legajo</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario['legajo']) ?>" readonly>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>" readonly>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Teléfono de Contacto</label>
                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                        </div>

                        <hr class="my-3">

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Cambiar Contraseña (Opcional)</label>
                            <input type="password" name="nueva_password" class="form-control" placeholder="Dejar en blanco para conservar la clave actual">
                            <div class="form-text small">Si completa este campo, su clave de acceso se actualizará.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="bi bi-save me-1"></i>Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>