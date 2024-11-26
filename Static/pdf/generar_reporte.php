<?php
require('fpdf186/fpdf.php'); // Usa la ruta relativa adecuada

function conectar_db() {
    $conn = new mysqli("localhost", "root", "", "claus", 3307);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

if (isset($_POST['tipo_reporte'])) {
    $tipo_reporte = $_POST['tipo_reporte'];
    $pdf = new FPDF();
    $pdf->SetTitle('Reportes del Sistema');
    $pdf->SetMargins(10, 10, 10); // Establecer márgenes

    switch ($tipo_reporte) {
        case 'pedidos':
            generar_reporte_pedidos($pdf);
            break;

        case 'usuarios_nuevos':
            generar_reporte_usuarios_nuevos($pdf);
            break;

        case 'usuarios_eliminados':
            generar_reporte_usuarios_eliminados($pdf);
            break;

        case 'producto_mas_vendido':
            generar_reporte_producto_mas_vendido($pdf);
            break;

        case 'producto_menos_vendido':
            generar_reporte_producto_menos_vendido($pdf);
            break;

        default:
            // Manejo de error si el tipo de reporte no es válido
            echo "Tipo de reporte no válido.";
            exit; // Salir para evitar que se genere un PDF vacío
    }

    // Salida del PDF
    header('Content-Type: application/pdf'); // Establecer el tipo de contenido
    $pdf->Output();
} else {
    // Manejo de error si no se ha enviado ningún tipo de reporte
    echo "No se ha enviado ningún tipo de reporte.";
}

// Función para generar el reporte de pedidos
function generar_reporte_pedidos($pdf) {
    $conn = conectar_db();
    
    // Obtener pedidos concretados (acciones de 'Creado' o 'Eliminado') con ID y ganancias por mes desde la bitácora
    $sql_concretados = "SELECT 
                            bp.pedido_id AS id_pedido,
                            bp.accion AS Estatus,
                            MONTH(bp.fecha) AS Mes, 
                            YEAR(bp.fecha) AS Anio, 
                            bp.total AS Total
                        FROM bitacora_pedidos bp
                        WHERE bp.accion = 'Entregado'
                        GROUP BY Anio, Mes, bp.pedido_id
                        ORDER BY Anio, Mes;";

    $result_concretados = $conn->query($sql_concretados);
    
    // Verificar si la consulta de pedidos concretados fue exitosa
    if (!$result_concretados) {
        die('Error en la consulta de pedidos concretados: ' . $conn->error);
    }

    // Obtener pedidos cancelados desde la bitácora
    $sql_cancelados = "SELECT 
                            bp.pedido_id AS id_pedido,
                            bp.accion AS Estatus,
                            MONTH(bp.fecha) AS Mes, 
                            YEAR(bp.fecha) AS Anio, 
                            bp.total AS Total
                        FROM bitacora_pedidos bp
                        WHERE bp.accion = 'Cancelado'
                        GROUP BY Anio, Mes, bp.pedido_id
                        ORDER BY Anio, Mes;";
    
    $result_cancelados = $conn->query($sql_cancelados);
    
    // Verificar si la consulta de pedidos cancelados fue exitosa
    if (!$result_cancelados) {
        die('Error en la consulta de pedidos cancelados: ' . $conn->error);
    }
    
    $pdf->AddPage();
    $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    
    // Verificar si la imagen existe antes de intentar agregarla
    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 10, 10, 30); // Posición (X, Y) y tamaño (ancho, alto)
    } else {
        // Si no se encuentra la imagen, mostrar un mensaje en el PDF
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
        $pdf->Ln(10);
    }

    // Información de la empresa: Dirección y Teléfono
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C'); // Nombre de la empresa
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C'); // Dirección de la empresa
    $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C'); // Teléfono de la empresa
    $pdf->Ln(10);  // Espacio después de la información de la empresa

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Pedidos', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Tabla de pedidos concretados
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Pedidos Concretados (Ganancias)', 0, 1, 'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'ID Pedido', 1);
    $pdf->Cell(30, 10, 'Estatus', 1);
    $pdf->Cell(30, 10, 'Mes', 1);
    $pdf->Cell(50, 10, 'Anio', 1);
    $pdf->Cell(50, 10, 'Total', 1);
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', '', 12);
    if ($result_concretados->num_rows > 0) {
        while ($row = $result_concretados->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['id_pedido'], 1); // ID del pedido
            $pdf->Cell(30, 10, $row['Estatus'], 1); // ID del pedido
            $pdf->Cell(30, 10, date('F', mktime(0, 0, 0, $row['Mes'], 1)), 1); // Nombre del mes
            $pdf->Cell(50, 10, $row['Anio'], 1);
            $pdf->Cell(50, 10, '$' . number_format($row['Total'], 2), 1); // Formato monetario
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }
    
    $pdf->Ln(10);
    
    // Tabla de pedidos cancelados
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Pedidos Cancelados (Perdidas)', 0, 1, 'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'ID Pedido', 1);
    $pdf->Cell(30, 10, 'Estatus', 1);
    $pdf->Cell(30, 10, 'Mes', 1);
    $pdf->Cell(50, 10, 'Anio', 1);
    $pdf->Cell(50, 10, 'Total', 1);
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', '', 12);
    if ($result_cancelados->num_rows > 0) {
        while ($row = $result_cancelados->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['id_pedido'], 1); // ID del pedido
            $pdf->Cell(30, 10, $row['Estatus'], 1); // ID del pedido
            $pdf->Cell(30, 10, date('F', mktime(0, 0, 0, $row['Mes'], 1)), 1); // Nombre del mes
            $pdf->Cell(50, 10, $row['Anio'], 1);
            $pdf->Cell(50, 10, '$' . number_format($row['Total'], 2), 1); // Formato monetario
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }
    
    // Cerrar conexión
    $conn->close();
    
    // Salvar el PDF
    $pdf->Output();
}

