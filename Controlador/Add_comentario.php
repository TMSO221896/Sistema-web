<?php
session_start();
$pagina_anterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';

// Conectar a la base de datos
require '../Modelo/conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = $_POST['descripcion'];  // Comentario de usuario
    $calificacion = $_POST['calificacion'];  // Calificación de satisfacción (1-5)
    $idUsuario = $_SESSION['usuario'];  // Usuario autenticado

    // Validación adicional si es necesario
    if (empty($comentario) || empty($calificacion)) {
        echo "Error: Todos los campos son obligatorios.";
        exit();
    }

    // Insertar comentario y calificación en la base de datos
    $query = "INSERT INTO comentarios (idUsuario, comentario, satisfaccion) VALUES ('$idUsuario', '$comentario', '$calificacion')";
    if (mysqli_query($conexion, $query)) {
        $_SESSION['alert'] = 'succes';
        $_SESSION['msj2'] = "Reseña creada con exito.";
        header("Location: $pagina_anterior"); // Redirige a una página de éxito o refresca la página
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
