<?php
require '../Modelo/conexion/conexion.php';
$pagina_anterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';


$id_com = $_POST['id_comentario'];

// Eliminar los artículos asociados al pedido
$sql = "DELETE FROM comentarios WHERE id = '$id_com';";
$resultado = mysqli_query($conexion, $sql);
header("Location: $pagina_anterior");
?>