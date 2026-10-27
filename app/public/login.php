<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../modules/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni      = $_POST['dni'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($dni) && !empty($password)) {
        if (login($dni, $password, $pdo)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'DNI o contraseña incorrectos.';
        }
    } else {
        $error = 'Por favor complete todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Gestión Académica</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">

<div class="card login-card p-4">
    <div class="card-body">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">Gestión Académica</h4>
            <p class="text-muted small">Profesorado de Informática</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="dni" class="form-label fw-semibold small">DNI</label>
                <input type="text" class="form-control form-control-lg fs-6" id="dni" name="dni" placeholder="Ingrese su DNI sin puntos" required autofocus pattern="[0-9]{7,10}">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold small">Contraseña</label>
                <input type="password" class="form-control form-control-lg fs-6" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 fs-6 fw-bold">Ingresar</button>
        </form>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>