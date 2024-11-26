<?php
session_start();

$user = $_SESSION['usuario'];
require '../Modelo/conexion/conexion.php';

// Verificar si existen pedidos asociados al usuario
$sql_check_pedidos = "SELECT * FROM pedidos WHERE user_id = '$user';";
$resultado_pedidos = mysqli_query($conexion, $sql_check_pedidos);

if (mysqli_num_rows($resultado_pedidos) > 0) {
    // Si hay pedidos, establecer mensaje de alerta y redirigir a la página de perfil
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "¡Tienes pedidos registrados! Finaliza tus pedidos antes de eliminar tu cuenta.";
    header('Location: ../Vista/Miperfil.php');
    exit();
}

$sql_delete_dir = "DELETE FROM direcciones WHERE id_usuario = '$user';";
$resultado2 = mysqli_query($conexion, $sql_delete_dir);
// Si no hay pedidos, procede con la eliminación del usuario
$sql_delete_user = "DELETE FROM usuarios WHERE usuario = '$user';";
$resultado = mysqli_query($conexion, $sql_delete_user);


if ($resultado && mysqli_affected_rows($conexion) > 0) {
    // Cerrar sesión si el usuario fue eliminado correctamente
    $query = "INSERT INTO bitacora_usuarios (usuario_id, accion, fecha) VALUES ('$usuario', 'Eliminado', CURDATE())";
    $resultado = mysqli_query($conexion, $query);
    session_destroy();
    header("Location: ../Vista/Index.php");
    exit();
} else {
    // Si ocurre algún error al eliminar el usuario
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "¡La cuenta no pudo ser eliminada!";
    header('Location: ../Vista/Miperfil.php');
    exit();
}
?>
