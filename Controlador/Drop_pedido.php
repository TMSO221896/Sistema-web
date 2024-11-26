<?php
session_start(); // Asegúrate de que la sesión esté iniciada
require '../Modelo/conexion/conexion.php';

// Verifica si el usuario está conectado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php"); // Redirige a la página de login si no hay sesión
    exit;
}

// Obtén el usuario de la sesión
$user = $_SESSION['usuario'];

// Prepara la consulta
$consulta = "SELECT usuario, nombre, apellido, correo, rol FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();
$rows = $resultado->fetch_assoc();

if ($rows) {
    $rol = $rows['rol'];
} else {
    echo "Usuario no encontrado.";
    exit;
}

$id_pedido = $_POST['id_pedido'];

// Eliminar los artículos asociados al pedido
$sql = "DELETE FROM pedido_articulos WHERE pedido_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_pedido);
$resultado = $stmt->execute();

if ($resultado) {
    // Eliminar el pedido si los artículos fueron eliminados correctamente
    $sql2 = "DELETE FROM pedidos WHERE id = ?";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("i", $id_pedido);
    $resultado2 = $stmt2->execute();

    if ($resultado2) {
        // Redirigir a la página correspondiente según el rol
        if ($rol == 1) {
            $_SESSION['alert'] = 'succes';
            $_SESSION['msj2'] = "El producto ha sido eliminado con éxito.";
            header("Location: ../Vista/IndexAdmin.php");
        } else {
            header("Location: ../Vista/pedidos.php");
        }
        exit;
    } else {
        echo "Error al eliminar el pedido: " . mysqli_error($conexion);
    }
} else {
    echo "Error al eliminar los artículos del pedido: " . mysqli_error($conexion);
}
?>