<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?? 'Gestión Académica' ?></title>
    <!-- Bootstrap 5 CSS y Bootstrap Icons -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; min-height: 100vh; display: flex; flex-direction: column; }
        .content-wrapper { flex: 1; }
        @media (max-width: 767.98px) {
            .table-responsive-cards thead { display: none; }
            .table-responsive-cards tr { display: block; margin-bottom: 1rem; border: 1px solid #dee2e6; border-radius: 8px; background: #fff; padding: 0.5rem; }
            .table-responsive-cards td { display: flex; justify-content: space-between; border: none; border-bottom: 1px solid #f0f0f0; }
            .table-responsive-cards td::before { content: attr(data-label); font-weight: bold; margin-right: 1rem; color: #6c757d; }
        }
    </style>
</head>
<body>

