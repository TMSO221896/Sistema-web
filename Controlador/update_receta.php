<?php
    require '../Modelo/conexion/conexion.php';
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../Vista/Iniciosesion.php");
        exit;
    }

    $idReceta = $_POST['id_receta'];
    $descripcion = $_POST['receta'];

    $sql1 = "UPDATE recetas SET receta = '$descripcion' WHERE id = '$idReceta'";
    $execute1 = mysqli_query($conexion, $sql1);

    header('Location: ../Vista/IndexAdmin.php');
    exit();

    mysqli_close($conexion);
?>
