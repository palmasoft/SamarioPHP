# SamarioPHP

**SamarioPHP** es un framework ligero y personalizable diseñado para el desarrollo rápido de aplicaciones web en PHP. Su objetivo principal es proporcionar una base sólida para proyectos que requieren flexibilidad y control, sin depender de librerías altamente acopladas. Utiliza el patrón de arquitectura MVC (Modelo-Vista-Controlador) y está construido con componentes minimalistas que facilitan su aprendizaje y extensión.

---

## Características principales

- **Ligero y eficiente**: Ideal para proyectos pequeños y medianos.
- **Flexibilidad**: Evita dependencias complejas, permitiendo un control total sobre el código.
- **Compatibilidad**: Utiliza librerías ampliamente conocidas y confiables, como:
  - [Medoo](https://medoo.in/) para ORM.
  - [Twig](https://twig.symfony.com/) como motor de plantillas.
  - [Slim Framework](https://www.slimframework.com/) para el enrutamiento.
  - [PHPMailer](https://github.com/PHPMailer/PHPMailer) para el envío de correos.
  - [TCPDF](https://tcpdf.org/) para la generación de PDFs.
  - [PHPUnit](https://phpunit.de/) para pruebas automatizadas.
- **Soporte para autenticación**: Incluye funcionalidades como inicio de sesión, registro, recuperación de contraseñas y autenticación de dos factores.
- **Sistema de roles y permisos**: Gestión avanzada de acceso a rutas específicas.

---

## Requisitos del sistema

- PHP 8.1 o superior.
- Extensiones habilitadas: `pdo`, `mbstring`, `openssl`, `json`, `fileinfo`.
- Servidor web con soporte para PHP (Apache o Nginx).
- Composer para la gestión de dependencias.

---

## Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/usuario/samariophp.git
   cd samariophp
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   ```

3. **Configurar el entorno:**
   Copia el archivo `configuracion_ejemplo.php` a `configuracion.php` y ajusta los valores según tus necesidades.

   ```php
   // Ejemplo básico para desarrollo local
   return [
       'base_de_datos' => [
           'tipo' => 'mysql',
           'servidor' => '127.0.0.1',
           'puerto' => 3306,
           'nombre_basedatos' => 'mi_base_de_datos',
           'nombre_usuario' => 'root',
           'clave_usuario' => '',
       ],
       'aplicacion' => [
           'entorno' => 'desarrollo',
           'url_base' => 'http://localhost/',
       ],
   ];
   ```

4. **Configurar la base de datos:**
   Ejecuta el instalador incluido para crear las tablas iniciales:
   ```bash
   php base_de_datos/instalador.php
   ```

5. **Configurar el servidor web:**
   Apunta el dominio o subdominio al directorio `publico/`.

---

## Organización del proyecto

- **`samariophp/`**: Núcleo del framework.
- **`publico/`**: Archivos públicos accesibles desde el navegador (CSS, JS, vistas).
- **`base_de_datos/`**: Migraciones y modelos para la gestión de datos.
- **`vendor/`**: Dependencias gestionadas por Composer.

---

## Primeros pasos

1. Accede a la URL base configurada en tu navegador.
2. Verifica que la aplicación se cargue correctamente.
3. Comienza a crear tus controladores, modelos y vistas en las carpetas respectivas.

---

## Contribuir

Si deseas contribuir al desarrollo de SamarioPHP:

1. Haz un fork del repositorio.
2. Crea una rama para tu funcionalidad o corrección:
   ```bash
   git checkout -b mi-nueva-funcionalidad
   ```
3. Envía un pull request con tus cambios.

---

## Licencia

SamarioPHP está licenciado bajo la [MIT License](LICENSE).
