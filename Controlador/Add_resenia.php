<?php
session_start();

// Conectar a la base de datos
require '../Modelo/conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pastel = $_POST['id_pastel'];
    $idUsuario = $_SESSION['usuario']; // Asumiendo que el usuario está autenticado
    $resenia = trim($_POST['resenia']);

    // Validación adicional si es necesario
    $query = "SELECT COUNT(*) FROM pasteles WHERE id_pastel = '$id_pastel'";
    $result = mysqli_query($conexion, $query);
    $row = mysqli_fetch_array($result);
    
    if ($row[0] == 0) {
        echo "El pastel no existe.";
    } else {
        // Proceder con la inserción
        $query = "INSERT INTO resenias (idUsuario, idpastel, resenias) VALUES ('$idUsuario', '$id_pastel', '$resenia')";
        if (mysqli_query($conexion, $query)) {
            $_SESSION['alert'] = 'succes';
            $_SESSION['msj2'] = "Reseña creada con exito.";
            header("Location: ../Vista/Index.php");
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
    
}
?>
