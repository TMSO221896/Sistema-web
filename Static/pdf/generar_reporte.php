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

        case 'usuarios_nuevos_eliminados':
            generar_reporte_usuarios($pdf);
            break;

        case 'recetas':
            generar_reporte_recetas($pdf);
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
function generar_reporte_pedidos($pdf)
{
    $conn = conectar_db();

    // Verificar que las fechas se envían correctamente
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if (!$fecha_inicio || !$fecha_fin) {
        die('Error: Debes proporcionar un rango de fechas.');
    }

    // Consultar pedidos concretados en el rango de fechas
    $sql_concretados = "SELECT 
                            bp.pedido_id AS id_pedido,
                            bp.accion AS Estatus,
                            DAY(bp.fecha) AS Dia,
                            MONTH(bp.fecha) AS Mes,
                            YEAR(bp.fecha) AS Anio,
                            bp.total AS Total
                        FROM bitacora_pedidos bp
                        WHERE bp.accion = 'Entregado'
                          AND bp.fecha BETWEEN ? AND ?
                        GROUP BY Anio, Mes, Dia, bp.pedido_id
                        ORDER BY Anio, Mes, Dia;";
    $stmt_concretados = $conn->prepare($sql_concretados);
    $stmt_concretados->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_concretados->execute();
    $result_concretados = $stmt_concretados->get_result();

    // Consultar pedidos cancelados en el rango de fechas
    $sql_cancelados = "SELECT 
                           bp.pedido_id AS id_pedido,
                           bp.accion AS Estatus,
                           DAY(bp.fecha) AS Dia,
                           MONTH(bp.fecha) AS Mes,
                           YEAR(bp.fecha) AS Anio,
                           bp.total AS Total
                       FROM bitacora_pedidos bp
                       WHERE bp.accion = 'Cancelado'
                         AND bp.fecha BETWEEN ? AND ?
                       GROUP BY Anio, Mes, Dia, bp.pedido_id
                       ORDER BY Anio, Mes, Dia;";
    $stmt_cancelados = $conn->prepare($sql_cancelados);
    $stmt_cancelados->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt_cancelados->execute();
    $result_cancelados = $stmt_cancelados->get_result();

    // Generar el PDF
    $pdf->AddPage();
    $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';

    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 10, 10, 30);
    } else {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Logotipo no encontrado. Verifique la ruta de la imagen.', 0, 1, 'C');
        $pdf->Ln(10);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Pedidos', 0, 1, 'C');
    $pdf->Ln(10);

    // Pedidos concretados
    $total_concretados = 0;
    $conteo_concretados = $result_concretados->num_rows;

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Pedidos Concretados (Ganancias): $conteo_concretados pedidos", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Estatus', 1);
    $pdf->Cell(20, 10, 'Dia', 1);
    $pdf->Cell(30, 10, 'Mes', 1);
    $pdf->Cell(20, 10, 'Anio', 1);
    $pdf->Cell(40, 10, 'Total', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    if ($conteo_concretados > 0) {
        while ($row = $result_concretados->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id_pedido'], 1);
            $pdf->Cell(30, 10, $row['Estatus'], 1);
            $pdf->Cell(20, 10, $row['Dia'], 1);
            $pdf->Cell(30, 10, date('F', mktime(0, 0, 0, $row['Mes'], 1)), 1);
            $pdf->Cell(20, 10, $row['Anio'], 1);
            $pdf->Cell(40, 10, '$' . number_format($row['Total'], 2), 1);
            $pdf->Ln();

            $total_concretados += $row['Total'];
        }
    } else {
        $pdf->Cell(0, 10, 'No hay pedidos concretados en este rango de fechas.', 0, 1, 'C');
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Total Ganado: $" . number_format($total_concretados, 2), 0, 1, 'C');
    $pdf->Ln(10);

    // Pedidos cancelados
    $total_cancelados = 0;
    $conteo_cancelados = $result_cancelados->num_rows;

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Pedidos Cancelados (Perdidas): $conteo_cancelados pedidos", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Estatus', 1);
    $pdf->Cell(20, 10, 'Dia', 1);
    $pdf->Cell(30, 10, 'Mes', 1);
    $pdf->Cell(20, 10, 'Anio', 1);
    $pdf->Cell(40, 10, 'Total', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    if ($conteo_cancelados > 0) {
        while ($row = $result_cancelados->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id_pedido'], 1);
            $pdf->Cell(30, 10, $row['Estatus'], 1);
            $pdf->Cell(20, 10, $row['Dia'], 1);
            $pdf->Cell(30, 10, date('F', mktime(0, 0, 0, $row['Mes'], 1)), 1);
            $pdf->Cell(20, 10, $row['Anio'], 1);
            $pdf->Cell(40, 10, '$' . number_format($row['Total'], 2), 1);
            $pdf->Ln();

            $total_cancelados += $row['Total'];
        }
    } else {
        $pdf->Cell(0, 10, 'No hay pedidos cancelados en este rango de fechas.', 0, 1, 'C');
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Total Perdido: $" . number_format($total_cancelados, 2), 0, 1, 'C');

    // Cerrar conexión y generar PDF
    $conn->close();
    $pdf->Output();
}


function generar_reporte_usuarios($pdf) {
    $conn = conectar_db();
    
    // Obtener las fechas de inicio y fin del formulario
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    
    // Consulta para obtener todos los usuarios nuevos, agrupados por mes y año
    $sql_creados = "SELECT * FROM bitacora_usuarios WHERE accion = 'Creado' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY YEAR(fecha), MONTH(fecha), fecha"; 
    $result_creados = $conn->query($sql_creados);

    $sql_eliminados = "SELECT * FROM bitacora_usuarios WHERE accion = 'Eliminado' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY YEAR(fecha), MONTH(fecha), fecha";
    $result_eliminados = $conn->query($sql_eliminados);
    
    // Variables para manejar el mes y año actuales
    $mes_actual = null;
    $anio_actual = null;
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

    // Obtener el conteo de usuarios creados
    $count_creados = $result_creados->num_rows;

    // Título de la sección de usuarios creados con el conteo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Usuarios Nuevos Creado(s): ' . $count_creados, 0, 1, 'C');
    $pdf->Ln(10);
    
    // Encabezados de la tabla (Día, Mes, Año en columnas separadas)
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, 10, 'ID', 1);
    $pdf->Cell(40, 10, 'Usuario ID', 1);
    $pdf->Cell(40, 10, 'Acción', 1);
    $pdf->Cell(25, 10, 'Día', 1);
    $pdf->Cell(25, 10, 'Mes', 1);
    $pdf->Cell(25, 10, 'Año', 1);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 10);
    if ($result_creados->num_rows > 0) {
        while ($row = $result_creados->fetch_assoc()) {
            // Obtener el día, mes y año de la fecha
            $fecha = strtotime($row['fecha']);
            $dia = date('d', $fecha);
            $mes = date('m', $fecha);
            $anio = date('Y', $fecha);
            
            // Imprimir los datos de cada usuario nuevo con las columnas separadas
            $pdf->Cell(25, 10, $row['id'], 1); // ID
            $pdf->Cell(40, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(40, 10, $row['accion'], 1); // Acción
            $pdf->Cell(25, 10, $dia, 1); // Día
            $pdf->Cell(25, 10, $mes, 1); // Mes
            $pdf->Cell(25, 10, $anio, 1); // Año
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    // Obtener el conteo de usuarios eliminados
    $count_eliminados = $result_eliminados->num_rows;

    // Título de la sección de usuarios eliminados con el conteo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Usuarios Eliminado(s): ' . $count_eliminados, 0, 1, 'C');
    $pdf->Ln(10);
    
    // Encabezados de la tabla (Día, Mes, Año en columnas separadas)
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, 10, 'ID', 1);
    $pdf->Cell(40, 10, 'Usuario ID', 1);
    $pdf->Cell(40, 10, 'Acción', 1);
    $pdf->Cell(25, 10, 'Día', 1);
    $pdf->Cell(25, 10, 'Mes', 1);
    $pdf->Cell(25, 10, 'Año', 1);
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', '', 10);
    if ($result_eliminados->num_rows > 0) {
        while ($row = $result_eliminados->fetch_assoc()) {
            // Obtener el día, mes y año de la fecha
            $fecha = strtotime($row['fecha']);
            $dia = date('d', $fecha);
            $mes = date('m', $fecha);
            $anio = date('Y', $fecha);
            
            // Imprimir los datos de cada usuario eliminado con las columnas separadas
            $pdf->Cell(25, 10, $row['id'], 1); // ID
            $pdf->Cell(40, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(40, 10, $row['accion'], 1); // Acción
            $pdf->Cell(25, 10, $dia, 1); // Día
            $pdf->Cell(25, 10, $mes, 1); // Mes
            $pdf->Cell(25, 10, $anio, 1); // Año
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar.', 0, 1, 'C');
    }

    $conn->close();
}

function generar_reporte_recetas($pdf) {
    $conn = conectar_db();
    
    // Comprobar si la conexión es exitosa
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener las fechas de inicio y fin del formulario
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    
    // Comprobamos que las fechas no estén vacías
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $pdf->Cell(0, 10, 'Por favor ingrese ambas fechas (inicio y fin).', 0, 1, 'C');
        return;
    }

    // Añadir una página antes de cualquier contenido
    $pdf->AddPage();

    // Configurar la fuente antes de agregar texto
    $pdf->SetFont('Arial', '', 12);
    
    // Consulta para obtener las recetas creadas en el rango de fechas
    $sql_creadas = "SELECT * FROM bitacora_recetas WHERE accion = 'Creado' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY YEAR(fecha), MONTH(fecha), fecha";
    $result_creadas = $conn->query($sql_creadas);

    // Depuración: Verificar si la consulta devuelve resultados
    if ($result_creadas === false) {
        $pdf->Cell(0, 10, 'Error en la consulta SQL para recetas creadas: ' . $conn->error, 0, 1, 'C');
        return;
    }

    // Consulta para obtener las recetas eliminadas en el rango de fechas
    $sql_eliminadas = "SELECT * FROM bitacora_recetas WHERE accion = 'Eliminado' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY YEAR(fecha), MONTH(fecha), fecha"; 
    $result_eliminadas = $conn->query($sql_eliminadas);

    // Depuración: Verificar si la consulta devuelve resultados
    if ($result_eliminadas === false) {
        $pdf->Cell(0, 10, 'Error en la consulta SQL para recetas eliminadas: ' . $conn->error, 0, 1, 'C');
        return;
    }

    // Comprobamos si hay registros en ambas consultas
    if ($result_creadas->num_rows == 0) {
        $pdf->Cell(0, 10, 'No se encontraron recetas creadas en este periodo.', 0, 1, 'C');
    }

    if ($result_eliminadas->num_rows == 0) {
        $pdf->Cell(0, 10, 'No se encontraron recetas eliminadas en este periodo.', 0, 1, 'C');
    }

    // Variables para manejar el mes y año actuales
    $mes_actual = null;
    $anio_actual = null;

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

    // Título para las recetas creadas
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Recetas Creadas', 0, 1, 'C');
    $pdf->Ln(10);

    // Mostrar el conteo de recetas creadas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Total de recetas creadas: ' . $result_creadas->num_rows, 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezados de la tabla (Día, Mes, Año en columnas separadas)
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1); // Ajustado el ancho
    $pdf->Cell(35, 10, 'Receta ID', 1); // Ajustado el ancho
    $pdf->Cell(35, 10, 'Usuario ID', 1); // Ajustado el ancho
    $pdf->Cell(30, 10, 'Acción', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Día', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Mes', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Año', 1); // Ajustado el ancho
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 10);
    if ($result_creadas->num_rows > 0) {
        while ($row = $result_creadas->fetch_assoc()) {
            // Obtener el día, mes y año de la fecha
            $fecha = strtotime($row['fecha']);
            $dia = date('d', $fecha);
            $mes = date('m', $fecha);
            $anio = date('Y', $fecha);
            
            // Imprimir los datos de cada acción con las columnas separadas
            $pdf->Cell(20, 10, $row['id'], 1); // ID
            $pdf->Cell(35, 10, $row['receta_id'], 1); // Receta ID
            $pdf->Cell(35, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(30, 10, $row['accion'], 1); // Acción (Creado)
            $pdf->Cell(20, 10, $dia, 1); // Día
            $pdf->Cell(20, 10, $mes, 1); // Mes
            $pdf->Cell(20, 10, $anio, 1); // Año
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay recetas creadas en este periodo.', 0, 1, 'C');
    }

    // Título para las recetas eliminadas
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Recetas Eliminadas', 0, 1, 'C');
    $pdf->Ln(10);

    // Mostrar el conteo de recetas eliminadas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Total de recetas eliminadas: ' . $result_eliminadas->num_rows, 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezados de la tabla (Día, Mes, Año en columnas separadas)
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1); // Ajustado el ancho
    $pdf->Cell(35, 10, 'Receta ID', 1); // Ajustado el ancho
    $pdf->Cell(35, 10, 'Usuario ID', 1); // Ajustado el ancho
    $pdf->Cell(30, 10, 'Acción', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Día', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Mes', 1); // Ajustado el ancho
    $pdf->Cell(20, 10, 'Año', 1); // Ajustado el ancho
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 10);
    if ($result_eliminadas->num_rows > 0) {
        while ($row = $result_eliminadas->fetch_assoc()) {
            // Obtener el día, mes y año de la fecha
            $fecha = strtotime($row['fecha']);
            $dia = date('d', $fecha);
            $mes = date('m', $fecha);
            $anio = date('Y', $fecha);
            
            // Imprimir los datos de cada acción con las columnas separadas
            $pdf->Cell(20, 10, $row['id'], 1); // ID
            $pdf->Cell(35, 10, $row['receta_id'], 1); // Receta ID
            $pdf->Cell(35, 10, $row['usuario_id'], 1); // Usuario ID
            $pdf->Cell(30, 10, $row['accion'], 1); // Acción (Eliminado)
            $pdf->Cell(20, 10, $dia, 1); // Día
            $pdf->Cell(20, 10, $mes, 1); // Mes
            $pdf->Cell(20, 10, $anio, 1); // Año
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay recetas eliminadas en este periodo.', 0, 1, 'C');
    }

    // Cerrar la conexión a la base de datos
    $conn->close();

    // Devolver el archivo PDF generado
    $pdf->Output('I', 'reporte_recetas.pdf');
}

// Función para generar el reporte del producto más vendido
function generar_reporte_producto_mas_vendido($pdf) {
    $conn = conectar_db();

    // Obtener las fechas de inicio y fin desde el formulario
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    // Construir la consulta SQL
    $sql = "SELECT MONTH(fecCompra) AS mes, 
                   YEAR(fecCompra) AS anio, 
                   nombreP AS nombre, 
                   COUNT(*) AS total_vendidos,
                   SUM(Precio) AS total_ganado
            FROM bitacora_pasteles
            WHERE 1=1"; // Filtro dinámico, donde '1=1' es siempre verdadero

    // Agregar filtro por fecha de inicio, si se ha proporcionado
    if ($fecha_inicio) {
        $sql .= " AND fecCompra >= '$fecha_inicio'";
    }

    // Agregar filtro por fecha de fin, si se ha proporcionado
    if ($fecha_fin) {
        $sql .= " AND fecCompra <= '$fecha_fin'";
    }

    // Continuar con la consulta
    $sql .= " GROUP BY anio, mes, nombreP
              ORDER BY anio, mes, total_vendidos DESC"; 

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

                // Título del reporte con el rango de fechas seleccionado
                $rango_fechas = '';
                if ($fecha_inicio && $fecha_fin) {
                    $rango_fechas = 'Desde: ' . $fecha_inicio . ' Hasta: ' . $fecha_fin;
                } elseif ($fecha_inicio) {
                    $rango_fechas = 'Desde: ' . $fecha_inicio;
                } elseif ($fecha_fin) {
                    $rango_fechas = 'Hasta: ' . $fecha_fin;
                }

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Top 5 Productos Mas Vendidos - ',0, 1, 'C');
                $pdf->Cell(0, 10, $rango_fechas, 0, 1, 'C');
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

    // Obtener fechas del formulario
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    // Validar que las fechas estén definidas
    if (!$fecha_inicio || !$fecha_fin) {
        die("Por favor, ingrese las fechas de inicio y fin para el reporte.");
    }

    // Consulta para obtener los 5 productos menos vendidos en el rango de fechas
    $sql = "SELECT p.id_pastel, 
                   p.nombreP AS nombre, 
                   COALESCE(COUNT(b.id), 0) AS total_vendidos
            FROM pasteles p
            LEFT JOIN bitacora_pasteles b 
                ON p.nombreP = b.nombreP AND b.fecCompra BETWEEN ? AND ?
            GROUP BY p.id_pastel, p.nombreP
            ORDER BY total_vendidos ASC
            LIMIT 5;";
    
    // Preparar y ejecutar consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Agregar página al PDF
    $pdf->AddPage();
    
    // Ruta del logotipo de la empresa
    $imagePath = 'D:\xampp\htdocs\keniamiamor\Static\img\clausLogo.png';
    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 10, 10, 30);
    } else {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Logotipo no encontrado, por favor verifique la ruta de la imagen.', 0, 1, 'C');
        $pdf->Ln(10);
    }

    // Información de la empresa
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Claus Detalles y Confiteria', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Direccion: Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Telefono: 123-456-7890', 0, 1, 'C');
    $pdf->Ln(10);

    // Título del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Top 5 Productos Menos Vendidos', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Desde: ' . $fecha_inicio . ' Hasta: ' . $fecha_fin, 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'ID Pastel', 1);
    $pdf->Cell(70, 10, 'Nombre Pastel', 1);
    $pdf->Cell(50, 10, 'Total Vendidos', 1);
    $pdf->Ln(10);

    // Mostrar los productos menos vendidos
    if ($result->num_rows > 0) {
        $pdf->SetFont('Arial', '', 12);
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(40, 10, $row['id_pastel'], 1);
            $pdf->Cell(70, 10, $row['nombre'], 1);
            $pdf->Cell(50, 10, $row['total_vendidos'], 1);
            $pdf->Ln(10);
        }
    } else {
        $pdf->Cell(0, 10, 'No hay registros para mostrar en el rango de fechas especificado.', 0, 1, 'C');
    }

    // Cerrar conexión y generar PDF
    $stmt->close();
    $conn->close();
    $pdf->Output();
}




?>
