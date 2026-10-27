<?php
$rolId = $_SESSION['rol_id'] ?? 0;
$nombreUsuario = $_SESSION['nombre_comp'] ?? 'Usuario';
$rolNombre = $_SESSION['rol_nombre'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-mortarboard-fill me-2"></i>Gestión Académica
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaActiva ?? '') === 'dashboard' ? 'active' : '' ?>" href="index.php">
                        <i class="bi bi-speedometer2 me-1"></i>Inicio
                    </a>
                </li>
                
                <?php if ($rolId == 1 || $rolId == 2): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaActiva ?? '') === 'alumnos' ? 'active' : '' ?>" href="alumnos.php">
                        <i class="bi bi-people-fill me-1"></i>Alumnos
                    </a>
                </li>
                <?php endif; ?>

                <!-- Enlace visible para TODOS los roles -->
                <li class="nav-item">
                    <a class="nav-link <?= ($paginaActiva ?? '') === 'perfil' ? 'active' : '' ?>" href="perfil.php">
                        <i class="bi bi-person-circle me-1"></i>Mi Perfil
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center text-white">
                <div class="me-3 text-end d-none d-lg-block">
                    <div class="fw-bold small"><?= htmlspecialchars($nombreUsuario) ?></div>
                    <span class="badge bg-light text-primary small"><?= htmlspecialchars($rolNombre) ?></span>
                </div>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Salir
                </a>
            </div>
        </div>
    </div>
</nav>