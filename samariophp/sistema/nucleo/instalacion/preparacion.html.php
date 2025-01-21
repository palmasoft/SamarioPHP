<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <title>Instalación - SamarioPHP</title>
        <link rel="stylesheet" href="{{ app.url_base }}css/estilos.css">
    </head>
    <body>
        <div class="contenedor">
            <header>
                <div class="marca">
                    <h1 class="titulo">SamarioPHP</h1>
                    <p class="subtitulo">Framework ligero y flexible para tus proyectos PHP</p>
                </div>
                <div class="app-info">
                    <h2 class="app-titulo">Aplicación: <strong>{{ app.nombre }}</strong></h2>
                    <p class="app-descripcion">URL de la aplicación: <a href="{{ app.url_base }}" target="_blank">{{ app.url_base }}</a></p>
                </div>
            </header>

            <section>
                <div class="mensaje {{ mensaje_tipo }}">
                    {{ mensaje }}
                </div>
                {% if mensaje_tipo == 'error' %}
                <p>Si necesita ayuda, contacte al soporte técnico o revise la documentación.</p>
                <a class="boton" href="/">Volver al inicio</a>
                {% endif %}
            </section>

            {% if mensaje_tipo == 'iniciar_instalacion' %}
            <section id="instrucciones_programador">
                {% include 'instalacion/leeme.html' %}

                {% if entorno == 'desarrollo' %}
                <p>Entorno de desarrollo: Algunas opciones avanzadas pueden estar disponibles.</p>
                {% endif %}

                <form action="/instalacion" method="POST" >
                    <button class="boton-iniciar" type="submit">Iniciar instalación</button>
                </form>
            </section>

            {% endif %}


        </div>
    </body>
</html>
