<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

// Incluye la conexión a la base de datos
require '../Modelo/conexion/conexion.php';

// Recoge las contraseñas desde el formulario
$Acontra = $_POST['Acontraseña'];
$Ncontra = $_POST['Ncontraseña'];

// Obtén el usuario de la sesión
$user_id = $_SESSION['usuario'];

// Prepara la consulta para obtener la contraseña antigua
$consulta = "SELECT * FROM usuarios WHERE usuario = '$user_id'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);

// Verifica si el usuario existe y la contraseña antigua coincide
if ($rows) {
    $contraAntigua = $rows['contrasena'];
    
    if ($contraAntigua == $Acontra) {
        // Si la contraseña antigua es correcta, actualiza con la nueva
        $sql2 = "UPDATE usuarios SET contrasena = '$Ncontra' WHERE usuario = '$user_id'";
        $execute2 = mysqli_query($conexion, $sql2);
        
        // Si la actualización es exitosa
        if ($execute2) {
            if($rows['rol'] == 1){
                $_SESSION['alert'] = 'success';
                $_SESSION['msj2'] = "¡Contraseña actualizada con éxito.!";
                header('Location: ../Vista/IndexAdmin.php'); 
                exit();
            }elseif($rows['rol'] == 2){
                $_SESSION['alert'] = 'success';
                $_SESSION['msj2'] = "¡Contraseña actualizada con éxito.!";
                header('Location: ../Vista/IndexAdmin.php'); 
                exit();
            }else{
                $_SESSION['alert'] = 'success';
                $_SESSION['msj2'] = "¡Contraseña actualizada con éxito.!";
                header('Location: ../Vista/Miperfil.php'); 
                exit();
            }
        } else {
            if($rows['rol'] == 1){
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "¡Error al actualizar la contraseña.!";
                header('Location: ../Vista/IndexAdmin.php'); 
                exit();
            }elseif($rows['rol'] == 2){
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "¡Error al actualizar la contraseña.!";
                header('Location: ../Vista/IndexAdmin.php'); 
                exit();
            }else{
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "¡Error al actualizar la contraseña.!";
                header('Location: ../Vista/Miperfil.php'); 
                exit();
            }
        }
    } else {
        // Si la contraseña antigua no coincide
        if($rows['rol'] == 1){
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "¡Contraseña antigua inválida, intenta nuevamente.!";
            header('Location: ../Vista/IndexAdmin.php'); 
            exit();
        }elseif($rows['rol'] == 2){
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "¡Contraseña antigua inválida, intenta nuevamente.!";
            header('Location: ../Vista/IndexAdmin.php'); 
            exit();
        }else{
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "¡Contraseña antigua inválida, intenta nuevamente.!";
            header('Location: ../Vista/Miperfil.php'); 
            exit();
        }
    }
} else {
    // Si no se encuentra al usuario
    if($rows['rol'] == 1){
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "¡Usuario no encontrado.!";
        header('Location: ../Vista/IndexAdmin.php'); 
        exit();
    }elseif($rows['rol'] == 2){
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "¡Usuario no encontrado.!";
        header('Location: ../Vista/IndexAdmin.php'); 
        exit();
    }else{
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "¡Usuario no encontrado.!";
        header('Location: ../Vista/Miperfil.php'); 
        exit();
    }
}

?>

