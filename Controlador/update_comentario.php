<?php
require '../Modelo/conexion/conexion.php';
session_start();
$pagina_anterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';


if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

// Obtener los valores enviados por POST
$idcom = $_POST['id_comentario'];
$comentario = $_POST['comentario'];
$satisfaccion = $_POST['satisfaccion']; // Obtener el valor de satisfacción

// Validación básica (puedes ajustar según sea necesario)
if (empty($comentario) || empty($satisfaccion) || !is_numeric($satisfaccion) || $satisfaccion < 1 || $satisfaccion > 5) {
    echo "Datos inválidos.";
    exit;
}

// Consulta de actualización
$sql = "UPDATE comentarios SET comentario = '$comentario', satisfaccion = '$satisfaccion' WHERE id = '$idcom'";
$execute = mysqli_query($conexion, $sql);

if ($execute) {
    header("Location: $pagina_anterior"); // Redirige si la actualización fue exitosa
    exit();
} else {
    echo "Error al actualizar el comentario: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
