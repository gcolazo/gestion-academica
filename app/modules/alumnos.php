<?php
require_once __DIR__ . '/../config/database.php';

function obtenerAlumnos($pdo) {
    $stmt = $pdo->query("
        SELECT a.id, a.usuario_id, a.legajo, a.apellido, a.nombre, a.email, a.telefono, a.estado, u.dni
        FROM alumnos a
        INNER JOIN usuarios u ON a.usuario_id = u.id
        ORDER BY a.apellido ASC, a.nombre ASC
    ");
    return $stmt->fetchAll();
}

function obtenerAlumnoPorId($pdo, $alumnoId) {
    $stmt = $pdo->prepare("
        SELECT a.*, u.dni
        FROM alumnos a
        INNER JOIN usuarios u ON a.usuario_id = u.id
        WHERE a.id = :id
    ");
    $stmt->execute(['id' => $alumnoId]);
    return $stmt->fetch();
}

function crearAlumno($pdo, $data) {
    try {
        $pdo->beginTransaction();

        $passHash = password_hash($data['dni'], PASSWORD_DEFAULT);
        $stmtU = $pdo->prepare("INSERT INTO usuarios (dni, password_hash, rol_id) VALUES (:dni, :pass, 3)");
        $stmtU->execute(['dni' => trim($data['dni']), 'pass' => $passHash]);
        $usuarioId = $pdo->lastInsertId();

        $stmtA = $pdo->prepare("
            INSERT INTO alumnos (usuario_id, legajo, apellido, nombre, email, telefono, estado)
            VALUES (:uid, :legajo, :apellido, :nombre, :email, :telefono, :estado)
        ");
        $stmtA->execute([
            'uid'       => $usuarioId,
            'legajo'    => trim($data['legajo']),
            'apellido'  => trim($data['apellido']),
            'nombre'    => trim($data['nombre']),
            'email'     => trim($data['email']),
            'telefono'  => trim($data['telefono'] ?? ''),
            'estado'    => $data['estado'] ?? 'Activo'
        ]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function actualizarAlumno($pdo, $data) {
    try {
        $pdo->beginTransaction();

        // 1. Actualizar DNI en la tabla usuarios
        $stmtU = $pdo->prepare("
            UPDATE usuarios SET dni = :dni 
            WHERE id = (SELECT usuario_id FROM alumnos WHERE id = :id)
        ");
        $stmtU->execute(['dni' => trim($data['dni']), 'id' => $data['id']]);

        // 2. Actualizar datos en la tabla alumnos
        $stmtA = $pdo->prepare("
            UPDATE alumnos 
            SET legajo = :legajo, apellido = :apellido, nombre = :nombre, 
                email = :email, telefono = :telefono, estado = :estado
            WHERE id = :id
        ");
        $stmtA->execute([
            'id'       => $data['id'],
            'legajo'   => trim($data['legajo']),
            'apellido' => trim($data['apellido']),
            'nombre'   => trim($data['nombre']),
            'email'    => trim($data['email']),
            'telefono' => trim($data['telefono'] ?? ''),
            'estado'   => $data['estado'] ?? 'Activo'
        ]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function eliminarAlumno($pdo, $alumnoId, $rolId) {
    // RESTRICCIÓN DE SEGURIDAD: Solo el Administrador (rol_id == 1) puede eliminar
    if ($rolId != 1) {
        return false;
    }
    $stmt = $pdo->prepare("
        DELETE FROM usuarios WHERE id = (SELECT usuario_id FROM alumnos WHERE id = :id)
    ");
    return $stmt->execute(['id' => $alumnoId]);
}

function actualizarPerfilAlumno($pdo, $usuarioId, $data) {
    try {
        $pdo->beginTransaction();

        // Actualizar datos de contacto del alumno
        $stmtA = $pdo->prepare("
            UPDATE alumnos SET email = :email, telefono = :telefono WHERE usuario_id = :uid
        ");
        $stmtA->execute([
            'email'    => trim($data['email']),
            'telefono' => trim($data['telefono'] ?? ''),
            'uid'      => $usuarioId
        ]);

        // Si ingresó una nueva contraseña, la actualizamos
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