function generar_reporte_usuarios_nuevos($pdf) {
    $conn = conectar_db();
    
    // Consulta para obtener todos los usuarios nuevos, agrupados por mes y año
    $sql = "SELECT * FROM bitacora_usuarios WHERE accion = 'Creado' ORDER BY YEAR(fecha), MONTH(fecha), fecha"; 
    $result = $conn->query($sql);
    
    // Variables para manejar el mes y año actuales
    $mes_actual = null;
    $anio_actual = null;
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mes = date('m', strtotime($row['fecha']));  // Extraer mes de la fecha
            $anio = date('Y', strtotime($row['fecha'])); // Extraer año de la fecha
            
            // Cambiar de mes/año si es necesario
            if ($mes_actual !== $mes || $anio_actual !== $anio) {
                // Si ya hay un mes anterior, agregar un espacio antes de comenzar el nuevo mes
                if ($mes_actual !== null) {
                    $pdf->Ln(10); // Espacio entre meses
                }
                
                // Actualizar mes y año actuales
                $mes_actual = $mes;
                $anio_actual = $anio;
                
                // Título del mes
                $pdf->AddPage();
                $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    
                // Verificar si la imagen existe antes de intentar agregarla
                if (file_exists($imagePath)) {
                    $pdf->Image($imagePath, 10, 10, 30); // Posición (X, Y) y tamaño (ancho, alto)
                } else {
                    // Si no se encuentra la imagen, mostrar un mensaje en el PDF
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
                    $pdf->Ln(10);
                }

                // Información de la empresa: Dirección y Teléfono
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C'); // Nombre de la empresa
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C'); // Dirección de la empresa
                $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C'); // Teléfono de la empresa
                $pdf->Ln(10);  // Espacio después de la información de la empresa

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Usuarios Nuevos - ' . date('F Y', mktime(0, 0, 0, $mes_actual, 1, $anio_actual)), 0, 1, 'C');
                $pdf->Ln(10);
                
                // Encabezados de la tabla
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(30, 10, 'ID', 1);
                $pdf->Cell(50, 10, 'Usuario ID', 1);
                $pdf->Cell(50, 10, 'Acción', 1);
                $pdf->Cell(50, 10, 'Fecha', 1);
                $pdf->Ln(10);
                
                $pdf->SetFont('Arial', '', 12);
            }
            
            // Imprimir los datos de cada usuario nuevo
            $pdf->Cell(30, 10, $row['id'], 1); // ID
            $pdf->Cell(50, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(50, 10, $row['accion'], 1); // Acción
            $pdf->Cell(50, 10, $row['fecha'], 1); // Fecha
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    $conn->close();
}

function generar_reporte_usuarios_eliminados($pdf) {
    $conn = conectar_db();
    
    // Consulta para obtener todos los usuarios eliminados, agrupados por mes y año
    $sql = "SELECT * FROM bitacora_usuarios WHERE accion = 'Eliminado' ORDER BY YEAR(fecha), MONTH(fecha), fecha";
    $result = $conn->query($sql);
    
    // Variables para manejar el mes y año actuales
    $mes_actual = null;
    $anio_actual = null;
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mes = date('m', strtotime($row['fecha']));  // Extraer mes de la fecha
            $anio = date('Y', strtotime($row['fecha'])); // Extraer año de la fecha
            
            // Cambiar de mes/año si es necesario
            if ($mes_actual !== $mes || $anio_actual !== $anio) {
                // Si ya hay un mes anterior, agregar un espacio antes de comenzar el nuevo mes
                if ($mes_actual !== null) {
                    $pdf->Ln(10); // Espacio entre meses
                }
                
                // Actualizar mes y año actuales
                $mes_actual = $mes;
                $anio_actual = $anio;
                
                // Título del mes
                $pdf->AddPage();
                $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    
                // Verificar si la imagen existe antes de intentar agregarla
                if (file_exists($imagePath)) {
                    $pdf->Image($imagePath, 10, 10, 30); // Posición (X, Y) y tamaño (ancho, alto)
                } else {
                    // Si no se encuentra la imagen, mostrar un mensaje en el PDF
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
                    $pdf->Ln(10);
                }

                // Información de la empresa: Dirección y Teléfono
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C'); // Nombre de la empresa
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C'); // Dirección de la empresa
                $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C'); // Teléfono de la empresa
                $pdf->Ln(10);  // Espacio después de la información de la empresa

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Usuarios Eliminados - ' . date('F Y', mktime(0, 0, 0, $mes_actual, 1, $anio_actual)), 0, 1, 'C');
                $pdf->Ln(10);
                
                // Encabezados de la tabla
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(30, 10, 'ID', 1);
                $pdf->Cell(50, 10, 'Usuario ID', 1);
                $pdf->Cell(50, 10, 'Acción', 1);
                $pdf->Cell(50, 10, 'Fecha', 1);
                $pdf->Ln(10);
                
                $pdf->SetFont('Arial', '', 12);
            }
            
            // Imprimir los datos de cada usuario eliminado
            $pdf->Cell(30, 10, $row['id'], 1); // ID
            $pdf->Cell(50, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(50, 10, $row['accion'], 1); // Acción
            $pdf->Cell(50, 10, $row['fecha'], 1); // Fecha
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    $conn->close();
}


// Función para generar el reporte del producto más vendido
function generar_reporte_producto_mas_vendido($pdf) {
    $conn = conectar_db();
    
    // Consulta para obtener los 5 productos más vendidos por mes y las ganancias totales
    $sql = "SELECT MONTH(fecCompra) AS mes, 
                   YEAR(fecCompra) AS anio, 
                   nombreP AS nombre, 
                   COUNT(*) AS total_vendidos,
                   SUM(Precio) AS total_ganado
            FROM bitacora_pasteles
            GROUP BY anio, mes, nombreP
            ORDER BY anio, mes, total_vendidos DESC;"; 

    $result = $conn->query($sql);

    // Inicializar variables para el mes actual y contador
    $mes_actual = null;
    $anio_actual = null;
    $productos_mostrados = 0; // Contador de productos mostrados por mes

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Verificar si se ha cambiado de mes/año
            if ($mes_actual !== $row['mes'] || $anio_actual !== $row['anio']) {
                // Si ya hay un mes/año anterior, agregar un espacio antes de comenzar el nuevo mes/año
                if ($mes_actual !== null) {
                    $pdf->Ln(10); // Espacio entre meses/años
                }

                // Actualizar mes y año actuales
                $mes_actual = $row['mes'];
                $anio_actual = $row['anio'];
                $productos_mostrados = 0; // Reiniciar contador de productos mostrados

                // Título del mes
                $pdf->AddPage();
                $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    
                // Verificar si la imagen existe antes de intentar agregarla
                if (file_exists($imagePath)) {
                    $pdf->Image($imagePath, 10, 10, 30); // Posición (X, Y) y tamaño (ancho, alto)
                } else {
                    // Si no se encuentra la imagen, mostrar un mensaje en el PDF
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
                    $pdf->Ln(10);
                }

                // Información de la empresa: Dirección y Teléfono
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C'); // Nombre de la empresa
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C'); // Dirección de la empresa
                $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C'); // Teléfono de la empresa
                $pdf->Ln(10);  // Espacio después de la información de la empresa

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Top 5 Productos Mas Vendidos del Mes - ' . date('F Y', mktime(0, 0, 0, $mes_actual, 1, $anio_actual)), 0, 1, 'C');
                $pdf->Ln(10);
                
                // Encabezados de la tabla
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(50, 10, 'Nombre Pastel', 1);
                $pdf->Cell(40, 10, 'Total Vendidos', 1);
                $pdf->Cell(50, 10, 'Total Ganado', 1);
                $pdf->Ln(10);
                
                $pdf->SetFont('Arial', '', 12);
            }
            
            // Mostrar los 5 productos más vendidos por mes
            if ($productos_mostrados < 5) {
                $pdf->Cell(50, 10, $row['nombre'], 1); // Nombre del producto
                $pdf->Cell(40, 10, $row['total_vendidos'], 1); // Total vendidos
                $pdf->Cell(50, 10, number_format($row['total_ganado'], 2), 1); // Total ganado con formato
                $pdf->Ln(10);
                $productos_mostrados++;
            }
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    $conn->close();
}

// Función para generar el reporte del producto menos vendido
function generar_reporte_producto_menos_vendido($pdf) {
    $conn = conectar_db();
    
    // Definir mes y año actual si no están definidos
    $mes_actual = date('m');  // Mes actual
    $anio_actual = date('Y'); // Año actual
    
    // Consulta para obtener los 5 productos menos vendidos
    $sql = "SELECT p.id_pastel, 
                   p.nombreP AS nombre, 
                   COALESCE(COUNT(b.id), 0) AS total_vendidos
            FROM pasteles p
            LEFT JOIN bitacora_pasteles b ON p.nombreP = b.nombreP
            GROUP BY p.id_pastel, p.nombreP
            ORDER BY total_vendidos ASC
            LIMIT 5;";
    $result = $conn->query($sql);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    // Agregar página al PDF
    $pdf->AddPage();
    
    // Ruta del logotipo de la empresa (verificar que esta ruta sea correcta)
    $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    
    // Verificar si la imagen existe antes de intentar agregarla
    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 10, 10, 30); // Posición (X, Y) y tamaño (ancho, alto)
    } else {
        // Si no se encuentra la imagen, mostrar un mensaje en el PDF
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
        $pdf->Ln(10);
    }

    // Información de la empresa: Dirección y Teléfono
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C'); // Nombre de la empresa
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C'); // Dirección de la empresa
    $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C'); // Teléfono de la empresa
    $pdf->Ln(10);  // Espacio después de la información de la empresa

    // Título del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Top 5 Productos Menos Vendidos del Mes - ' . date('F Y', mktime(0, 0, 0, $mes_actual, 1, $anio_actual)), 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'ID Pastel', 1);
    $pdf->Cell(50, 10, 'Nombre Pastel', 1);
    $pdf->Cell(50, 10, 'Total Vendidos', 1);
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    // Mostrar los productos menos vendidos
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(40, 10, $row['id_pastel'], 1); // ID del producto
            $pdf->Cell(50, 10, $row['nombre'], 1); // Nombre del producto
            $pdf->Cell(50, 10, $row['total_vendidos'], 1); // Total vendidos
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Generar el PDF y mostrarlo
    $pdf->Output();
}




?>
