<?php 
include '../Modelo/conexion/conexion.php'; 
session_start(); // Inicia la sesión al principio

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobar si es el formulario de registro
    if (isset($_POST['idproducto'], $_POST['nombreproducto'], $_POST['descripcion'], $_POST['precio'], $_FILES['imagen'])) {

        // Escapar los datos del formulario para prevenir inyecciones SQL
        $ID = mysqli_real_escape_string($conexion, $_POST['idproducto']);
        $nombreProd = mysqli_real_escape_string($conexion, $_POST['nombreproducto']);
        $descProd = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precioProd = mysqli_real_escape_string($conexion, $_POST['precio']);

        // Verificar si el producto ya existe
        $checkProducto = mysqli_query($conexion, "SELECT * FROM pasteles WHERE id_pastel='$ID'");
        if (mysqli_num_rows($checkProducto) > 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El id de producto ya está en uso. Elija otro.";
            header('Location: ../Vista/IndexAdmin.php');
            exit();
        }

        if ($precioProd <= 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El precio debe ser mayor que cero.";
            header('Location: ../Vista/IndexAdmin.php');
            exit();
        }

        // Proceso para subir la imagen
        $target_dir = "../Static/img/Productos/"; // Carpeta donde se guardarán las imágenes
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]); // Ruta completa de la imagen

        // Verificar si se ha cargado un archivo de imagen
        if (!empty($_FILES["imagen"]["tmp_name"])) {
            try {
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            } catch (Exception $e) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Error al procesar la extensión de la imagen: " . $e->getMessage();
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Verificar si el archivo es una imagen
            $check = getimagesize($_FILES["imagen"]["tmp_name"]);
            if ($check === false) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "El archivo no es una imagen.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Verificar si el nombre del archivo coincide con el nombre del producto (sin extensión)
            $fileNameWithoutExt = pathinfo($_FILES["imagen"]["name"], PATHINFO_FILENAME);
            if ($fileNameWithoutExt !== $nombreProd) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "El nombre del archivo debe coincidir con el nombre del producto.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Verificar si la imagen ya existe
            if (file_exists($target_file)) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "La imagen ya existe.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Limitar el tamaño de la imagen a 2MB
            if ($_FILES["imagen"]["size"] > 2000000) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "La imagen es demasiado grande (máximo 2MB).";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Permitir solo formatos PNG
            if ($imageFileType != "png") {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Solo se permiten imágenes PNG.";
                header('Location: ../Vista/IndexAdmin.php');
                exit();
            }

            // Intentar mover la imagen subida a la carpeta de destino
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO pasteles (id_pastel, nombreP, descripcion, precio,visualizacion) 
                          VALUES ('$ID', '$nombreProd', '$descProd', '$precioProd','visualizar')";

                if (mysqli_query($conexion, $query)) {
                    $_SESSION['alert'] = 'success';
                    $_SESSION['msj2'] = "Producto almacenado exitosamente";
                } else {
                    $_SESSION['alert'] = 'error';
                    $_SESSION['msj2'] = "Error al almacenar el producto.";
                }
            } else {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Error al subir la imagen.";
            }
        } else {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "No se seleccionó ningún archivo de imagen.";
        }

        // Redirigir después del proceso
        header('Location: ../Vista/IndexAdmin.php');
        exit();
    }
}

// Cerrar la conexión a la base de datos al final
mysqli_close($conexion);
?>
