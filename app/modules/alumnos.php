<?php
require_once __DIR__ . '/../config/database.php';

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

function obtenerAlumnoPorId($pdo, $alumnoId) {
    $stmt = $pdo->prepare("
        SELECT a.id, a.usuario_id, a.legajo, a.estado,
               u.dni, u.apellido, u.nombre, u.email, u.telefono
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

        $dni = trim($data['dni']);
        $passHash = password_hash($dni, PASSWORD_DEFAULT);

        // 1. Insertar la entidad base en usuarios (rol_id = 3 para Alumnos)
        $stmtU = $pdo->prepare("
            INSERT INTO usuarios (dni, password_hash, rol_id, apellido, nombre, email, telefono) 
            VALUES (:dni, :pass, 3, :apellido, :nombre, :email, :telefono)
        ");
        $stmtU->execute([
            'dni'      => $dni,
            'pass'     => $passHash,
            'apellido' => trim($data['apellido']),
            'nombre'   => trim($data['nombre']),
            'email'    => trim($data['email']),
            'telefono' => trim($data['telefono'] ?? '')
        ]);
        $usuarioId = $pdo->lastInsertId();

        // 2. Insertar los datos académicos en alumnos
        $stmtA = $pdo->prepare("
            INSERT INTO alumnos (usuario_id, legajo, estado)
            VALUES (:uid, :legajo, :estado)
        ");
        $stmtA->execute([
            'uid'    => $usuarioId,
            'legajo' => trim($data['legajo']),
            'estado' => $data['estado'] ?? 'Activo'
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

        // 1. Actualizar datos personales y DNI en usuarios
        $stmtU = $pdo->prepare("
            UPDATE usuarios 
            SET dni = :dni, apellido = :apellido, nombre = :nombre, 
                email = :email, telefono = :telefono 
            WHERE id = (SELECT usuario_id FROM alumnos WHERE id = :id)
        ");
        $stmtU->execute([
            'id'       => $data['id'],
            'dni'      => trim($data['dni']),
            'apellido' => trim($data['apellido']),
            'nombre'   => trim($data['nombre']),
            'email'    => trim($data['email']),
            'telefono' => trim($data['telefono'] ?? '')
        ]);

        // 2. Actualizar legajo y estado en alumnos
        $stmtA = $pdo->prepare("
            UPDATE alumnos 
            SET legajo = :legajo, estado = :estado 
            WHERE id = :id
        ");
        $stmtA->execute([
            'id'     => $data['id'],
            'legajo' => trim($data['legajo']),
            'estado' => $data['estado'] ?? 'Activo'
        ]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function eliminarAlumno($pdo, $alumnoId, $rolId) {
    // RESTRICCIÓN: Solo el Administrador (rol_id == 1) puede eliminar
    if ($rolId != 1) {
        return false;
    }

    // Al borrar la fila en usuarios, MySQL elimina automáticamente en alumnos por ON DELETE CASCADE
    $stmt = $pdo->prepare("
        DELETE FROM usuarios WHERE id = (SELECT usuario_id FROM alumnos WHERE id = :id)
    ");
    return $stmt->execute(['id' => $alumnoId]);
}