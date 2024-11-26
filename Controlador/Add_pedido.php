<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
$user = $_SESSION['usuario'];
require '../Modelo/conexion/conexion.php';

// Prepara la consulta para obtener el rol del usuario
$consulta = "SELECT rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);
$rol = $rows['rol'];

// Verificar si el carrito está definido y no está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "No hay productos en el carrito.";
    header('Location: ../Vista/carrito.php');
}

// Calcular el total de precios en el carrito
$total = 0;
foreach ($_SESSION['carrito'] as $pedido) {
    $total += $pedido['precio'];
}

// Verificar si se presionó el botón "Realizar Pedido"
if (isset($_POST['realizar_pedido'])) {
    // Iniciar una transacción manualmente
    $conexion->begin_transaction();

    try {
        // Obtener los datos del primer artículo en el carrito
        $primer_pedido = $_SESSION['carrito'][0];

        // Verificar que los campos requeridos no sean null antes de la inserción
        if (empty($primer_pedido['user_id']) || empty($primer_pedido['nombre']) || empty($primer_pedido['apellido']) || 
            empty($primer_pedido['fecha_actual'])) {
                $_SESSION['alert'] = 'error';
                $_SESSION['msj2'] = "No hay productos en el carrito.";
                header('Location: ../Vista/Index.php');
        }

        // Insertar el pedido en la tabla `pedidos` con el total calculado
        $stmt_pedido = $conexion->prepare("INSERT INTO pedidos (user_id, fecha_actual, estatus, total) 
                                        VALUES (?, ?, 'Pendiente', ?)");
        if ($stmt_pedido === false) {
            throw new Exception("Error en la preparación de la consulta de pedido: " . $conexion->error);
        }

        $stmt_pedido->bind_param(
            "ssd",  // Nota: el último parámetro es un `double` (para el total)
            $primer_pedido['user_id'], 
            $primer_pedido['fecha_actual'],
            $total  // El total calculado se pasa aquí
        );

        if (!$stmt_pedido->execute()) {
            throw new Exception("Error al ejecutar la consulta del pedido: " . $stmt_pedido->error);
        }

        // Obtener el `id` del pedido recién insertado
        $pedido_id = $stmt_pedido->insert_id;

        // Preparar la consulta para insertar los productos en la tabla `pedido_articulos`
        $stmt_articulo = $conexion->prepare("INSERT INTO pedido_articulos (pedido_id, pastel_id, fecEntrega, color, texto) 
                                         VALUES (?, ?, ?, ?, ?)");
        if ($stmt_articulo === false) {
            throw new Exception("Error en la preparación de la consulta de artículos: " . $conexion->error);
        }

        // Insertar los productos del carrito en la tabla `pedido_articulos`
        foreach ($_SESSION['carrito'] as $pedido) {
            $stmt_articulo->bind_param(
                "issss", 
                $pedido_id,  // Usar el mismo id de pedido
                $pedido['pastel_id'], 
                $pedido['fecha_entrega'],
                $pedido['color'], 
                $pedido['texto']
            );

            if (!$stmt_articulo->execute()) {
                throw new Exception("Error al ejecutar la consulta para los artículos del pedido: " . $stmt_articulo->error);
            }
        }

        // Confirmar la transacción
        $conexion->commit();

        // Vaciar el carrito después de la inserción exitosa
        unset($_SESSION['carrito']);
        $_SESSION['alert'] = 'success';
        $_SESSION['msj2'] = "Pedido realizado con éxito!.";
        header('Location: ../Vista/carrito.php');
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $conexion->rollback();
        echo "Error al realizar el pedido: " . $e->getMessage();
    }

    // Cerrar las declaraciones si fueron preparadas correctamente
    if (isset($stmt_pedido)) {
        $stmt_pedido->close();
    }
    if (isset($stmt_articulo)) {
        $stmt_articulo->close();
    }
}

// Cuenta los elementos en el carrito
$numero_articulos = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;

?>
