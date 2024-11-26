<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
$user = $_SESSION['usuario'];
require '../Modelo/conexion/conexion.php';

// Prepara la consulta
$consulta = "SELECT rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);
$rol = $rows['rol'];

// Verificar si el carrito está definido y no está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
  // Detener el proceso si el carrito no tiene datos
}

// Eliminar un producto del carrito si se envía una solicitud para ello
if (isset($_POST['eliminar_producto'])) {
    $index = $_POST['index'];  // Índice del producto en el carrito
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);  // Eliminar el producto del carrito
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);  // Reindexar el carrito
        header('Location: ../Vista/carrito.php');
    }
}

// Cuenta los elementos en el carrito
$numero_articulos = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;

?>
