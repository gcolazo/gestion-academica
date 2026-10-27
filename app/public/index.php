<?php
require_once __DIR__ . '/../modules/auth.php';
requerirAuth();

$tituloPagina = 'Panel de Control';
$paginaActiva = 'dashboard';

// Consultas rápidas para KPI (Indicadores)
$stmtAlumnos = $pdo->query("SELECT COUNT(*) FROM alumnos WHERE estado = 'Activo'");
$totalAlumnos = $stmtAlumnos->fetchColumn();

$stmtCursos = $pdo->query("SELECT COUNT(*) FROM cursos");
$totalCursos = $stmtCursos->fetchColumn();

$stmtProfesores = $pdo->query("SELECT COUNT(*) FROM profesores");
$totalProfesores = $stmtProfesores->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container content-wrapper">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark">Bienvenido/a, <?= htmlspecialchars($_SESSION['nombre_comp']) ?> 👋</h3>
            <p class="text-muted">Panel general de administración académica.</p>
        </div>
    </div>

    <!-- KPI Cards (Mobile-First: 1 columna en móvil, 3 en escritorio) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary fs-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase fw-bold">Alumnos Activos</h6>
                        <h2 class="mb-0 fw-bold"><?= $totalAlumnos ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success fs-3">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase fw-bold">Cursos Dictados</h6>
                        <h2 class="mb-0 fw-bold"><?= $totalCursos ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 text-warning fs-3">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase fw-bold">Docentes Registrados</h6>
                        <h2 class="mb-0 fw-bold"><?= $totalProfesores ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>