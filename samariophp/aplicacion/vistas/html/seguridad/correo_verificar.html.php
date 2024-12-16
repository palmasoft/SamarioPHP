{% extends 'plantilla.html.php' %}
{% block title %}Inicio de Sesión{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   

    <title>Verificación de Correo</title>
    <link rel="stylesheet" href="/publico/css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Verificación de Correo</h2>
        <p>Su correo ha sido verificado exitosamente. Ahora puede iniciar sesión.</p>
        <p><a href="/login">Iniciar sesión</a></p>
    </div>
</body>
</html>

{% endblock %}