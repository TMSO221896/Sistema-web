<?php include '../Modelo/conexion/conexion.php';?>

<?php

    session_start();

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Comprobar si es el formulario de registro
        if (isset($_POST['nombreEmp']) && isset($_POST['apellidoEmp']) && isset($_POST['usuarioEmp']) && isset($_POST['correoEmp']) && isset($_POST['contraseñaEmp'])) {
            // Registro de Usuario
            $nombre = mysqli_real_escape_string($conexion, $_POST['nombreEmp']);
            $apellido = mysqli_real_escape_string($conexion, $_POST['apellidoEmp']);
            $usuario = mysqli_real_escape_string($conexion, $_POST['usuarioEmp']);
            $correo = mysqli_real_escape_string($conexion, $_POST['correoEmp']);
            $contraseña = mysqli_real_escape_string($conexion, $_POST['contraseñaEmp']);

            // Verificar si el usuario ya existe
            $checkUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");
            if (mysqli_num_rows($checkUsuario) > 0) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "¡El nombre de usuario ya está en uso. Elija otro.!";
                header('Location: ../Vista/IndexAdmin.php'); 
                exit();
            }

            // Insertar en la base de datos
            $query = "INSERT INTO usuarios (usuario, nombre, apellido, correo, contrasena,rol) VALUES ('$usuario', '$nombre', '$apellido', '$correo', '$contraseña',2)";
            
            if (mysqli_query($conexion, $query)) {
                $_SESSION['alert'] = 'success';
                $_SESSION['msj2'] = "¡Empleado almacenado exitosamente!";
                header('Location: ../Vista/IndexAdmin.php'); 
            } else {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "¡Error al almacenar el usuario!";
                header('Location: ../Vista/IndexAdmin.php'); 
            }
            mysqli_close($conexion);
        }
    }
?>
