
<?php

try {
    require __DIR__ . '/../config/database.php';

    $stmt = $pdo->query('SELECT VERSION() AS version');
    $result = $stmt->fetch();

    echo '<h1>Conexión exitosa</h1>';
    echo '<p>PHP se conectó correctamente a MySQL.</p>';
    echo '<p>Versión de MySQL: '
        . htmlspecialchars($result['version'], ENT_QUOTES, 'UTF-8')
        . '</p>';

} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Error de conexión</h1>';
    echo '<p>No se pudo comprobar la conexión con la base de datos.</p>';
}
