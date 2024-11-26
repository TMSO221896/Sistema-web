<?php
session_start();
require '../Modelo/conexion/conexion.php';  // Conexión a la base de datos
// Recibir el ID del pastel
$id_pastel = $_GET['id_pastel'];

?>

<table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Reseña</th>
                    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consultar las reseñas del pastel
                $consulta = "SELECT r.idUsuario, r.resenias,r.id, u.nombre
                FROM resenias r
                JOIN usuarios u ON r.idUsuario = u.usuario
                WHERE r.idpastel = '$id_pastel'";
                $resultado = mysqli_query($conexion, $consulta);
                $index = 1;

                $id_usuario = $_SESSION['usuario'];
                $consulta2 = "SELECT rol FROM usuarios where usuario = '$id_usuario'";
                $resultado2 = mysqli_query($conexion, $consulta2);
                if (mysqli_num_rows($resultado2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($resultado2)) {
                        $rol = $row2['rol'];
                    }
                }
                // Verificar si la consulta tiene resultados
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        $nombre = $row['nombre'];
                        $id_resenia = $row['id'];
                        $resenia = $row['resenias'];
                ?>
                <tr>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo $resenia; ?></td>
                    <?php if ($_SESSION['usuario'] === $row['idUsuario'] || $rol == '1'): ?>
                    <td>
                        <!-- Botón para eliminar reseña -->
                        <input type="hidden" name="id_resenia" value="<?php echo $id_resenia; ?>">
                        <button type="button" onclick="ConfirmarEliminacionRes(event, <?php echo $id_resenia; ?>)" style="background: none; border: none; padding: 0;">
                            <i class="fa fa-trash" aria-hidden="true" style="color: black; font-size: 0.9em; cursor: pointer;"></i>
                        </button>

                        <?php if ($_SESSION['usuario'] === $row['idUsuario']): // Solo permitir edición si es el propietario ?>
                        <!-- Botón para actualizar reseña -->
                        <button type="button" onclick="ActualizarResenia(event, <?php echo $id_resenia; ?>)" style="background: none; border: none; padding: 0;"
                            data-resenia="<?php echo $resenia; ?>">
                            <i class="fa fa-pencil" aria-hidden="true" style="color: black; font-size: 0.9em; cursor: pointer;"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php
                    $index++;
                    }
                } else {
                    echo '<tr><td colspan="3">No hay reseñas disponibles.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <script>
            
        </script>
