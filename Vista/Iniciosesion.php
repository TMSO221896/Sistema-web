<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include '../Modelo/conexion/conexion.php';

// Verificar si el usuario está logueado (si existe la variable de sesión 'usuario')
if (isset($_SESSION['usuario'])) {
    $log = $_SESSION['usuario']; // Asignar el valor del usuario logueado a la variable $log
} else {
    $log = ''; // Dejar vacío el campo si no hay usuario logueado
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluir íconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Incluir la hoja de estilos personalizada -->
    <link rel="stylesheet" href="../Static/css/IniciosesionStyle.css">
    <!-- Incluir SweetAlert2 para notificaciones emergentes -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../Static/js/SweetAlert.js"></script>

    <title>Inicio de sesión | Becla</title>
</head>

<body>
    <div class="container" id="container">
        <!-- Formulario de registro de usuario -->
        <div class="form-container sign-up" id="IniciarSesion">
            <form action="../Controlador/Create_Cliente.php" method="POST" class="Formulario_registro">
                <h1>Crear una cuenta</h1>
                <!-- Campos para capturar datos del usuario -->
                <input type="text" placeholder="Nombre" maxlength="20" name="nombre" required>
                <input type="text" placeholder="Apellido" maxlength="20" name="apellido" required>
                <input type="text" placeholder="Nombre Usuario" maxlength="10" name="usuario" required>
                <input type="email" placeholder="Correo electrónico" maxlength="30" name="correo" required>
                <input type="password" placeholder="Contraseña" maxlength="12" name="contraseña" required>
                <button type="submit">Inscribirse</button>
            </form>
        </div>

        <!-- Formulario de inicio de sesión -->
        <div class="form-container sign-in" id="Registro">
            <form action="../Controlador/Validacion_Usuarios.php" method="POST" class="Formulario_inicio">
                <h1>Inicio de sesión</h1>
                <span>Ingresa con tu Nombre de Usuario o utiliza tu correo electrónico</span>
                <!-- Campo para ingresar el nombre de usuario con valor previo si está logueado -->
                <input type="text" placeholder="Nombre Usuario" name="usuario" value="<?php echo isset($log) ? $log : ''; ?>" required>
                <!-- Campo para ingresar la contraseña -->
                <input type="password" placeholder="Contraseña" name="contraseña" required>
                <!-- Enlace para recuperar la contraseña -->
                <a href="#">¿Olvidaste tu contraseña?</a>
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>

        <!-- Contenedor para los botones de cambio entre las vistas de registro e inicio de sesión -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Ingrese sus datos personales para utilizar todas las funciones del sitio</p>
                    <!-- Botón para mostrar la vista de inicio de sesión -->
                    <button class="hidden" id="login">Iniciar sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hola amigo!</h1>
                    <p>Regístrese con sus datos personales para utilizar todas las funciones del sitio</p>
                    <!-- Botón para mostrar la vista de registro -->
                    <button class="hidden" id="register">Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir el archivo JavaScript para manejar la lógica del formulario -->
    <script src="../Static/js/Iniciosesion.js"></script>
    <!-- Incluir la librería jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>

</html>

<?php
// Verificar si existen mensajes de sesión para mostrar una alerta
if (isset($_SESSION['msj2']) && isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert']; // Alerta a mostrar
    $respuesta = $_SESSION['msj2']; // Mensaje de respuesta
    ?>
    <script>
        // Mostrar la alerta con el mensaje y el tipo de alerta (éxito, error, etc.)
        mostrarAlerta("<?php echo $respuesta; ?>", "<?php echo $alert; ?>");
    </script>
    <?php
    // Limpiar las variables de sesión después de mostrar la alerta
    unset($_SESSION['msj2']);
    unset($_SESSION['alert']);
}
?>
