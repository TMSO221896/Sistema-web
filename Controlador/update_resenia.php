<?php
    require '../Modelo/conexion/conexion.php';
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../Vista/Iniciosesion.php");
        exit;
    }

    $idres = $_POST['id_resenia'];
    $resenia = $_POST['resenia'];

    $sql = "UPDATE resenias SET resenias = '$resenia' WHERE id = '$idres'";
    $execute = mysqli_query($conexion, $sql);

 
    header('Location: ../Vista/Index.php');
    exit();

    mysqli_close($conexion);
?>
