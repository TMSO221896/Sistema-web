<?php
require '../Modelo/conexion/conexion.php';

echo $id_res = $_POST['id_resenia'];

// Eliminar los artículos asociados al pedido
$sql = "DELETE FROM resenias WHERE id = '$id_res';";
$resultado = mysqli_query($conexion, $sql);

?>