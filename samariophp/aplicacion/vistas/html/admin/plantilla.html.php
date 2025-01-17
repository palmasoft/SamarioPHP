<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SamarioPHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="{{config.app.url_base}}/css/admin.css">

</head>
<body>
    <!-- Barra superior -->
    <header class="header">
        <div class="header__logo">
            <img src="{{config.app.url_base}}/imagenes/samarioPHP.png" alt="Logo" class="header__logo-img">
            <span>SamarioPHP</span>
        </div>
        <div class="header__user-menu">
            <a href="/perfil" class="header__user-link">Perfil</a>
            <a href="/cerrar-sesion" class="header__user-link">Cerrar Sesión</a>
        </div>
    </header>

    <div class="d-flex">
        <!-- Menú lateral -->
        <aside class="sidebar">
            <ul class="sidebar__menu-list">
                <li class="sidebar__menu-item">
                    <a href="#" class="sidebar__menu-link">Inicio</a>
                </li>
                <li class="sidebar__menu-item">
                    <a href="#" class="sidebar__menu-link">Gestión de Usuarios</a>
                </li>
                <li class="sidebar__menu-item">
                    <a href="#" class="sidebar__menu-link">Reportes</a>
                </li>
                <li class="sidebar__menu-item">
                    <a href="#" class="sidebar__menu-link">Configuración</a>
                </li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="content">
            <h1 class="content__welcome-title">Bienvenido, {{usuario.nombre}}</h1>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <!-- Tarjeta 1 -->
                <div class="card">
                    <h3 class="card__title">Estadísticas Generales</h3>
                    <ul class="card__list">
                        <li class="card__list-item">Accesos al sistema: {{estadisticas.accesos}}</li>
                        <li class="card__list-item">Usuarios registrados: {{estadisticas.usuarios}}</li>
                    </ul>
                </div>

                <!-- Tarjeta 2 -->
                <div class="card">
                    <h3 class="card__title">Accesos Rápidos</h3>
                    <ul class="card__list">
                        <li class="card__list-item">
                            <a href="#" class="card__link">Registrar nuevo usuario</a>
                        </li>
                        <li class="card__list-item">
                            <a href="#" class="card__link">Ver reportes</a>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
