<?php
session_start();

// Obtén el usuario de la sesión
$user = $_SESSION['usuario'];
require '../Modelo/conexion/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobar si es el formulario de dirección
    if (isset($_POST['calle']) && isset($_POST['colonia']) && isset($_POST['cod_postal']) && isset($_POST['numero']) && isset($_POST['estado']) && isset($_POST['ciudad'])) {
        
        // Escapar datos para evitar inyección SQL
        $calle = mysqli_real_escape_string($conexion, $_POST['calle']);
        $colonia = mysqli_real_escape_string($conexion, $_POST['colonia']);
        $cod_postal = mysqli_real_escape_string($conexion, $_POST['cod_postal']);
        $numero = mysqli_real_escape_string($conexion, $_POST['numero']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);

        // Insertar la nueva dirección en la base de datos
        $query = "INSERT INTO direcciones (id_usuario, calle, colonia, cod_postal, numero, estado, ciudad) 
                  VALUES ('$user', '$calle', '$colonia', '$cod_postal', '$numero', '$estado', '$ciudad')";
        
        if (mysqli_query($conexion, $query)) {
            $_SESSION['alert'] = 'success';
            $_SESSION['msj2'] = "¡Dirección añadida exitosamente!";
            header('Location: ../Vista/Miperfil.php'); 
        } else {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "¡Error al añadir la dirección!";
            header('Location: ../Vista/Miperfil.php'); 
        }

        // Cerrar conexión a la base de datos
        mysqli_close($conexion);
    } 
}
?>
