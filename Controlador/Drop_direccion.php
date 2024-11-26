<?php
require '../Modelo/conexion/conexion.php';

$id_dir = $_POST['id_direccion'];

$sql = "DELETE FROM direcciones WHERE id_direccion = '$id_dir';";
$resultado = mysqli_query($conexion, $sql);
header('Location: ../Vista/Miperfil.php');
exit;

?>
