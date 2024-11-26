<?php
require '../Modelo/conexion/conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}

$idPedido = $_POST['id_pedido'];
$status = $_POST['status'];

// Obtener información del pedido
$sql = "SELECT * FROM pedidos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idPedido);
$stmt->execute();
$result = $stmt->get_result();
while ($rows = $result->fetch_assoc()) {
    $fec_Reg = $rows['fecha_actual'];
    $Total = $rows['Total'];
}
$stmt->close();

// Obtener artículos relacionados con el pedido
$sql0 = "SELECT pastel_id FROM pedido_articulos WHERE pedido_id = ?";
$stmt0 = $conexion->prepare($sql0);
$stmt0->bind_param("i", $idPedido);
$stmt0->execute();
$result0 = $stmt0->get_result();

// Almacenar los pasteles para la bitácora
$pasteles = [];
while ($rows = $result0->fetch_assoc()) {
    $pastel_id = $rows['pastel_id'];

    // Obtener el precio y nombre del pastel
    $sqlPrecio = "SELECT precio, nombreP FROM pasteles WHERE id_pastel = ?";
    $stmtPrecio = $conexion->prepare($sqlPrecio);
    $stmtPrecio->bind_param("i", $pastel_id);
    $stmtPrecio->execute();
    $resultPrecio = $stmtPrecio->get_result();
    if ($precioRow = $resultPrecio->fetch_assoc()) {
        $precio = $precioRow['precio'];
        $nombreP = $precioRow['nombreP'];
        $pasteles[] = ['pastel_id' => $pastel_id, 'precio' => $precio, 'nombreP' => $nombreP];
    }
    $stmtPrecio->close();
}
$stmt0->close();

// Actualizar el estado del pedido
$sql1 = "UPDATE pedidos SET estatus = ? WHERE id = ?";
$stmt1 = $conexion->prepare($sql1);
$stmt1->bind_param("si", $status, $idPedido);
$execute1 = $stmt1->execute();
$stmt1->close();

// Insertar en bitácoras según el estado
if ($status == 'Entregado') {
    $sql2 = "INSERT INTO bitacora_pedidos (pedido_id, accion, total, fecha) VALUES (?, ?, ?, ?)";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("isds", $idPedido, $status, $Total, $fec_Reg);
    $stmt2->execute();
    $stmt2->close();

    // Insertar cada pastel en la bitácora de pasteles
    foreach ($pasteles as $pastel) {
        $pastel_id = $pastel['pastel_id'];
        $nombrePast = $pastel['nombreP'];
        $precio = $pastel['precio'];

        $sql3 = "INSERT INTO bitacora_pasteles ( nombreP, fecCompra, Precio) VALUES ( ?, ?, ?)";
        $stmt3 = $conexion->prepare($sql3);
        $stmt3->bind_param("ssd", $nombrePast, $fec_Reg, $precio);
        $stmt3->execute();
        $stmt3->close();
    }
} elseif ($status == 'Cancelado') {
    $sql2 = "INSERT INTO bitacora_pedidos (pedido_id, accion, total, fecha) VALUES (?, ?, ?, ?)";
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->bind_param("isds", $idPedido, $status, $Total, $fec_Reg);
    $stmt2->execute();
    $stmt2->close();
}

// Mensajes de éxito o error
if ($execute1) {
    $_SESSION['alert'] = 'success';
    $_SESSION['msj2'] = "Estatus actualizado con éxito!";
} else {
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "Error al actualizar el Estatus.";
}

header('Location: ../Vista/IndexAdmin.php');
exit();

mysqli_close($conexion);
?>
