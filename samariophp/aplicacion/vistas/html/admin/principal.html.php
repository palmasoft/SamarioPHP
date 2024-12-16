<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - SamarioPHP</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{config.app.url_base}}/css/estilos.css">
        <style>
            /* Estilos adicionales */
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                background-color: #f4f4f4;
            }
            .header {
                background-color: #007bff;
                color: #fff;
                padding: 15px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .header .logo {
                display: flex;
                align-items: center;
            }
            .header .logo img {
                height: 40px;
                margin-right: 10px;
            }
            .header .logo span {
                font-size: 20px;
                font-weight: bold;
            }
            .header .user-menu a {
                color: #fff;
                margin-left: 15px;
                text-decoration: none;
                font-weight: 500;
            }
            .sidebar {
                width: 200px;
                background-color: #333;
                color: #fff;
                position: fixed;
                top: 60px;
                bottom: 0;
                padding: 20px;
            }
            .sidebar ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .sidebar ul li {
                margin: 15px 0;
            }
            .sidebar ul li a {
                color: #fff;
                text-decoration: none;
                display: block;
            }
            .content {
                margin-left: 220px;
                padding: 20px;
            }
            .welcome {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .card {
                background-color: #fff;
                padding: 20px;
                margin-bottom: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .card h3 {
                margin: 0 0 10px;
            }
        </style>
    </head>
    <body>
        <!-- Barra superior -->
        <header class="header">
            <div class="logo">
                <img src="{{config.app.url_base}}imagenes/samarioPHP.png" alt="SamarioPHP Logo">
                <span>SamarioPHP</span>
            </div>
            <div class="user-menu">
                <a href="#">Perfil</a>
                <a href="#">Cerrar Sesión</a>
            </div>
        </header>

        <!-- Menú lateral -->
        <aside class="sidebar">
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Gestión de Usuarios</a></li>
                <li><a href="#">Reportes</a></li>
                <li><a href="#">Configuración</a></li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="content">
            <div class="welcome">Bienvenido, {{usuario.nombre}}.</div>
            
            <div class="card">
                <h3>Estadísticas Generales</h3>
                <p>Accesos al sistema: {{estadisticas.accesos}}</p>
                <p>Usuarios registrados: {{estadisticas.usuarios}}</p>
            </div>

            <div class="card">
                <h3>Accesos Rápidos</h3>
                <ul>
                    <li><a href="#">Registrar nuevo usuario</a></li>
                    <li><a href="#">Ver reportes</a></li>
                </ul>
            </div>
        </main>
    </body>
</html>
