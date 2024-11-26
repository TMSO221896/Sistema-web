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
if ($rol != 3) {  // Cambia 2 por el número de rol que representa administrador
    header("Location: ../Vista/Iniciosesion.php");  // Página de acceso denegado
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link rel="stylesheet" href="../Static/css/MiperfilStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../Static/js/SweetAlert.js"></script>
    <script src="../Static/js/Miperfil.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .btn-default {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-default:hover {
            background-color: #0056b3;
        }
        .list-group-item i {
            margin-right: 8px;
        }
        .list-group-item {
            font-weight: bold;
        }
        .list-group-item.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            <i class="fas fa-cogs"></i> Account Settings
        </h4>
        <div class="text-right mt-3">
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
                        <a class="list-group-item list-group-item-action" data-toggle="tab" href="#account-change-password">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" href="#create-dir">
                            <i class="fas fa-map-marker-alt"></i> Crear Dirección
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" href="#direcciones">
                            <i class="fas fa-map-marked-alt"></i> Direcciones
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="tab" href="#drop-account">
                            <i class="fas fa-user-times"></i> Eliminar Cuenta
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
                            <form action="../Controlador/update_contraseñas.php" method="POST" class="update_contraseñas">
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
                                    <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="create-dir">
                            <form action="../Controlador/Create_Direccion.php" method="POST" id="create-dir-" class="create-dir">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Calle</label><br>
                                        <input type="text" name="calle" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            placeholder="Solo letras">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Colonia</label><br>
                                        <input type="text" name="colonia" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            placeholder="Solo letras">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Código Postal</label><br>
                                        <input type="text" name="cod_postal" maxlength="5" required 
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)" 
                                            placeholder="Máximo 5 números">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Número</label><br>
                                        <input type="text" name="numero" required 
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')"
                                            placeholder="Número positivo">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Estado</label><br>
                                        <input type="text" name="estado" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            placeholder="Solo letras">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Ciudad</label><br>
                                        <input type="text" name="ciudad" required 
                                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                                            placeholder="Solo letras">
                                    </div>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="validarDireccion(event)">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>


                        <!-- Pestaña Info -->
                        <div class="tab-pane fade" id="direcciones">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Calle</th>
                                            <th>Colonia</th>
                                            <th>Cod_postal</th>
                                            <th>#</th>
                                            <th>Estado</th>
                                            <th>Ciudad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody> <!-- Etiqueta tbody corregida -->
                                        <?php
                                            $sql = "SELECT * FROM direcciones where id_usuario = '$user';";
                                            $execute = mysqli_query($conexion, $sql);
                                            $index = 0;

                                            while ($rows = mysqli_fetch_array($execute)) {
                                                $id_direccion =$rows['id_direccion'];
                                                $calle = $rows['calle'];
                                                $colonia = $rows['colonia'];
                                                $cod_postal = $rows['cod_postal'];
                                                $numero = $rows['numero'];
                                                $estado = $rows['estado'];
                                                $ciudad = $rows['ciudad'];
                                        ?>
                                        <tr>
                                            <td> <?php echo $calle; ?> </td>
                                            <td> <?php echo $colonia; ?> </td>
                                            <td> <?php echo $cod_postal; ?> </td>
                                            <td> <?php echo $numero; ?> </td>
                                            <td> <?php echo $estado; ?> </td>
                                            <td> <?php echo $ciudad; ?> </td>
                                            <td>
                                                <input type="hidden" name="id_pastel" value="<?php echo $id_direccion; ?>">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="ActualizarDireccion(event, <?php echo $id_direccion; ?>)" 
                                                    data-calle="<?php echo $calle; ?>"
                                                    data-colonia="<?php echo $colonia; ?>"
                                                    data-cod_postal="<?php echo $cod_postal; ?>"
                                                    data-numero="<?php echo $numero; ?>"
                                                    data-estado="<?php echo $estado; ?>"
                                                    data-ciudad="<?php echo $ciudad; ?>">
                                                    Actualizar
                                                </button>
                                                <form method="POST" action="../Controlador/Drop_direccion.php" id="form-eliminar-<?php echo $index; ?>">
                                                    <input type="hidden" name="id_direccion" value="<?php echo $id_direccion; ?>">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacionDir(<?php echo $index; ?>)">Eliminar</button>
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

                        <div class="tab-pane fade" id="drop-account">
                            <form action="../Controlador/Drop_cuenta.php" method="POST" id="drop-account-" class="drop-account">
                                <div class="card-body pb-2">
                                    <label class="form-label">Eliminar cuenta</label>
                                    <br>
                                    <button type="button" class="btn btn-primary" onclick="CondirmarEliminacionCuenta(event)">Save changes</button>&nbsp;
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        async function ActualizarDireccion(event, id_direccion) {
    event.preventDefault();

    const button = event.target;
    const calle = button.getAttribute("data-calle");
    const colonia = button.getAttribute("data-colonia");
    const cod_postal = button.getAttribute("data-cod_postal");
    const numero = button.getAttribute("data-numero");
    const estado = button.getAttribute("data-estado");
    const ciudad = button.getAttribute("data-ciudad");

    const { value: formValues } = await Swal.fire({
        title: "Actualizar Dirección",
        html: `
            <input type="text" name="calle" id="swal-calle" class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${calle}" 
                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')"
                placeholder="Solo letras">
            
            <input type="text" name="colonia" id="swal-colonia" class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${colonia}" 
                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')"
                placeholder="Solo letras">
            
            <input type="text" name="cod_postal" id="swal-cod_postal" maxlength="4" 
                class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${cod_postal}" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)"
                placeholder="Máximo 4 números">
            
            <input type="text" name="numero" id="swal-numero" class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${numero}" 
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');"
                placeholder="Números mayores a 0">
            
            <input type="text" name="estado" id="swal-estado" class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${estado}" 
                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')"
                placeholder="Solo letras">
            
            <input type="text" name="ciudad" id="swal-ciudad" class="swal2-input" 
                style="width: 80%; padding: 8px; font-size: 20px;" 
                value="${ciudad}" 
                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]/g, '')"
                placeholder="Solo letras">
            
            <input type="hidden" name="id_direccion" value="${id_direccion}">
        `,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        preConfirm: () => {
            const calleValue = document.getElementById('swal-calle').value.trim();
            const coloniaValue = document.getElementById('swal-colonia').value.trim();
            const codPostalValue = document.getElementById('swal-cod_postal').value.trim();
            const numeroValue = document.getElementById('swal-numero').value.trim();
            const estadoValue = document.getElementById('swal-estado').value.trim();
            const ciudadValue = document.getElementById('swal-ciudad').value.trim();

            // Validación general de campos vacíos
            if (!calleValue || !coloniaValue || !codPostalValue || !numeroValue || !estadoValue || !ciudadValue) {
                Swal.showValidationMessage('Todos los campos son obligatorios');
                return false;
            }

            // Validación para "cod_postal"
            if (!/^\d{1,4}$/.test(codPostalValue)) {
                Swal.showValidationMessage('El código postal debe ser un número de hasta 4 dígitos');
                return false;
            }

            // Validación específica para "numero"
            if (isNaN(numeroValue) || parseFloat(numeroValue) <= 0) {
                Swal.showValidationMessage('El campo "número" debe ser un número mayor a 0 y válido.');
                return false;
            }

            return {
                calle: calleValue,
                colonia: coloniaValue,
                cod_postal: codPostalValue,
                numero: numeroValue,
                estado: estadoValue,
                ciudad: ciudadValue,
                id_direccion: id_direccion
            };
        }
    });

    if (formValues) {
        GuardarCambiosDireccion(formValues);
    }
}


    </script>
    <!-- Bibliotecas JS de jQuery y Bootstrap correctamente incluidas -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
