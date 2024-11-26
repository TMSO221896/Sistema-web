<?php 
require '../Modelo/conexion/conexion.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

$user_id = $_SESSION['usuario'];
$user = $_POST['usuario'];
$name = $_POST['nombre'];
$ape = $_POST['apellido'];
$correo = $_POST['correo'];

// Obtener el rol y el correo actual del usuario
$consulta = "SELECT rol, correo FROM usuarios WHERE usuario = '$user_id'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);
$rol = $rows['rol'];
$current_email = $rows['correo'];

// Si el usuario no cambió
if ($user_id == $user && $correo == $current_email) {
    $sql_update = "UPDATE usuarios SET usuario = '$user', nombre = '$name', apellido = '$ape'
                   WHERE usuario = '$user_id'";
    $execute_update = mysqli_query($conexion, $sql_update);
    $_SESSION['usuario'] = $user;
    $_SESSION['alert'] = 'success';
    $_SESSION['msj2'] = "¡Credenciales actualizadas con éxito!";
    header('Location: ' . ($rol == 1 || $rol == 2 ? '../Vista/IndexAdmin.php' : '../Vista/Miperfil.php'));
    exit();
}

// Validar usuario duplicado
$sql_user_check = "SELECT usuario FROM usuarios WHERE usuario = '$user'";
$execute_user_check = mysqli_query($conexion, $sql_user_check);

// Validar correo duplicado si cambió
if ($correo != $current_email) {
    $sql_email_check = "SELECT correo FROM usuarios WHERE correo = '$correo'";
    $execute_email_check = mysqli_query($conexion, $sql_email_check);
    
    if (mysqli_num_rows($execute_email_check) > 0) {
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "¡El correo electrónico ya está registrado!";
        header('Location: ' . ($rol == 1 || $rol == 2 ? '../Vista/IndexAdmin.php' : '../Vista/Miperfil.php'));
        exit();
    }
}

// Validar usuario duplicado
if (mysqli_num_rows($execute_user_check) > 0 && $user_id != $user) {
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "¡El nombre de usuario ya existe!";
    header('Location: ' . ($rol == 1 || $rol == 2 ? '../Vista/IndexAdmin.php' : '../Vista/Miperfil.php'));
    exit();
}

// Actualizar los datos del usuario si no hay duplicados
$sql_update = "UPDATE usuarios SET usuario = '$user', nombre = '$name', apellido = '$ape', correo = '$correo'
               WHERE usuario = '$user_id'";
$execute_update = mysqli_query($conexion, $sql_update);

if ($execute_update) {
    $_SESSION['usuario'] = $user;
    $_SESSION['alert'] = 'success';
    $_SESSION['msj2'] = "¡Credenciales actualizadas con éxito!";
    header('Location: ' . ($rol == 1 || $rol == 2 ? '../Vista/IndexAdmin.php' : '../Vista/Miperfil.php'));
} else {
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "Hubo un error al actualizar los datos.";
    header('Location: ../Vista/Miperfil.php');
}

mysqli_close($conexion);
?>
