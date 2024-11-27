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
if ($rol != 1) {  // Cambia 2 por el número de rol que representa administrador
    header("Location: ../Vista/Iniciosesion.php");  // Página de acceso denegado
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['backup'])) {
    include 'respaldo.php'; // Llama al archivo de respaldo
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Administración - Pastelería</title>
        <link rel="stylesheet" href="../Static/css/MiperfilStyle.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../Static/js/SweetAlert.js"></script>
        <script src="../Static/js/Pedidos.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
        <style>
            body {
                background-color: #fff7f0;
            }
            .sidebar {
                background-color: #ffccd5;
                min-height: 100vh;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            }
            .sidebar .list-group-item {
                color: #7a294f;
                font-weight: bold;
            }
            .sidebar .list-group-item:hover {
                background-color: #f5a6b7;
                color: #fff;
            }
            .sidebar .list-group-item.active {
                background-color: #d980a4;
                color: #fff;
            }
            .header {
                background-color: #ffccd5;
                color: #7a294f;
            }
            .btn-default {
                background-color: #f76c6c;
                color: #fff;
                border: none;
            }
            .btn-default:hover {
                background-color: #ff4c4c;
            }
            .card {
                border: none;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            .card-header {
                background-color: #fce4ec;
                color: #7a294f;
                font-weight: bold;
            }
            .list-group-item i {
                margin-right: 8px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar py-4">
                    <div class="text-center mb-4">
                        <img src="../Static/img/clausLogo.png" alt="Logo Pastelería" class="img-fluid rounded-circle">
                    </div>
                    <div class="list-group list-group-flush">
                        <a class="list-group-item active" data-toggle="tab" href="#account-general">
                            <i class="fas fa-user-cog"></i> General
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='account-change-password' href="#account-change-password">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='account-emp' href="#account-emp">
                            <i class="fas fa-user-plus"></i> Añadir empleado
                        </a>
                        <a class="list-group-item" data-toggle="tab" href="#add-producto">
                            <i class="fas fa-plus-circle"></i> Añadir productos
                        </a>
                        <a class="list-group-item" data-toggle="tab" href="#add-receta">
                            <i class="fas fa-book-open"></i> Añadir recetas
                        </a>
                        <a class="list-group-item" data-toggle="tab" href="#read-pedidos">
                            <i class="fas fa-shopping-cart"></i> Pedidos
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='UPD-drop' href="#UPD-drop">
                            <i class="fas fa-edit"></i> Act-Elim. Productos
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='UPD-dropRec' href="#UPD-dropRec">
                            <i class="fas fa-pen-alt"></i> Act-Elim. Recetas
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='drop-emp' href="#drop-emp">
                            <i class="fas fa-user-minus"></i> Elim. Empleados
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='reportes' href="#reportes">
                            <i class="fas fa-chart-line"></i> Reportes
                        </a>
                        <a class="list-group-item" data-toggle="tab" name='respaldo' href="#respaldo">
                            <i class="fas fa-database"></i> Respaldo
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="header p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h3">Panel de Admin</h1>
                            <a href="../Vista/Index.php" class="btn btn-default">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <!-- Pestaña General -->
                            <div class="tab-pane fade show active" id="account-general">
                            <div class="alert alert-info" role="alert">
                                <strong>Aviso:</strong>Sección de actualización de credenciales.
                            </div>
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
                            <div class="alert alert-info" role="alert">
                                <strong>Aviso:</strong> Sección de actualización de contraseñas.
                            </div>
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

                            <!-- Pestaña Empleado -->
                            <div class="tab-pane fade" id="account-emp">
                            <div class="alert alert-info" role="alert">
                                <strong>Aviso:</strong> Sección de Ingreso de gerentes
                            </div>
                                <form action="../Controlador/Create_Empleado.php" method="POST" id="create_Empleado-"class="Create_empleado">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Usuario Empleado</label><br>
                                            <input type="text" placeholder="Usuario" name="usuarioEmp" 
                                                value="" required 
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" 
                                                maxlength="20" 
                                                pattern="^[^@]+$" 
                                                title="El usuario no puede contener el carácter '@' y solo puede incluir letras y números.">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nombre Empleado</label><br>
                                            <input type="text" placeholder="Nombre" name="nombreEmp" 
                                                value="" required 
                                                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                                maxlength="30" 
                                                title="El nombre solo puede contener letras.">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Apellido Empleado</label><br>
                                            <input type="text" placeholder="Apellido" name="apellidoEmp" 
                                                value="" required 
                                                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                                maxlength="30" 
                                                title="El apellido solo puede contener letras.">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Correo Empleado</label><br>
                                            <input type="email" placeholder="Correo" name="correoEmp" 
                                                value="" required 
                                                maxlength="50" 
                                                title="Por favor, ingresa un correo válido.">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Contraseña</label></br>
                                            <input type="password" placeholder="Contraseña" name="contraseñaEmp" required>
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary" onclick="validarEmpleado(event)">Save changes</button>&nbsp;
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="add-producto">
                                <div class="alert alert-info" role="alert">
                                    <strong>Aviso:</strong> En la sección de creación de productos, es indispensable que el ID del producto sea único. 
                                    Además, el nombre del producto debe ser único y coincidir exactamente con el nombre del archivo de la imagen adjunta 
                                    (sin incluir la extensión). La imagen debe estar en formato PNG, pesar menos de 2MB, y no debe existir previamente 
                                    en el sistema. Asegúrese de que el precio ingresado sea mayor a cero y que todos los datos sean válidos para 
                                    evitar errores en el registro.
                                </div>
                                <form action="../Controlador/Create_Producto.php" method="POST" class="Create_producto" id='create_producto-' enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">ID Producto</label><br>
                                            <input type="number" placeholder="ID Producto" name="idproducto" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nombre Producto</label><br>
                                            <input type="text" placeholder="Nombre" name="nombreproducto"
                                                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                                maxlength="20" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Descripción</label><br>
                                            <textarea name="descripcion" rows="4" cols="50" 
                                                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                                maxlength="30" placeholder="Escribe una descripción..." value=""></textarea><br><br>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Precio</label><br>
                                            <input type="number" placeholder="Precio" name="precio" value="" required>
                                        </div><br>
                                        <div class="form-group">
                                            <label class="form-label">Categoría</label><br>
                                            <select name="categoria" class="form-control" required>
                                                <option value="" disabled selected>Selecciona una categoría</option>
                                                <option value="Galletas">Galletas</option>
                                                <option value="Pasteles">Pasteles</option>
                                                <option value="Especiales">Especiales</option>
                                            </select>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="imagen">Campo de imagen:</label><br>
                                            <input type="file" id="imagen" name="imagen">
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary" onclick="validarProducto(event)">Save changes</button>&nbsp;
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="add-receta">
                            <div class="alert alert-info" role="alert">
                                <strong>Aviso:</strong> Aviso: Para registrar una receta, es indispensable cumplir con los siguientes requisitos: 
                                El ID de la receta debe ser único, el nombre de la receta debe ser único e igual al nombre del archivo de imagen (sin la extensión), 
                                y solo se permiten imágenes en formato PNG con un tamaño máximo de 2MB. Además, esta funcionalidad está restringida a usuarios con los roles permitidos.
                            </div>
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
                                <div class="alert alert-info" role="alert">
                                    <strong>Aviso:</strong> Sección de vista de pedidos, en esta sección se podrá actualizar el
                                    estatus del pedido, visualizar y eliminar el mismo.Si el pedido es entregado o cancelado este podra ser eliminado del sistema
                                </div>
                                <div class="mb-3">
                                    <label for="filter-status" class="form-label">Buscar por Estatus:</label>
                                    <input type="text" id="filter-status" class="form-control" placeholder="Escribe un estatus para filtrar...">
                                </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover " id="pedidos-table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha de Registro</th>
                                                    <th>Total</th>
                                                    <th>Estatus</th>
                                                    <th>Act.Estatus</th>
                                                    <th>Artículos</th>
                                                    <th>Acción</th>
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
                                                    <td class="estatus"> <?php echo $Estatus; ?> </td>
                                                    <td>
                                                        <form action="../Controlador/update_estatus.php" method="POST">
                                                            <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
                                                            <select name="status" id="lang" <?php echo ($Estatus == 'Cancelado' || $Estatus == 'Entregado') ? 'disabled' : ''; ?>>
                                                                <option value="Registrado" <?php echo ($Estatus == 'Registrado') ? 'selected' : ''; ?>>Registrado</option>
                                                                <option value="Cocinando" <?php echo ($Estatus == 'Cocinando') ? 'selected' : ''; ?>>Cocinando</option>
                                                                <option value="Listo" <?php echo ($Estatus == 'Listo') ? 'selected' : ''; ?>>Listo</option>
                                                                <option value="Entregado" <?php echo ($Estatus == 'Entregado') ? 'selected' : ''; ?>>Entregado</option>
                                                                <option value="Cancelado" <?php echo ($Estatus == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                                                            </select>
                                                            <input type="submit" value="Enviar" <?php echo ($Estatus == 'Cancelado' || $Estatus == 'Entregado') ? 'disabled' : ''; ?> />
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <!-- Solo opción para actualizar los primeros 3 pasteles -->
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="VisualizarProductos(event, <?php echo $id_pedido; ?>)" >
                                                            Articulos
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <?php if ($Estatus === 'Cancelado' || $Estatus === 'Entregado'): ?>
                                                            <form method="POST" action="../Controlador/Drop_pedido.php" id="form-eliminarPed-<?php echo $index; ?>">
                                                                <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacionPed(event,<?php echo $index; ?>)">Eliminar</button>
                                                            </form>
                                                        <?php endif; ?>
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
                                <div class="alert alert-info" role="alert">
                                    <strong>Aviso:</strong> Aquí puedes actualizar la visualización de los productos, eliminarlos o modificar sus detalles.
                                </div>
                                <div class="mb-3">
                                    <label for="filter-categoria" class="form-label">Buscar por Categoria:</label>
                                    <input type="text" id="filter-categoria" class="form-control" placeholder="Escribe una categoría para filtrar...">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="productos-table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID producto</th>
                                                <th>Nombre Producto</th>
                                                <th>Descripción</th>
                                                <th>Precio</th>
                                                <th>Categoría</th>
                                                <th>Visualización</th>
                                                <th>Imagen</th>
                                                <th>Actualización</th>
                                                <th>Eliminación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql ="SELECT * FROM pasteles;";
                                            $execute = mysqli_query($conexion, $sql);
                                            $index = 0;

                                            while ($rows = mysqli_fetch_array($execute)) {
                                                $id_pastel = $rows['id_pastel'];
                                                $nombreP = $rows['nombreP'];
                                                $descripcion = $rows['descripcion'];
                                                $precio = $rows['precio'];
                                                $categoria = $rows['categoria'];
                                                $view = $rows['visualizacion'];
                                            ?>
                                            <tr>
                                                <td> <?php echo $id_pastel; ?> </td>
                                                <td> <?php echo $nombreP; ?> </td>
                                                <td> <?php echo $descripcion; ?> </td>
                                                <td> $<?php echo $precio; ?> </td>
                                                <td class="categoria"> <?php echo $categoria; ?> </td>
                                                <td>
                                                    <form action="../Controlador/update_visualizacion.php" method="POST">
                                                        <label for="lang"><?php echo $view; ?></label>
                                                        <input type="hidden" name="id_pastel" value="<?php echo $id_pastel; ?>">
                                                        <select name="status" id="lang">
                                                            <option value="visualizar">Visualizar</option>
                                                            <option value="ocultar">Ocultar</option>
                                                        </select>
                                                        <input type="submit" value="Enviar" />
                                                    </form>
                                                </td>
                                                <td>
                                                    <form method="POST" action="../Controlador/update_imagen.php" id="updateImg-<?php echo $id_pastel; ?>" enctype="multipart/form-data">
                                                        <input type="file" id="imagen" name="imagen" class="form-control" accept=".png" required>
                                                        <input type="hidden" name="id_pastel" value="<?php echo $id_pastel; ?>">
                                                        <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <?php if ($id_pastel <= 3): ?>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarProductos(event, <?php echo $id_pastel; ?>)" 
                                                                data-nombre="<?php echo $nombreP; ?>"
                                                                data-descripcion="<?php echo $descripcion; ?>"
                                                                data-precio="<?php echo $precio; ?>">
                                                            Actualizar
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarProductos(event, <?php echo $id_pastel; ?>)" 
                                                                data-nombre="<?php echo $nombreP; ?>"
                                                                data-descripcion="<?php echo $descripcion; ?>"
                                                                data-precio="<?php echo $precio; ?>">
                                                            Actualizar
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form method="POST" action="../Controlador/Drop_pastel.php" id="form-eliminar-<?php echo $id_pastel; ?>">
                                                        <input type="hidden" name="id_pastel" value="<?php echo $id_pastel; ?>">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion(event, <?php echo $id_pastel; ?>)">Eliminar</button>
                                                    </form>
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

                            <div class="tab-pane fade" id="UPD-dropRec">
                                <div class="mb-3">
                                    <label for="filter-description" class="form-label">Buscar por Descripción:</label>
                                    <input type="text" id="filter-description" class="form-control" placeholder="Escribe parte de la descripción para filtrar...">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="recetas-table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID Receta</th>
                                                <th>Nombre Receta</th>
                                                <th>Descripción</th>
                                                <th>Actualización</th>
                                                <th>Eliminación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                <td><?php echo $id_Receta; ?></td>
                                                <td><?php echo $nombreR; ?></td>
                                                <td class="descripcion"><?php echo $descripcion; ?></td>
                                                <td>
                                                    <input type="hidden" name="id_receta" value="<?php echo $id_Receta; ?>">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarRecetas(event, <?php echo $id_Receta; ?>)" 
                                                        data-nombre="<?php echo $nombreR; ?>"
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="drop-emp">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>usuario</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>correo</th>
                                                <th>Eliminación</th>
                                            </tr>
                                        </thead>
                                        <body>
                                            <?php
                                                $sql = "SELECT * FROM usuarios where rol = 2;";
                                                $execute = mysqli_query($conexion, $sql);
                                                $index = 0;

                                                while ($rows = mysqli_fetch_array($execute)) {
                                                    $id_usuario = $rows['usuario'];
                                                    $nombreU = $rows['nombre'];
                                                    $apellidoU = $rows['apellido'];
                                                    $correo = $rows['correo'];
                                            ?>
                                            <tr>
                                                <td> <?php echo $id_usuario; ?> </td>
                                                <td> <?php echo $nombreU; ?> </td>
                                                <td> <?php echo $apellidoU; ?> </td>
                                                <td> <?php echo $correo; ?> </td>
                                                <td>
                                                    <form method="POST" action="../Controlador/Drop_emp.php" id="form-eliminaremp-<?php echo $index; ?>">
                                                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacionEmp(<?php echo $index; ?>)">Eliminar</button>
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

                            <div class="tab-pane fade" id="reportes">
                                <!-- Reporte de Pedidos -->
                                <form action="../Static/pdf/generar_reporte.php" method="POST" class="Reporte_Pedidos" id="Reporte_Pedidos">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Reporte de Pedidos</label><br>
                                        <label for="fecha_inicio_pedidos" class="form-label">Fecha de inicio</label>
                                        <input type="date" id="fecha_inicio_pedidos" name="fecha_inicio" class="form-control" require>
                                        <label for="fecha_fin_pedidos" class="form-label">Fecha de fin</label>
                                        <input type="date" id="fecha_fin_pedidos" name="fecha_fin" class="form-control" require><br>
                                        <button type="submit" name="tipo_reporte" value="pedidos" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>

                                <!-- Usuarios Nuevos -->
                                <form action="../Static/pdf/generar_reporte.php" method="POST" class="Reporte_Usuarios" id="Reporte_Usuarios">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Usuarios Nuevos/Eliminados</label><br>
                                        <label for="fecha_inicio_usuarios" class="form-label">Fecha de inicio</label>
                                        <input type="date" id="fecha_inicio_usuarios" name="fecha_inicio" class="form-control">
                                        <label for="fecha_fin_usuarios" class="form-label">Fecha de fin</label>
                                        <input type="date" id="fecha_fin_usuarios" name="fecha_fin" class="form-control"><br>
                                        <button type="submit" name="tipo_reporte" value="usuarios_nuevos_eliminados" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>

                                <!-- Recetas -->
                                <form action="../Static/pdf/generar_reporte.php" method="POST" class="Reporte_recetas" id="Reporte_recetas">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Recetas</label><br>
                                        <label for="fecha_inicio_recetas" class="form-label">Fecha de inicio</label>
                                        <input type="date" id="fecha_inicio_recetas" name="fecha_inicio" class="form-control">
                                        <label for="fecha_fin_recetas" class="form-label">Fecha de fin</label>
                                        <input type="date" id="fecha_fin_recetas" name="fecha_fin" class="form-control"><br>
                                        <button type="submit" name="tipo_reporte" value="recetas" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>

                                <!-- Producto Más Vendido -->
                                <form action="../Static/pdf/generar_reporte.php" method="POST" class="Producto_Mas_Vendido" id="Producto_Mas_Vendido">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Producto Más Vendido</label><br>
                                        <label for="fecha_inicio_producto_mas_vendido" class="form-label">Fecha de inicio</label>
                                        <input type="date" id="fecha_inicio_producto_mas_vendido" name="fecha_inicio" class="form-control">
                                        <label for="fecha_fin_producto_mas_vendido" class="form-label">Fecha de fin</label>
                                        <input type="date" id="fecha_fin_producto_mas_vendido" name="fecha_fin" class="form-control"><br>
                                        <button type="submit" name="tipo_reporte" value="producto_mas_vendido" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>

                                <!-- Producto Menos Vendido -->
                                <form action="../Static/pdf/generar_reporte.php" method="POST" class="Producto_Menos_Vendido" id="Producto_Menos_Vendido">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Producto Menos Vendido</label><br>
                                        <label for="fecha_inicio_producto_menos_vendido" class="form-label">Fecha de inicio</label>
                                        <input type="date" id="fecha_inicio_producto_menos_vendido" name="fecha_inicio" class="form-control">
                                        <label for="fecha_fin_producto_menos_vendido" class="form-label">Fecha de fin</label>
                                        <input type="date" id="fecha_fin_producto_menos_vendido" name="fecha_fin" class="form-control"><br>
                                        <button type="submit" name="tipo_reporte" value="producto_menos_vendido" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>
                            </div>


                            <div class="tab-pane fade" id="respaldo">
                                <form action="../Controlador/respaldo.php" method="POST" class="Respaldo_base" id="Respaldo_base">
                                    <div class="text-left mt-3">
                                        <label class="form-label">Respaldo</label><br>
                                        <button type="submit" name="respaldo" class="btn btn-primary">Crear</button>
                                    </div>
                                </form>
                                <br>
                                <div class="alert alert-info" role="alert">
                                    <strong>Aviso:</strong> Selecciona el respaldo de la base de datos para restaurar.
                                </div>
                                <form action="../Controlador/restore_all.php" method="post" enctype="multipart/form-data">
                                    <a>Restaurar Base de Datos</a>
                                    <input type="file" name="backup_db_file" class="form-control" accept=".sql" required>
                                    <br><br>
                                    <button type="submit" name="respaldo" class="btn btn-primary">Restaurar</button>
                                </form>
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
        </script>
        <script>         
            document.getElementById('filter-status').addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#pedidos-table tbody tr');
                rows.forEach(row => {
                const status = row.querySelector('.estatus').textContent.toLowerCase();
                if (status.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                                        }
                });
            });

            document.getElementById('filter-description').addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#recetas-table tbody tr');

                rows.forEach(row => {
                    const description = row.querySelector('.descripcion').textContent.toLowerCase();
                    if (description.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('filter-categoria').addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#productos-table tbody tr');

                rows.forEach(row => {
                    const categoria = row.querySelector('.categoria').textContent.toLowerCase();
                    if (categoria.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
