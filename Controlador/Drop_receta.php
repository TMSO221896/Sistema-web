<?php
session_start();
require '../Modelo/conexion/conexion.php';

// Verifica que la sesión tenga un valor
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

// Obtén el usuario de la sesión
$user = $_SESSION['usuario'];

// Prepara la consulta para obtener la información del usuario, incluido su rol
$consulta = "SELECT usuario, nombre, apellido, correo, rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);

$rol = $rows['rol']; // Obtener el rol del usuario

// Verificar si el rol es válido
if ($rol != 1 && $rol != 2) {
    echo "No tienes permisos para realizar esta operación.";
    exit;
}

$id_receta = $_POST['id_receta'];

$sql = "SELECT * FROM recetas WHERE id = '$id_receta';";
$resultado = mysqli_query($conexion, $sql);
$rows = mysqli_fetch_array($resultado);
$ruta = "../Static/img/Recetas/" . $rows['nombre'] . ".png";

if (unlink($ruta)) {
    $sql1 = "DELETE FROM recetas WHERE id = '$id_receta';";
    if (mysqli_query($conexion, $sql1)) {
        // Si la eliminación fue exitosa, redirigir según el rol del usuario
        if ($rol == 1) {
            header("Location: ../Vista/IndexAdmin.php");
        } else {
            header("Location: ../Vista/IndexGerente.php");
        }
        exit;
    } else {
        echo "Error al eliminar la receta.";
    }
} else {
    echo "Error al eliminar la imagen.";
}

// Redirigir en caso de que no haya eliminación
if ($rol == 1) {
    header("Location: ../Vista/IndexAdmin.php");
} else {
    header("Location: ../Vista/IndexGerente.php");
}

exit;
mysqli_close($conexion);
?>
