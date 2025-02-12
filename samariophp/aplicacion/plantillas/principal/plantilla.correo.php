<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{% block asunto %}titulo para la version web del correo{% endblock %}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333333;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
                border-bottom: 1px solid #dddddd;
            }
            .header img {
                max-width: 120px;
                margin-bottom: 10px;
            }
            .header h1 {
                font-size: 22px;
                color: #333333;
            }
            .content {
                padding: 20px 0;
            }
            .content p {
                margin: 10px 0;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007BFF;
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 20px;
            }
            .btn:hover {
                background-color: #0056b3;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px solid #dddddd;
                font-size: 12px;
                color: #777777;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .header {
                background: #4caf50;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }
            .content {
                padding: 20px;
            }
            .footer {
                text-align: center;
                padding: 10px;
                font-size: 12px;
                color: #999;
            }
            a {
                text-decoration: none;
                color: #4caf50;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin-top: 20px;
                background: #4caf50;
                color: #ffffff;
                text-transform: uppercase;
                font-size: 14px;
                font-weight: bold;
                border-radius: 5px;
            }
            .button:hover {
                background: #45a049;
            }
        </style>        
        {% block estilos %}
        {% endblock %}
        
    </head>
    <body>
        <div class="container">

            <header>
                {% block encabezado %}            
                <img src="{{ logo_url }}" alt="Logo">                            
                {% endblock %}
            </header>

            <main>
                {% block mensaje %}            
                {% endblock %}
            </main>

            <footer>
                {% block piecera %}
                <div class="footer">
                    <p>&copy; {{ anio }} {{ nombre_proyecto }}. Todos los derechos reservados.</p>
                    <p>¿Necesitas ayuda? <a href="{{ correo_contacto }}">Contáctanos</a></p>
                    <p><a href="{{ url_base }}/terminos">Términos y Condiciones</a> | <a href="{{ url_base }}/privacidad">Política de Privacidad</a></p>
                </div>
                {% endblock %}
            </footer>

        </div>
    </body>
</html>
