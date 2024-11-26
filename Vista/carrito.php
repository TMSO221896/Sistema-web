<?php require '../Static/head.php'; ?>
<link rel="stylesheet" href="../Static/css/CarritoStyle.css" />
<script src="../Static/js/carrito.js"></script>
<body>
    <!-- Header intacto -->
	<header>
			<div class="container-hero">
				<div class="container hero">
					<div class="customer-support">
						<i class="fa-solid fa-headset"></i>
						<div class="content-customer-support">
							<span class="text">Soporte al cliente</span>
							<span class="number">123-456-7890</span>
						</div>
					</div>

					<div class="container-logo">
              			<a href="../Vista/Index.php"><img src="../Static/img/clausLogo.png" alt="Claus Logo" /></a>
						  <h1 class="logo"></h1>
					</div>

					<div class="container-user">
                        <div class="dropdown">
                            <a class="fa-solid fa-user" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                            <!-- Menú desplegable -->
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['usuario']) && $rol == 3): ?>
                                    <li><a class="dropdown-item" href="../Controlador/Logout.php">Cerrar sesión</a></li>
                                    <li><a class="dropdown-item" href="../Vista/Miperfil.php">Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="../Vista/pedidos.php">Mis Pedidos</a></li>
                                <?php elseif (isset($_SESSION['usuario']) && $rol == 2): ?>
                                    <li><a class="dropdown-item" href="../Controlador/Logout.php">Cerrar sesión</a></li>
                                    <li><a class="dropdown-item" href="../Vista/IndexGerente.php">Configuración</a></li>
                                <?php elseif (isset($_SESSION['usuario']) && $rol == 1): ?>
                                    <li><a class="dropdown-item" href="../Controlador/Logout.php">Cerrar sesión</a></li>
                                    <li><a class="dropdown-item" href="../Vista/IndexAdmin.php">Configuración</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="../Vista/Iniciosesion.php#IniciarSesion">Iniciar sesión</a></li>
                                    <li><a class="dropdown-item" href="../Vista/Iniciosesion.php#Registro">Registrarse</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

						<span class="welcome-message">
							<?php if (isset($_SESSION['usuario'])): ?>
								Bienvenid@, <br><?php echo htmlspecialchars($_SESSION['usuario']); ?>
							<?php else: ?>
								Bienvenid@
							<?php endif; ?>
						</span>

						<a class="fa-solid fa-basket-shopping" href="../Vista/carrito.php" role="button"></a>
						<div class="content-shopping-cart">		
						</div>
                        <span class="text">Artículos</span>
                        <span class="number">(<?php echo $numero_articulos; ?>)</span>
					</div>
				</div>
			</div>

			<div class="container-navbar">
				<nav class="navbar container">
					<button class="navbar-toggler" onclick="toggleMenu()">
						<i class="fa-solid fa-bars"></i>
					</button>
					<ul class="menu">
                        <li><a href="../Vista/Index.php#pasteles">Pasteles</a></li>
						<li><a href="../Vista/Index.php#recetas">Recetas</a></li>
						<li><a href="../Vista/Index.php#quienessomos">¿Quienes Somos?</a></li>
					</ul>
				</nav>
			</div>
		</header>
    </header>

    <section class="h-100 gradient-custom">
        <div class="container py-5">
            <div class="row d-flex justify-content-center my-4">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Carrito - <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?> items</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $total = 0;
                            if (!empty($_SESSION['carrito'])) {
                                foreach ($_SESSION['carrito'] as $index => $pedido) {
                                    $precio = floatval($pedido['precio']);
                                    $total += $precio;
                                    $imagen = "../Static/img/Productos/" . $pedido['nombreP'] . ".png";
                                    if (!file_exists($imagen)) {
                                        $imagen = "../Static/img/nophoto.png";
                                    }
                                    ?>
                                    <!-- Single item -->
                                    <div class="row mb-4">
                                        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                            <!-- Image -->
                                            <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                                                <img src="<?php echo $imagen; ?>" class="w-100" alt="Producto">
                                                <a href="#!">
                                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                            <!-- Data -->
                                            <p><strong><?php echo htmlspecialchars($pedido['nombreP']); ?></strong></p>
                                            <p>Color: <?php echo htmlspecialchars($pedido['color']); ?></p>
                                            <p>Texto: <?php echo htmlspecialchars($pedido['texto']); ?></p>
                                            <p>Fecha de Entrega: <?php echo htmlspecialchars($pedido['fecha_entrega']); ?></p>
                                            <form method="POST" action="../Controlador/Drop_carrito.php" id="form-eliminar-<?php echo $index; ?>">
                                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                                <input type="hidden" name="eliminar_producto" value="1">
                                                <button type="button" onclick="confirmarEliminacion(event, <?php echo $index; ?>)" class="btn btn-danger btn-sm mb-2" title="Eliminar producto">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                            <!-- Quantity -->
                                            <div class="d-flex mb-4" style="max-width: 300px">
                                                <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control text-center" disabled />
                                            </div>
                                            <!-- Price -->
                                            <p class="text-start text-md-center">
                                                <strong>$<?php echo number_format($precio, 2); ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-4" />
                                    <?php
                                }
                            } else {
                                echo "<p class='text-center'>El carrito está vacío.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Resumen</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    Productos
                                    <span>$<?php echo number_format($total, 2); ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Envío
                                    <span>Gratis</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                    <div>
                                        <strong>Total</strong>
                                        <strong>
                                            <p class="mb-0">(Incluye IVA)</p>
                                        </strong>
                                    </div>
                                    <span><strong>$<?php echo number_format($total, 2); ?></strong></span>
                                </li>
                            </ul>
                            <?php if (!empty($_SESSION['carrito'])) { ?>
                                <form method="POST" action="../Controlador/Add_pedido.php">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="realizar_pedido">Realizar Pedido</button>
                                </form>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-lg btn-block" disabled>No hay productos</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="slider" reverse="true" style="
        --width: 300px;
        --height: 169px;
        --quantity: 10;
    ">
        <div class="list">
            <div class="item" style="--position: 1"><img src="../Static/img/Pgallery1.png" alt=""></div>
            <div class="item" style="--position: 2"><img src="../Static/img/Pgallery2.png" alt=""></div>
            <div class="item" style="--position: 3"><img src="../Static/img/Pgallery3.png" alt=""></div>
            <div class="item" style="--position: 4"><img src="../Static/img/Pgallery4.png" alt=""></div>
            <div class="item" style="--position: 5"><img src="../Static/img/Pgallery5.png" alt=""></div>
            <div class="item" style="--position: 6"><img src="../Static/img/Pgallery1.png" alt=""></div>
            <div class="item" style="--position: 7"><img src="../Static/img/Pgallery2.png" alt=""></div>
            <div class="item" style="--position: 8"><img src="../Static/img/Pgallery3.png" alt=""></div>
            <div class="item" style="--position: 9"><img src="../Static/img/Pgallery4.png" alt=""></div>
            <div class="item" style="--position: 10"><img src="../Static/img/Pgallery5.png" alt=""></div>
        </div>
    </div>

    <section class="produ" id="produ">
        <div class="heading">
            <a name="recetas"></a>
            <h1 class="heading-1">Productos</h1>
        </div>

        <!-- Contenedor principal de Swiper -->
        <div class="swiper produ-row">
            <div class="swiper-wrapper">
                <?php
                $counter = 4;
                foreach (array_slice($pasteles, 3) as $row) {
                    $id = $row['id_pastel'];
                    $desc = $row['descripcion'];
                    $imagen = "../Static/img/Productos/" . $row['nombreP'] . ".png";
        
                    if (!file_exists($imagen)) {
                        $imagen = "../Static/img/nophoto.png";
                    }
                    ?>
                    <!-- Cada item o "slide" dentro del swiper -->
                    <div class="swiper-slide box">
                        <div class="img">
                            <img onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, 
                                    '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')" src="<?php echo $imagen; ?>" alt="">
                        </div>
                        <div class="produ-content">
                            <h3><?php echo $row['nombreP']; ?>.</h3>
                            <div class="orderNow">
                                <button class="fa-solid fa-basket-shopping" 
                                    onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, 
                                    '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')">
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                    $counter++;
                }
                ?>
            </div>
            <!-- Paginación -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <?php require '../Static/footer.php';?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>