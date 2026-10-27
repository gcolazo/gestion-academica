<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function login($dni, $password, $pdo) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.dni, u.password_hash, u.rol_id, r.nombre AS rol_nombre
        FROM usuarios u
        INNER JOIN roles r ON u.rol_id = r.id
        WHERE u.dni = :dni
        LIMIT 1
    ");
    $stmt->execute(['dni' => trim($dni)]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);

        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['dni']        = $user['dni'];
        $_SESSION['rol_id']     = (int) $user['rol_id'];
        $_SESSION['rol_nombre'] = $user['rol_nombre'];
        $_SESSION['logged_in']  = true;
        $_SESSION['nombre_comp'] = obtenerNombreUsuario($user['id'], $user['rol_id'], $pdo);

        return true;
    }
    return false;
}

function obtenerNombreUsuario($usuario_id, $rol_id, $pdo) {
    if ($rol_id == 2) { // Profesor
        $stmt = $pdo->prepare("SELECT nombre, apellido FROM profesores WHERE usuario_id = :uid");
        $stmt->execute(['uid' => $usuario_id]);
        $p = $stmt->fetch();
        return $p ? $p['nombre'] . ' ' . $p['apellido'] : 'Profesor';
    } elseif ($rol_id == 3) { // Alumno
        $stmt = $pdo->prepare("SELECT nombre, apellido FROM alumnos WHERE usuario_id = :uid");
        $stmt->execute(['uid' => $usuario_id]);
        $a = $stmt->fetch();
        return $a ? $a['nombre'] . ' ' . $a['apellido'] : 'Alumno';
    }
    return 'Administrador';
}

function requerirAuth() {
    if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: login.php');
    exit;
}

function actualizarMiPerfil($pdo, $usuarioId, $rolId, $data) {
    try {
        $pdo->beginTransaction();

        // 1. Si es Profesor, actualiza email y teléfono en la tabla profesores
        if ($rolId == 2) {
            $stmt = $pdo->prepare("UPDATE profesores SET email = :email, telefono = :telefono WHERE usuario_id = :uid");
            $stmt->execute([
                'email'    => trim($data['email']),
                'telefono' => trim($data['telefono'] ?? ''),
                'uid'      => $usuarioId
            ]);
        }
        // 2. Si es Alumno, actualiza email y teléfono en la tabla alumnos
        elseif ($rolId == 3) {
            $stmt = $pdo->prepare("UPDATE alumnos SET email = :email, telefono = :telefono WHERE usuario_id = :uid");
            $stmt->execute([
                'email'    => trim($data['email']),
                'telefono' => trim($data['telefono'] ?? ''),
                'uid'      => $usuarioId
            ]);
        }

        // 3. Para cualquier rol (incluido Admin): si ingresó contraseña nueva, la actualiza
        if (!empty($data['nueva_password'])) {
            $passHash = password_hash($data['nueva_password'], PASSWORD_DEFAULT);
            $stmtU = $pdo->prepare("UPDATE usuarios SET password_hash = :pass WHERE id = :uid");
            $stmtU->execute(['pass' => $passHash, 'uid' => $usuarioId]);
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}