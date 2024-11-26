<?php 
include '../Modelo/conexion/conexion.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_pastel'], $_FILES['imagen'])) {
        $ID = mysqli_real_escape_string($conexion, $_POST['id_pastel']);

        $consulta = "SELECT * FROM pasteles WHERE id_pastel = '$ID'";
        $resultado = mysqli_query($conexion, $consulta);

        if (!$resultado || mysqli_num_rows($resultado) == 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "No se encontró el producto.";
            header('Location: ../Vista/IndexAdmin.php');
            exit();
        }

        $rows = mysqli_fetch_array($resultado);
        $nombreProd = $rows['nombreP'];

        $target_dir = "../Static/img/Productos/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

        if (!empty($_FILES["imagen"]["tmp_name"])) {
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["imagen"]["tmp_name"]);

            if ($check === false || $imageFileType != "png") {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Archivo inválido.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            $fileNameWithoutExt = pathinfo($_FILES["imagen"]["name"], PATHINFO_FILENAME);
            if ($fileNameWithoutExt !== $nombreProd) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Nombre del archivo inválido.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            if (file_exists($target_file)) {
                unlink($target_file);
            }

            if ($_FILES["imagen"]["size"] > 2000000) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Archivo demasiado grande.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $_SESSION['alert'] = 'success';
                $_SESSION['msj2'] = "Imagen subida correctamente.";
            } else {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Error al guardar la imagen.";
            }
        } else {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "No se seleccionó ningún archivo.";
        }

        header('Location: ../Vista/IndexAdmin.php');
        exit();
    }
}

mysqli_close($conexion);
?>
