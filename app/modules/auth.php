<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function login($dni, $password, $pdo) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.dni, u.password_hash, u.nombre, u.apellido, u.rol_id, r.nombre AS rol_nombre
        FROM usuarios u
        INNER JOIN roles r ON u.rol_id = r.id
        WHERE u.dni = :dni
        LIMIT 1
    ");
    $stmt->execute(['dni' => trim($dni)]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);

        $_SESSION['user_id']     = (int) $user['id'];
        $_SESSION['dni']         = $user['dni'];
        $_SESSION['rol_id']      = (int) $user['rol_id'];
        $_SESSION['rol_nombre']  = $user['rol_nombre'];
        $_SESSION['logged_in']   = true;
        $_SESSION['nombre_comp'] = trim($user['nombre'] . ' ' . $user['apellido']);

        return true;
    }
    return false;
}

function requerirAuth() {
    if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

function actualizarMiPerfil($pdo, $usuarioId, $data) {
    try {
        $pdo->beginTransaction();

        // 1. Actualizar datos de contacto en usuarios (válido para Admin, Profesor y Alumno)
        $stmtU = $pdo->prepare("
            UPDATE usuarios 
            SET email = :email, telefono = :telefono 
            WHERE id = :uid
        ");
        $stmtU->execute([
            'email'    => trim($data['email']),
            'telefono' => trim($data['telefono'] ?? ''),
            'uid'      => $usuarioId
        ]);

        // 2. Si se especificó una nueva contraseña, actualizar el hash
        if (!empty($data['nueva_password'])) {
            $passHash = password_hash($data['nueva_password'], PASSWORD_DEFAULT);
            $stmtP = $pdo->prepare("UPDATE usuarios SET password_hash = :pass WHERE id = :uid");
            $stmtP->execute(['pass' => $passHash, 'uid' => $usuarioId]);
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
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