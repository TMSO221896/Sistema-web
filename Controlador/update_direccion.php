<?php
    require '../Modelo/conexion/conexion.php';
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../Vista/Iniciosesion.php");
        exit;
    }

    $calle = $_POST['calle'];
    $colonia = $_POST['colonia'];
    $cod_postal =  $_POST['cod_postal'];
    $numero = $_POST['numero'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $id_direccion =  $_POST['id_direccion'];

    $sql1 = "UPDATE direcciones SET calle = '$calle', colonia = '$colonia' ,numero = '$numero', estado = '$estado', ciudad = '$ciudad' WHERE id_direccion = '$id_direccion'";
    $execute1 = mysqli_query($conexion, $sql1);

 
    header('Location: ../Vista/IndexAdmin.php');
    exit();

    mysqli_close($conexion);
?>
