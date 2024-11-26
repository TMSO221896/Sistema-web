<?php 
error_reporting(0);
ini_set('display_errors', 0);
session_start();
$pagina_anterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';
require '../Modelo/conexion/conexion.php';  // Conexión a la base de datos

// Asegurarse de que el usuario está logueado y la sesión contiene el 'usuario'
if (!isset($_SESSION['usuario'])) {
	header("Location: ../Vista/Iniciosesion.php");
    exit;
}

$user_id = $_SESSION['usuario'];  // Aquí asumimos que 'usuario' es el ID o username

// Obtener los datos del usuario logueado desde la base de datos
$stmt = $conexion->prepare("SELECT nombre, apellido, rol FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $user_id);  // Usamos "s" si 'usuario' es una cadena (e.g. nombre o correo)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $nombre = $usuario['nombre'];
    $apellido = $usuario['apellido'];
	$rol = $usuario['rol'];
} else {
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "Error al obtener los datos del usuario.";
    header("Location: $pagina_anterior");
    exit;
}

$stmt->close();

// Consultar los detalles del pastel seleccionado
$pastel_id = $_POST['id_pastel'];  // Este debería venir de algún lugar, como $_GET['id'] o similar
$query_pastel = $conexion->prepare("SELECT * FROM pasteles WHERE id_pastel = ?");
$query_pastel->bind_param("i", $pastel_id);
$query_pastel->execute();
$result_pastel = $query_pastel->get_result();

if ($result_pastel->num_rows > 0) {
    $pastel = $result_pastel->fetch_assoc();
} else {
	$_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "En proceso de horneado.";
    header("Location: $pagina_anterior");
    exit;
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Verificar si se envió el formulario y agregar los datos al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_actual = date('Y-m-d');
    $fecha_entrega = $_POST['fecha_entrega'];

    // Validar que la fecha de entrega no sea menor a la fecha actual
    if ($fecha_entrega < $fecha_actual) {
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "La fecha de entrega no puede ser menor a la fecha actual.";
        header("Location: $pagina_anterior");
        exit;
    }

    if (isset($_POST['add_pastel'])) {
            $pedido = array(
                'user_id' => $user_id,  // Usar el user_id del usuario logueado
                'nombre' => $nombre,    // Usar el nombre desde la base de datos
                'apellido' => $apellido, // Usar el apellido desde la base de datos
                'fecha_actual' => date('Y-m-d'),
                'fecha_entrega' => $_POST['fecha_entrega'],
                'pastel_id' => $pastel['id_pastel'],  // Tomar el ID del pastel consultado
                'nombreP' => $pastel['nombreP'], // Asegúrate que el campo es 'nombre_pastel' en la tabla
                'color' => $_POST['color'],
                'texto' => $_POST['texto'],
                'precio'=> $pastel['precio']
        );
    }else{
        $pedido = array(
            'user_id' => $user_id,  // Usar el user_id del usuario logueado
            'nombre' => $nombre,    // Usar el nombre desde la base de datos
            'apellido' => $apellido, // Usar el apellido desde la base de datos
            'fecha_actual' => $fecha_actual,
            'fecha_entrega' => $fecha_entrega,
            'pastel_id' => $pastel['id_pastel'],  // Tomar el ID del pastel consultado
            'nombreP' => $pastel['nombreP'], // Asegúrate que el campo es 'nombreP' en la tabla
            'precio' => $pastel['precio']
        );
    }

    // Añadir el pedido al carrito (arreglo de sesión)
    $_SESSION['carrito'][] = $pedido;

    $_SESSION['alert'] = 'success';
    $_SESSION['msj2'] = "Producto Agregado";
    header("Location: $pagina_anterior");
}

$numero_articulos = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;

?>
