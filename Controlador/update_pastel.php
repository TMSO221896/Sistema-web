<?php
    require '../Modelo/conexion/conexion.php';
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../Vista/Iniciosesion.php");
        exit;
    }

    $idPastel = $_POST['id_pastel'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    $sql1 = "UPDATE pasteles SET descripcion = '$descripcion', precio = '$precio'  WHERE id_pastel = '$idPastel'";
    $execute1 = mysqli_query($conexion, $sql1);

 
    header('Location: ../Vista/IndexAdmin.php');
    exit();

    mysqli_close($conexion);
?>
