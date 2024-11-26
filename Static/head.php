<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    // Detener el proceso si el carrito no tiene datos
  }
  // Cuenta los elementos en el carrito
$numero_articulos = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
  
$user = $_SESSION['usuario'];
$user_id = $_SESSION['usuario'];  // Obtener el ID del usuario logueado
require '../Modelo/conexion/conexion.php';

// Prepara la consulta
$consulta = "SELECT usuario, nombre, apellido, correo, rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);
$rol = $rows['rol'];

// Consultar solo los pedidos de este usuario
$query_pedidos = $conexion->prepare("SELECT * FROM pedidos WHERE user_id = ?");
$query_pedidos->bind_param("s", $user_id);
$query_pedidos->execute();
$result_pedidos = $query_pedidos->get_result();

// Consultar datos de este usuario
$query_usuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$query_usuario->bind_param("s", $user_id);
$query_usuario->execute();
$result_usuario = $query_usuario->get_result();

// Cambia la consulta SQL para incluir la descripción
$sql = $conexion->prepare("SELECT id_pastel, nombreP, descripcion, precio FROM pasteles where visualizacion = 'visualizar'");
if ($sql === false) {
    die('Error en la consulta: ' . $conexion->error);
}
$sql->execute();
$result = $sql->get_result(); 
$pasteles = $result->fetch_all(MYSQLI_ASSOC);

// Cambia la consulta SQL para incluir la descripción
$sql = $conexion->prepare("SELECT id, nombre, receta FROM recetas;");
if ($sql === false) {
    die('Error en la consulta: ' . $conexion->error);
}
$sql->execute();
$result = $sql->get_result(); 
$recetas = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1.0"
		/>
		<title>Claus DyC</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
		<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    	<script src="../Static/js/SweetAlert.js"></script>
		<script src="../Static/js/Footer.js"></script>
		<link rel="stylesheet" href="../Static/css/FooterStyle.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
</head>