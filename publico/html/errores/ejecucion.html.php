<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error del sistema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .contenedor {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .titulo {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .detalle {
            font-size: 14px;
            background: #eee;
            padding: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="titulo">Se ha producido un error</div>
        <p><strong>Mensaje:</strong> <?= htmlentities($mensaje) ?></p>
        <p><strong>Archivo:</strong> <?= htmlentities($archivo) ?></p>
        <p><strong>Línea:</strong> <?= htmlentities($linea) ?></p>
        <div class="detalle"><?= htmlentities($traza) ?></div>
    </div>
</body>
</html>
