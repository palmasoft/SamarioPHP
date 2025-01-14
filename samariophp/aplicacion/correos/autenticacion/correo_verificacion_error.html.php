{% extends 'plantilla.html.php' %}
{% block title %}Inicio de Sesión{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   

    <title>Error de Verificación</title>
    <link rel="stylesheet" href="/publico/css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Error de Verificación</h2>
        <p>Hubo un problema al verificar tu correo. Por favor, intenta nuevamente o contacta con soporte.</p>
        <p><a href="/inicio-sesion">Volver al inicio de sesión</a></p>
    </div>
</body>
</html>

{% endblock %}