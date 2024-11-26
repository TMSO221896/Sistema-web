<?php
require '../Modelo/conexion/conexion.php';

$id_usu = $_POST['id_usuario'];

$sql = "DELETE FROM usuarios WHERE usuario = '$id_usu';";
$resultado = mysqli_query($conexion, $sql);
header('Location: ../Vista/IndexAdmin.php');
exit;

?>
