<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{% block title %}Página de Ejemplo{% endblock %} - SamarioPHP.</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{config.app.url_base}}/css/estilos.css">
    </head>
    <body>
        <div class="container">

            {% block menu %}
            <nav class="public-menu">
                <!-- Título del menú -->
                <div class="menu-title">SamarioPHP</div>
                <ul>
                    <li><a href="{{config.app.url_base}}/login">Iniciar Sesión</a></li>
                    <li><a href="{{config.app.url_base}}/registro">Registrarse</a></li>
                </ul>
            </nav>
            {% endblock %}


            {% block encabezado %}
            <header class="header">
                <div class="logo">
                    <img src="{{config.app.url_base}}imagenes/samarioPHP.png" alt="SamarioPHP Logo" class="logo-img">
                    <h1>Bienvenido a SamarioPHP</h1>
                </div>
            </header>
            {% endblock %}


            {% block contenido %}
            <div class="main-content">
                <section class="intro">
                    <h2>Introducción a SamarioPHP</h2>
                    <p>SamarioPHP es un framework robusto y eficiente para desarrollar aplicaciones web. Está diseñado para ser fácil de usar y personalizar.</p>
                </section>

                <section class="features">
                    <h2>Características</h2>
                    <ul>
                        <li><strong>Fácil de usar:</strong> Con una estructura limpia y sencilla.</li>
                        <li><strong>Extensible:</strong> Agrega lo que necesites sin complicaciones.</li>
                        <li><strong>Rápido:</strong> Optimizado para aplicaciones de alto rendimiento.</li>
                    </ul>
                </section>

                <section class="credits">
                    <p>Desarrollado por: <a href="https://puroingeniosamario.com.co" target="_blank">Puro Ingenio Samario</a></p>
                    <img src="https://puroingeniosamario.com.co/wp-content/uploads/2024/07/puroingeniosamario-1.png" alt="Puro Ingenio Samario" class="credits-img">
                </section>
            </div>
            {% endblock %}

        </div>
        {% block piecera  %}
        <footer class="footer">
            <p>Puro Ingenio Samario &copy; {{now | date('y')}} SamarioPHP. Todos los derechos reservados.</p>
        </footer>
        {% endblock %}
    </body>
</html>
