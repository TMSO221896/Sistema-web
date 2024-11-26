<?php
    require '../Modelo/conexion/conexion.php';
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../Vista/Iniciosesion.php");
        exit;
    }

    $idPastel = $_POST['id_pastel'];
    $status = $_POST['status'];

    $sql1 = "UPDATE pasteles SET visualizacion = '$status' WHERE id_pastel = '$idPastel';";
    $execute1 = mysqli_query($conexion, $sql1);

    if ($execute1) {
        $_SESSION['alert'] = 'success';
        $_SESSION['msj2'] = "Visualización actualizada con éxito!";
    } else {
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "Error al actualizar la Visualización.";
    }
    
    header('Location: ../Vista/IndexAdmin.php');
    exit();

    mysqli_close($conexion);
?>
