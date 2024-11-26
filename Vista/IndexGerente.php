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

// Prepara la consulta
$consulta = "SELECT usuario, nombre, apellido, correo, rol FROM usuarios WHERE usuario = '$user'";
$resultado = mysqli_query($conexion, $consulta);
$rows = mysqli_fetch_array($resultado);

$rol = $rows['rol'];
if ($rol != 2) {  // Cambia 2 por el número de rol que representa administrador
    header("Location: ../Vista/Iniciosesion.php");  // Página de acceso denegado
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Gerente | Sweet Delights</title>
    <link rel="stylesheet" href="../Static/css/MiperfilStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../Static/js/SweetAlert.js"></script>
    <script src="../Static/js/Pedidos.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        h4 {
            color: #f16cb6;
        }

        .btn-default {
            background-color: #f16cb6;
            color: white;
            border: none;
            font-weight: bold;
        }

        .btn-default:hover {
            background-color: #f16cb6;
        }

        .list-group-item {
            font-weight: bold;
            color: #5a3e2b;
            background-color: #fff;
        }

        .list-group-item i {
            margin-right: 8px;
            color: #5a3e2b;
        }

        .list-group-item.active {
            background-color: #f16cb6;
            color: white;
            border-color: #ca6f1e;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: #fffdf8;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h4 class="font-weight-bold text-center mb-4">
            <i class="fas fa-user-cog"></i> Panel de Configuración - Gerente
        </h4>
        <div class="text-right mb-3">
            <a href="../Vista/Index.php" class="btn btn-default">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="tab" href="#account-general">
                            <i class="fas fa-user-cog"></i> General
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" name="account-change-password" href="#account-change-password">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" href="#add-receta">
                            <i class="fas fa-plus-circle"></i> Añadir Recetas
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" name="read-pedidos" href="#read-pedidos">
                            <i class="fas fa-shopping-basket"></i> Pedidos
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" name="UPD-drop" href="#UPD-drop">
                            <i class="fas fa-box-open"></i> Actualizar Productos
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" name="UPD-dropRec" href="#UPD-dropRec">
                            <i class="fas fa-edit"></i> Actualizar/Eliminar Recetas
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Pestaña General -->
                        <div class="tab-pane fade show active" id="account-general">
                            <form action="../Controlador/update_credenciales.php" method="POST" id="update_credenciales-" class="Actualizacion_credenciales">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Usuario</label><br>
                                        <input type="text" placeholder="Usuario" name="usuario" 
                                            value="<?php echo $rows['usuario']; ?>" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" 
                                            maxlength="20" 
                                            pattern="^[^@]+$" 
                                            title="El usuario no puede contener el carácter '@' y solo puede incluir letras y números.">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label><br>
                                        <input type="text" placeholder="Nombre" name="nombre" 
                                            value="<?php echo $rows['nombre']; ?>" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            maxlength="30" 
                                            title="El nombre solo puede contener letras.">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Apellido</label><br>
                                        <input type="text" placeholder="Apellido" name="apellido" 
                                            value="<?php echo $rows['apellido']; ?>" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            maxlength="30" 
                                            title="El apellido solo puede contener letras.">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Correo</label><br>
                                        <input type="email" placeholder="Correo" name="correo" 
                                            value="<?php echo $rows['correo']; ?>" required 
                                            maxlength="50" 
                                            title="Por favor, ingresa un correo válido.">
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="validarCredenciales(event)">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>

                        <!-- Pestaña Cambiar Contraseña -->
                        <div class="tab-pane fade" id="account-change-password">
                            <form action="../Controlador/update_contraseñas.php" method="POST" id="update_contraseñas-"class="Actualizacion_contraseñas">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Contraseña Anterior</label>
                                        <input type="password" placeholder="Contraseña anterior" name="Acontraseña" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nueva contraseña</label>
                                        <input type="password" placeholder="Nueva contraseña" name="Ncontraseña" required>
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="validarContrasenas(event)">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="add-receta">
                            <form action="../Controlador/Create_Receta.php" method="POST" class="Create_receta" id='create_receta-'enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">ID Receta</label><br>
                                        <input type="number" placeholder="ID Receta" name="idreceta" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nombre Receta</label><br>
                                        <input type="text" placeholder="Nombre" name="nombrereceta"
                                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                        maxlength="20"  value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Descripcion </label><br>
                                        <textarea name="descripcionR" rows="4" cols="50" placeholder="Escribe una descripción..." value=""></textarea><br><br>
                                        </div>
                                    <div class="form-group">
                                        <label for="imagen">Campo de imagen: </label><br>
                                        <input type="file" id="imagen" name="imagen">
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary"onclick="validarReceta(event)">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>

                        <!-- Pestaña Pedidos -->
                        <div class="tab-pane fade" id="read-pedidos">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID pedido</th>
                                            <th>Cliente</th>
                                            <th>Fecha de Registro</th>
                                            <th>Total</th>
                                            <th>Estatus</th>
                                            <th>Artículos</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!-- Etiqueta tbody corregida -->
                                        <?php
                                            $sql = "SELECT * FROM pedidos;";
                                            $execute = mysqli_query($conexion, $sql);
                                            $index = 0;

                                            while ($rows = mysqli_fetch_array($execute)) {
                                                $id_pedido = $rows['id'];
                                                $cliente = $rows['user_id'];
                                                $fec_Reg = $rows['fecha_actual'];
                                                $Total = $rows['Total'];
                                                $Estatus = $rows['estatus'];
                                                // Verifica si estas variables están definidas en el contexto adecuado
                                                $id_pastel = isset($rows['id_pastel']) ? $rows['id_pastel'] : null; 
                                                $nombreP = isset($rows['nombreP']) ? $rows['nombreP'] : null;
                                                $descripcion = isset($rows['descripcion']) ? $rows['descripcion'] : null;
                                                $precio = isset($rows['precio']) ? $rows['precio'] : null;
                                        ?>
                                        <tr>
                                            <td> <?php echo $id_pedido; ?> </td>
                                            <td> <?php echo $cliente; ?> </td>
                                            <td> <?php echo $fec_Reg; ?> </td>
                                            <td> $<?php echo $Total; ?> </td>
                                            <td><?php echo $Estatus; ?></td>
                                            <td>
                                                <!-- Solo opción para actualizar los primeros 3 pasteles -->
                                                <button type="button" class="btn btn-primary btn-sm" onclick="VisualizarProductos(event, <?php echo $id_pedido; ?>)" >
                                                    Articulos
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                            $index++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="UPD-drop">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID producto</th>
                                            <th>Nombre Producto</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Actualización</th>
                                        </tr>
                                    </thead>
                                    <body>
                                        <?php
                                            $sql = "SELECT * FROM pasteles;";
                                            $execute = mysqli_query($conexion, $sql);
                                            $index = 0;

                                            while ($rows = mysqli_fetch_array($execute)) {
                                                $id_pastel = $rows['id_pastel'];
                                                $nombreP = $rows['nombreP'];
                                                $descripcion = $rows['descripcion'];
                                                $precio = $rows['precio'];
                                        ?>
                                        <tr>
                                            <td> <?php echo $id_pastel; ?> </td>
                                            <td> <?php echo $nombreP; ?> </td>
                                            <td> <?php echo $descripcion; ?> </td>
                                            <td> $<?php echo $precio; ?> </td>
                                            <td>
                                                    <!-- Opción solo para actualizar los primeros 3 pasteles -->
                                                        <input type="hidden" name="id_pastel" value="<?php echo $id_pastel; ?>">
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarProductos(event, <?php echo $id_pastel; ?>)" 
                                                                data-nombre="<?php echo $nombreP; ?>"
                                                                data-descripcion="<?php echo $descripcion; ?>"
                                                                data-precio="<?php echo $precio; ?>">
                                                            Actualizar
                                                        </button>
                                                </td>
                                        </tr>
                                        <?php
                                            $index++;
                                            }
                                        ?>
                                    </body>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="UPD-dropRec">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID Receta</th>
                                            <th>Nombre Receta</th>
                                            <th>Descripción</th>
                                            <th>Actualización</th>
                                        </tr>
                                    </thead>
                                    <body>
                                        <?php
                                            $sql = "SELECT * FROM recetas;";
                                            $execute = mysqli_query($conexion, $sql);
                                            $index = 0;

                                            while ($rows = mysqli_fetch_array($execute)) {
                                                $id_Receta = $rows['id'];
                                                $nombreR = $rows['nombre'];
                                                $descripcion = $rows['receta'];
                                        ?>
                                        <tr>
                                            <td> <?php echo $id_Receta; ?> </td>
                                            <td> <?php echo $nombreR; ?> </td>
                                            <td> <?php echo $descripcion; ?> </td>
                                            <td>
                                                <input type="hidden" name="id_receta" value="<?php echo $id_Receta; ?>">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarRecetas(event, <?php echo $id_Receta; ?>)" 
                                                    data-nombre="<?php echo $nombre; ?>"
                                                    data-receta="<?php echo $descripcion; ?>">
                                                    Actualizar
                                                </button>
                                            </td>
                                                <td>
                                                <form method="POST" action="../Controlador/Drop_receta.php" id="form-eliminarRec-<?php echo $index; ?>">
                                                    <input type="hidden" name="id_receta" value="<?php echo $id_Receta; ?>">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacionRec(<?php echo $index; ?>)">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                            $index++;
                                            }
                                        ?>
                                    </body>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- SweetAlert Ingresar Productos-->
    <?php
    if (isset($_SESSION['msj'])) {
        $respuesta = $_SESSION['msj']; ?>
        <script>
            mostrarAlerta("<?php echo $respuesta; ?>");
        </script>
    <?php
        unset($_SESSION['msj']);
    }
    ?>
    
    <?php
    if (isset($_SESSION['msj2']) && isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        $respuesta = $_SESSION['msj2']; ?>
        <script>
            mostrarAlerta("<?php echo $respuesta; ?>", "<?php echo $alert; ?>");
        </script>
    <?php
        unset($_SESSION['msj2']);
        unset($_SESSION['alert']);
    }
    ?>

<?php
    if (isset($_SESSION['usuario']) && isset($_SESSION['login_success'])) { ?>
        <script>
            mostrarLogueoSuccess();
        </script>
    <?php
        unset($_SESSION['login_success']);
    }
    ?>
    
    <script>
            async function ActualizarProductos(event, id_pastel) {
                event.preventDefault();

                const button = event.target;
                const descripcion = button.getAttribute("data-descripcion");
                const precio = button.getAttribute("data-precio");

                const { value: formValues } = await Swal.fire({
                    title: "Actualizar Producto",
                    html: `
                        <textarea name="descripcion" id="swal-descripcion" class="swal2-input" rows="4" style="width: 100%;">${descripcion}</textarea>
                        <input type="number" name="precio" id="swal-precio" class="swal2-input" min="0" step="0.01" style="width: 100%; padding: 8px; font-size: 20px;" value="${precio}">
                        <input type="hidden" name="id_pastel" value="${id_pastel}">
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Actualizar",
                    preConfirm: () => {
                        const descripcionValue = document.getElementById('swal-descripcion').value.trim();
                        const precioValue = document.getElementById('swal-precio').value.trim();

                        // Validación para asegurarse de que los campos no estén vacíos
                        if (!descripcionValue) {
                            Swal.showValidationMessage('La descripción no puede estar vacía');
                            return false; // Evita el envío si la validación falla
                        }
                        if (!precioValue || isNaN(precioValue) || Number(precioValue) <= 0) {
                            Swal.showValidationMessage('El precio debe ser un número válido mayor que 0');
                            return false; // Evita el envío si la validación falla
                        }

                        return {
                            descripcion: descripcionValue,
                            precio: precioValue,
                            id_pastel: id_pastel
                        };
                    }
                });

                if (formValues) {
                    GuardarCambiosPastel(formValues);
                }
            }


        async function ActualizarRecetas(event, id_receta) {
            event.preventDefault();

            const button = event.target;
            const receta = button.getAttribute("data-receta");  // Obtenemos la receta actual

            const { value: formValues } = await Swal.fire({
                title: "Actualizar Receta",
                html: `
                    <textarea name="receta" id="swal-receta" class="swal2-input" rows="6" style="width: 100%;">${receta}</textarea>
                    <input type="hidden" name="id_receta" value="${id_receta}">
                `,
                showCancelButton: true,
                confirmButtonText: "Actualizar",
                preConfirm: () => {
                    // Capturamos y validamos el valor de la receta editada
                    const recetaValue = document.getElementById('swal-receta').value.trim();

                    if (!recetaValue) {
                        Swal.showValidationMessage('La receta no puede estar vacía');
                        return false; // Evita el envío si la validación falla
                    }

                    return {
                        receta: recetaValue,
                        id_receta: id_receta
                    };
                }
            });

            if (formValues) {
                // Pasamos los valores capturados a la función de confirmación
                GuardarCambiosReceta(formValues);
            }
        }


        async function VisualizarProductos(event, id_pedido) {
            event.preventDefault();

            // Realiza una solicitud AJAX para obtener los datos del pedido
            const response = await fetch("../Controlador/read_articulos.php?id_pedido=" + id_pedido);
            const tableHTML = await response.text();

            // Muestra el resultado en SweetAlert
            await Swal.fire({
                title: "Artículos del Pedido",
                html: tableHTML,
                showCancelButton: false,
            });
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
