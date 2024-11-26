<?php
session_start();

// Verifica que la sesión tenga un valor
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

// Obtén el usuario de la sesión
$user = $_SESSION['usuario'];
require '../Modelo/conexion/conexion.php';

// Prepara la consulta para obtener la información del usuario, incluido su rol
$consulta = "SELECT usuario, nombre, apellido, correo, rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);

$user = $rows['usuario'];
$rol = $rows['rol']; // Obtener el rol del usuario

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobar si es el formulario de registro
    if (isset($_POST['idreceta'], $_POST['nombrereceta'], $_POST['descripcionR'], $_FILES['imagen'])) {

        // Validar el rol del usuario
        if ($rol != 1 && $rol != 2) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "No tienes permisos para registrar recetas.";
            if ($rol == 1) {
                header('Location: ../Vista/IndexAdmin.php');
            } else {
                header('Location: ../Vista/IndexGerente.php');
            }
            exit();
        }

        // Escapar los datos del formulario para prevenir inyecciones SQL
        $ID = mysqli_real_escape_string($conexion, $_POST['idreceta']);
        $nombreRec = mysqli_real_escape_string($conexion, $_POST['nombrereceta']);
        $descRec = mysqli_real_escape_string($conexion, $_POST['descripcionR']);

        // Verificar si el nombre de la receta ya existe
        $checkNombreReceta = mysqli_query($conexion, "SELECT * FROM recetas WHERE nombre='$nombreRec'");
        if (mysqli_num_rows($checkNombreReceta) > 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El nombre de la receta ya existe. Elija otro nombre.";
            if ($rol == 1) {
                header('Location: ../Vista/IndexAdmin.php');
            } else {
                header('Location: ../Vista/IndexGerente.php');
            }
            exit();
        }

        // Verificar si el id de la receta ya existe
        $checkreceta = mysqli_query($conexion, "SELECT * FROM recetas WHERE id='$ID'");
        if (mysqli_num_rows($checkreceta) > 0) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El id de receta ya está en uso. Elija otro.";
            if ($rol == 1) {
                header('Location: ../Vista/IndexAdmin.php');
            } else {
                header('Location: ../Vista/IndexGerente.php');
            }
            exit();
        }

        // Proceso para subir la imagen
        $target_dir = "../Static/img/Recetas/"; // Carpeta donde se guardarán las imágenes
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]); // Ruta completa de la imagen

        // Verificar si el nombre de la receta coincide con el nombre del archivo (sin la extensión)
        $nombreArchivoSinExt = pathinfo($_FILES["imagen"]["name"], PATHINFO_FILENAME);
        if ($nombreRec !== $nombreArchivoSinExt) {
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "El nombre de la receta debe coincidir con el nombre del archivo (sin la extensión).";
            if ($rol == 1) {
                header('Location: ../Vista/IndexAdmin.php');
            } else {
                header('Location: ../Vista/IndexGerente.php');
            }
            exit();
        }

        // Verificar si se ha cargado un archivo de imagen
        if (!empty($_FILES["imagen"]["tmp_name"])) {
            try {
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            } catch (Exception $e) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Error al procesar la extensión de la imagen: " . $e->getMessage();
                if ($rol == 1) {
                    header('Location: ../Vista/IndexAdmin.php');
                } else {
                    header('Location: ../Vista/IndexGerente.php');
                }
                exit();
            }

            // Verificar si el archivo es una imagen
            $check = getimagesize($_FILES["imagen"]["tmp_name"]);
            if ($check === false) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "El archivo no es una imagen.";
                if ($rol == 1) {
                    header('Location: ../Vista/IndexAdmin.php');
                } else {
                    header('Location: ../Vista/IndexGerente.php');
                }
                exit();
            }

            // Verificar si la imagen ya existe
            if (file_exists($target_file)) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "La imagen ya existe.";
                if ($rol == 1) {
                    header('Location: ../Vista/IndexAdmin.php');
                } else {
                    header('Location: ../Vista/IndexGerente.php');
                }
                exit();
            }

            // Limitar el tamaño de la imagen a 2MB
            if ($_FILES["imagen"]["size"] > 2000000) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "La imagen es demasiado grande (máximo 2MB).";
                if ($rol == 1) {
                    header('Location: ../Vista/IndexAdmin.php');
                } else {
                    header('Location: ../Vista/IndexGerente.php');
                }
                exit();
            }

            // Permitir solo formatos PNG
            if ($imageFileType != "png") {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "Solo se permiten imágenes PNG.";
                if ($rol == 1) {
                    header('Location: ../Vista/IndexAdmin.php');
                } else {
                    header('Location: ../Vista/IndexGerente.php');
                }
                exit();
            }

            // Intentar mover la imagen subida a la carpeta de destino
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO recetas (id, nombre, receta, usuario_id) 
                          VALUES ('$ID', '$nombreRec', '$descRec', '$user')";

                if (mysqli_query($conexion, $query)) {
                    $_SESSION['alert'] = 'success';
                    $_SESSION['msj2'] = "Receta almacenada exitosamente";
                } else {
                    $_SESSION['alert'] = 'error';
                    $_SESSION['msj2'] = "Error al almacenar la receta.";
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
        if ($rol == 1) {
            header('Location: ../Vista/IndexAdmin.php');
        } else {
            header('Location: ../Vista/IndexGerente.php');
        }
        exit();
    }
}

mysqli_close($conexion);

?> 
