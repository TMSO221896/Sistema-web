<?php
session_start();
include '../Modelo/conexion/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['usuario'], $_POST['correo'], $_POST['contraseña'])) {
        // Limpieza de datos
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $usuario = trim($_POST['usuario']);
        $correo = trim($_POST['correo']);
        $contraseña = trim($_POST['contraseña']);

        // Validar formato de Nombre y Apellido
        if (!preg_match('/^[a-zA-Z]+$/', $nombre) || !preg_match('/^[a-zA-Z]+$/', $apellido)) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El nombre y apellido solo deben contener letras.";
            header('Location: ../Vista/Iniciosesion.php');
            exit();
        }

        // Validar formato del Usuario
        if (!preg_match('/^[a-zA-Z0-9]+$/', $usuario)) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El nombre de usuario solo puede contener letras y números, sin caracteres especiales.";
            header('Location: ../Vista/Iniciosesion.php');
            exit();
        }

        // Validar patrones repetitivos
        if (preg_match('/^(.)\1+$/', $nombre) || preg_match('/^(.)\1+$/', $apellido) || preg_match('/^(.)\1+$/', $usuario)) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "Los campos no deben contener patrones repetitivos como 'aaaaaa'.";
            header('Location: ../Vista/Iniciosesion.php');
            exit();
        }

        // Verificar si el usuario o correo ya existe
        $nombre = mysqli_real_escape_string($conexion, $nombre);
        $apellido = mysqli_real_escape_string($conexion, $apellido);
        $usuario = mysqli_real_escape_string($conexion, $usuario);
        $correo = mysqli_real_escape_string($conexion, $correo);
        $contraseña = mysqli_real_escape_string($conexion, $contraseña);

        $checkUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario' OR correo='$correo'");
        if (mysqli_num_rows($checkUsuario) > 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El nombre de usuario o correo ya están en uso. Elija otro.";
            header('Location: ../Vista/Iniciosesion.php');
            exit();
        }

        // Encriptar la contraseña
        // Insertar en la base de datos
        $queryUsuario = "INSERT INTO usuarios (usuario, nombre, apellido, correo, contrasena, rol) VALUES ('$usuario', '$nombre', '$apellido', '$correo', '$contraseña', 3)";
        if (mysqli_query($conexion, $queryUsuario)) {
            $usuario_id = mysqli_insert_id($conexion); // Obtener el ID del usuario insertado
            $queryBitacora = "INSERT INTO bitacora_usuarios (usuario_id, accion, fecha) VALUES ('$usuario', 'Creado', CURDATE())";
            mysqli_query($conexion, $queryBitacora); // Ejecutar la inserción en bitácora

            $_SESSION['alert'] = 'success';
            $_SESSION['msj2'] = "Usuario almacenado exitosamente.";
            header('Location: ../Vista/Iniciosesion.php');
        } else {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "Error al almacenar el usuario: " . mysqli_error($conexion);
            header('Location: ../Vista/Iniciosesion.php');
        }
        mysqli_close($conexion);
    }
}
?>