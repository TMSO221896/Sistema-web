<?php
require '../Modelo/conexion/conexion.php';  // Conexión a la base de datos

// Validar y recibir el ID del pedido
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

// Consultar los artículos del pedido
$consulta = "SELECT pa.pastel_id, pa.color, pa.texto,pa.fecEntrega, p.nombreP, p.precio
             FROM pedido_articulos pa
             JOIN pasteles p ON pa.pastel_id = p.id_pastel
             WHERE pa.pedido_id = $id_pedido";
$resultado = mysqli_query($conexion, $consulta);
?>

<section class="container specials">
    <a name="pasteles"></a>
    <div class="slider-scroll">
        <?php
            foreach ($resultado as $row) {
                $nombre_pastel = $row['nombreP'];
                $imagen = "../Static/img/Productos/" . $nombre_pastel . ".png"; // Cambia la extensión según el formato de tus imágenes
                // Verifica si la imagen existe; si no, usa una imagen de "sin foto"
                if (!file_exists($imagen)) {
                    $imagen = "../Static/img/nophoto.png"; // Imagen predeterminada si no hay foto
                }
        ?>
            <div class="card" style="width: 200px; margin: 10px;">
                <img src="<?php echo htmlspecialchars($imagen); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($nombre_pastel); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($nombre_pastel); ?></h5>
                    <p class="card-text">Entrega: <?php echo htmlspecialchars($row['fecEntrega']); ?></p>
                    <p class="card-text">Color: <?php echo htmlspecialchars($row['color']); ?></p>
                    <p class="card-text">Texto: <?php echo htmlspecialchars($row['texto']); ?></p>
                    <p class="card-text">Precio: $<?php echo htmlspecialchars($row['precio']); ?></p>
                    <div class="button-group">
                        <span><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>
    </div>
</section>

<style>
    .container.specials {
        padding: 20px;
        background-color: #f9f9f9;
    }

    .slider-scroll {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        scroll-behavior: smooth;
        padding: 10px;
        border: 1px solid #ccc; /* Borde opcional */
    }

    .slider-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .slider-scroll::-webkit-scrollbar-thumb {
        background-color: #888; 
        border-radius: 4px;
    }

    .slider-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    .card {
        flex: 0 0 auto; /* Evita que las tarjetas se reduzcan en tamaño */
        transition: transform 0.3s;
        margin: 10px;
        width: 200px;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
