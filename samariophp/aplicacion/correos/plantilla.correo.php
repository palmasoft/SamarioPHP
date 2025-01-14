<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verificación de correo</title>
        <style>
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
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Verifica tu correo</h1>
            </div>
            <div class="content">
                <p>Hola {{ nombre|e }},</p>
                <p>Gracias por registrarte en nuestra plataforma. Para completar el proceso, por favor verifica tu correo haciendo clic en el botón a continuación:</p>
                <a href="{{ enlace_verificacion }}" class="button">Verificar mi correo</a>
                <p>O copia y pega este enlace en tu navegador:</p>
                <p>{{ enlace_verificacion }}</p>
                <p>Si no reconoces esta acción, por favor ignora este mensaje.</p>
                <p>Saludos,<br>El equipo de {{ nombre_proyecto|e }}</p>
            </div>
            <div class="footer">
                <p>&copy; {{ anio }} {{ nombre_proyecto|e }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
</html>
