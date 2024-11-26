<?php
session_start();
require '../Modelo/conexion/conexion.php';

$id_pastel = $_POST['id_pastel'];

$sql_check_pedidos = "SELECT * FROM pedido_articulos WHERE pastel_id = '$id_pastel';";
$resultado_pedidos = mysqli_query($conexion, $sql_check_pedidos);

if (mysqli_num_rows($resultado_pedidos) > 0) {
    // Si hay pedidos, establecer mensaje de alerta y redirigir a la página de perfil
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "El pastel esta en un pedido activo o registrado! Finaliza tus pedidos antes de eliminar el pastel.";
    header('Location: ../Vista/IndexAdmin.php');
    exit();
}

$sql = "SELECT  * FROM pasteles WHERE id_pastel = '$id_pastel';";
$resultado = mysqli_query($conexion, $sql);
$rows = mysqli_fetch_array($resultado);
$ruta ="../Static/img/Productos/" . $rows['nombreP'] . ".png";


    if (unlink($ruta)) {
        $sql1 = "DELETE FROM pasteles WHERE id_pastel = '$id_pastel';";
        if (mysqli_query($conexion, $sql1)) {
            $_SESSION['alert'] = 'success';
            $_SESSION['msj2'] = "El pastel se elimino correctamente";
            header('Location: ../Vista/IndexAdmin.php');
        }
    } else {
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "Error al eliminar la imagen";
        header('Location: ../Vista/IndexAdmin.php');
    }

exit;

?>
