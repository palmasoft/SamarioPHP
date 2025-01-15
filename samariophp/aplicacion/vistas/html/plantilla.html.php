<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{% block title %}Plantilla HOME{% endblock %} - {{app.nombre}}.</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{config.app.url_base}}/css/estilos.css">
    </head>
    <body>
        <div class="container">

            {% block menu %}

            <nav class="public-menu">
                <div class="menu-title">SamarioPHP</div>
                <ul>
                    <li><a href="/inicio-sesion">Iniciar Sesión</a></li>
                    <li><a href="/registro">Registrarse</a></li>
                    <li><a href="/documentacion">Documentación</a></li>
                    <li><a href="/soporte">Soporte</a></li>
                </ul>
            </nav>            
            {% endblock %}


            {% block encabezado %}
            <header class="header encabezado">
                <div class="logo">
                    <img src="{{app.logo}}" alt="Logo" class="logo-img">
                </div>
                <h1>Bienvenido a {{app.nombre}}</h1>
                <p class="tagline">El framework diseñado para el desarrollo rápido y eficiente de aplicaciones web.</p>
            </header>

            {% endblock %}


            {% block contenido %}
            <div class="main-content">
                <section class="intro">
                    <h2>¿Qué es SamarioPHP?</h2>
                    <p>SamarioPHP es un framework ligero y modular que proporciona una base sólida para desarrollar aplicaciones web modernas. Con su arquitectura limpia y herramientas intuitivas, es ideal tanto para principiantes como para expertos.</p>
                </section>

                <section class="features">
                    <h2>Características Destacadas</h2>
                    <ul>
                        <li><strong>Simplicidad:</strong> Estructura clara para facilitar el desarrollo.</li>
                        <li><strong>Flexibilidad:</strong> Personaliza cada aspecto según tus necesidades.</li>
                        <li><strong>Compatibilidad:</strong> Funciona con librerías populares como Medoo, Twig y PHPMailer.</li>
                        <li><strong>Optimización:</strong> Rendimiento sobresaliente para aplicaciones de alto tráfico.</li>
                        <li><strong>Seguridad:</strong> Incluye autenticación robusta, roles y permisos configurables.</li>
                        <li><strong>Documentación Completa:</strong> Guías detalladas para empezar rápidamente.</li>
                    </ul>
                </section>

                <section class="system-details">
                    <h2>Detalles del Sistema</h2>
                    <p>SamarioPHP incluye:</p>
                    <ul>
                        <li>Enrutamiento dinámico con Slim Framework.</li>
                        <li>ORM sencillo y eficiente con Medoo.</li>
                        <li>Gestión de vistas con Twig para un diseño elegante.</li>
                        <li>Generación de PDFs profesionales con TCPDF.</li>
                        <li>Envío de correos seguro con PHPMailer.</li>
                        <li>Herramientas de prueba con PHPUnit.</li>
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
            <p>Puro Ingenio Samario &copy; {{now | date('Y')}} SamarioPHP. Todos los derechos reservados.</p>
            <p><a href="/terminos">Términos de Uso</a> | <a href="/privacidad">Política de Privacidad</a></p>
        </footer>
        {% endblock %}
    </body>
</html>
