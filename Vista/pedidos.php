<?php
require '../Static/head.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Vista/Iniciosesion.php");
    exit;
}
?>
<link rel="stylesheet" href="../Static/css/PedidosStyle.css">
<script src="../Static/js/Pedidos.js"></script>

<body>
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
							<a class="fa-solid fa-user" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							</a>

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
									<li><a class="dropdown-item" href="../Vista/Iniciosesion.php">Iniciar sesión</a></li>
									<li><a class="dropdown-item" href="../Vista/Iniciosesion.php">Registrarse</a></li>
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

        <main class="container my-5">
            <h1 class="text-center mb-5">Mis Pedidos</h1>

            <?php
            // Consultar los pedidos del usuario actual
            $query_pedidos = $conexion->prepare("SELECT * FROM pedidos WHERE user_id = ?");
            $query_pedidos->bind_param("s", $user_id);
            $query_pedidos->execute();
            $result_pedidos = $query_pedidos->get_result();

            // Verificar si hay resultados
            if ($result_pedidos->num_rows === 0) {
                // Mostrar mensaje y imagen si no hay pedidos
                echo '<div class="text-center my-5">';
                echo '<img src="../Static/img/vacio.png" alt="Sin pedidos" style="max-width: 300px; height: auto; margin-bottom: 20px;">';
                echo '</div>';
            } else {
                echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
                // Mostrar cada pedido
                while ($pedido = $result_pedidos->fetch_assoc()) {
                    $id_pedido = $pedido['id'];
                    $nombre_cliente = $rows['nombre'] . ' ' . $rows['apellido']; // Ajusta según los datos
                    $total = $pedido['Total'];
                    $estatus = $pedido['estatus'];
                    $fecha_actual = $pedido['fecha_actual'];
            ?>
                    <div class="col">
                        <div class="card rounded-3 shadow-sm" style="border-top: 4px solid #f16cb6; background-color: #fff;">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-dark"><?php echo htmlspecialchars($nombre_cliente); ?></h5>
                                    <span class="badge 
                                        <?php
                                        switch ($estatus) {
                                            case 'Pendiente':
                                                echo 'bg-warning text-dark';
                                                break;
                                            case 'Entregado':
                                                echo 'bg-success';
                                                break;
                                            case 'Cancelado':
                                                echo 'bg-danger';
                                                break;
                                            default:
                                                echo 'bg-secondary';
                                        }
                                        ?>">
                                        <?php echo htmlspecialchars($estatus); ?>
                                    </span>
                                </div>

                                <p class="text-muted mb-2">Pedido #<?php echo htmlspecialchars($id_pedido); ?></p>
                                <p class="text-muted">Fecha: <?php echo htmlspecialchars($fecha_actual); ?></p>
                                <p class="fw-bold text-dark" style="font-size: 1.2rem;">$<?php echo number_format($total, 2); ?></p>

                                <!-- Botones de acción -->
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="VisualizarProductos(event, <?php echo $id_pedido; ?>)">
                                        <i class="fas fa-cake"></i> Ver Artículos
                                    </button>
                                    <?php if (in_array($estatus, ['Entregado', 'Pendiente', 'Cancelado'])) { ?>
                                        <form action="../Controlador/Drop_pedido.php" method="POST" id="form-eliminarPed-<?php echo $id_pedido; ?>">
                                            <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
                                            <button type="button" class="btn btn-outline-danger w-100" onclick="confirmarEliminacionPedido(<?php echo $id_pedido; ?>)">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
                echo '</div>'; // Cerrar la fila de pedidos
            }
            ?>
        </main>

        <section class="gallery">
            <img src="../Static/img/Pgallery1.png" alt="Gallery Img1" class="gallery-img-1" />
            <img src="../Static/img/Pgallery2.png" alt="Gallery Img2" class="gallery-img-2" />
            <img src="../Static/img/Pgallery3.png" alt="Gallery Img3" class="gallery-img-3" />
            <img src="../Static/img/Pgallery4.png" alt="Gallery Img4" class="gallery-img-4" />
            <img src="../Static/img/Pgallery5.png" alt="Gallery Img5" class="gallery-img-5" />
        </section>

        <section class="blogs" id="blogs">
            <div class="swiper blogs-row">
                <div class="swiper-wrapper">
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="../Static/img/clausLogo.png" alt="">
                        </div>
                        <div class="content">
                            <a name="quienessomos"></a>
                            <h3>¿Quienes Somos?</h3>
                            <p>Clau's Detalles y Confitería, ubicada en Cuautla, Morelos, México, se especializa en el sector comercial, 
                                con un giro dedicado a la elaboración y venta de pasteles, confitería y productos personalizados. 
                                Como pequeña empresa, inició sus operaciones en 2013 con la venta de galletas. A lo largo de los años, 
                                la empresa ha expandido su catálogo, ofreciendo una variedad de productos que ahora promociona en sus redes sociales.</p>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="../Static/img/IndexBanner.png" alt="">
                        </div>
                        <div class="content">
                            <h3>Misión</h3>
                            <p>La misión de Clau's Detalles y Confitería es deleitar a sus clientes
                                con productos de alta calidad, disponibles tanto en venta directa en tienda 
                                como para eventos especiales. Diariamente, la empresa elabora productos con recetas
                                desde cero, utilizando ingredientes seleccionados por su calidad.</p>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <div class="img">
                            <img src="../Static/img/Pgallery1.png" alt="">
                        </div>
                        <div class="content">
                            <h3>Visión</h3>
                            <p>La visión de Clau's Detalles y Confitería es convertirse en la 
                                pastelería líder de la región, sin dejar de lado sus estándares 
                                de calidad y servicio. La expansión hacia nuevas regiones y la
                                innovación constante en productos son las principales metas que 
                                la empresa busca alcanzar.</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <?php require '../Static/footer.php';?>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
		
    </body>
