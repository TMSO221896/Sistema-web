<?php 
require '../Static/head.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: Iniciosesion.php");
    exit;
}
$pastel_id = 3;  // Este debería venir de algún lugar, como $_GET['id'] o similar
$consulta = "SELECT * FROM pasteles WHERE id_pastel = '$pastel_id'";
$resultado = mysqli_query($conexion, $consulta);
$pastel = mysqli_fetch_array($resultado);
?>
<link rel="stylesheet" href="../Static/css/PastelGrandeStyle.css" />
<script src="../Static/js/PastelGrande.js"></script>
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
						  <h1 class="logo"><a></a></h1>
					</div>

					<div class="container-user">
						<div class="dropdown">
							<a class="fa-solid fa-user" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							</a>

							<ul class="dropdown-menu">
								<?php if (isset($_SESSION['usuario']) && $rol == 3): ?>
									<li><a class="dropdown-item" href="../Vista/Logout.php">Cerrar sesión</a></li>
									<li><a class="dropdown-item" href="../Vista/Miperfil.php">Mi Perfil</a></li>
									<li><a class="dropdown-item" href="../Vista/pedidos.php">Mis Pedidos</a></li>
								<?php elseif (isset($_SESSION['usuario']) && $rol == 2): ?>
									<li><a class="dropdown-item" href="../Vista/Logout.php">Cerrar sesión</a></li>
									<li><a class="dropdown-item" href="../Vista/IndexGerente.php">Configuración</a></li>
								<?php elseif (isset($_SESSION['usuario']) && $rol == 1): ?>
									<li><a class="dropdown-item" href="../Vista/Logout.php">Cerrar sesión</a></li>
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
        
        <main>
			<div class = contenedor>
				<section class="gallery">
					<img
						src="../Static/img/Pgallery1.png"
						alt="Gallery Img1"
						class="gallery-img-1"
					/><img
						src="../Static/img/Pgallery2.png"
						alt="Gallery Img2"
						class="gallery-img-2"
					/><img
						src="../Static/img/Pgallery3.png"
						alt="Gallery Img3"
						class="gallery-img-3"
					/>
				</section>
				
				<section class="container-info-product">
					<div class="container-price">
						<span>$
							<?php
								echo $pastel['precio'];
							?> 
						</span>
					</div>

					<?php (isset($_SESSION['username'])) ?>
						<div class="form-group">
							<form method="POST" action="../Controlador/Add_carrito.php">
							<input type="hidden" name="id_pastel" value="3" required>
								<!-- Selección de color -->
								<div class="container-details-product">
									<div class="form-group">
										<label for="color">Color:</label>
										<select name="color" id="color" required>
											<option disabled selected value="">
												Escoge una opción
											</option>
											<option value="rojo">Rojo</option>
											<option value="blanco">Blanco</option>
											<option value="beige">Beige</option>
										</select>
									</div>
								</div>

								<!-- Texto personalizado -->
								<div class="container-text-product">
									<div class="form-group">
										<label for="inputTexto">Texto&nbsp;personalizado:</label><br>
										<input type="text" id="inputTexto" name="texto" maxlength="40" placeholder="Escribe aquí..." oninput="actualizarContador()" required>
									</div>
								</div>
								
								<!-- Selección de fecha de entrega -->
								<div class="container-date-product">
									<div class="form-group">
										<br><label for="fecha_entrega">Fecha&nbsp;de&nbsp;entrega:</label><br>
										<input type="date" name="fecha_entrega" id="fecha_entrega" required><br>
									</div>
								</div><br>

								<!-- Botón para añadir al pedido -->
								<div class="container-add-cart">
									<button type="submit" class="btn-add-to-cart" name="add_pastel">
										<i class="fa-solid fa-plus" ></i>
										Añadir al carrito
									</button>
								</div>
							</form>
						</div>

					<div class="container-description">
						<div class="title-description">
							<h4>Descripción</h4>
							<i class="fa-solid fa-chevron-down"></i>
						</div>
						<div class="text-description">
							<p>
								<?php
									echo $pastel['descripcion'];
								?>
							</p>
						</div>
					</div>

					<div class="container-additional-information">
						<div class="title-additional-information">
							<h4>Información adicional</h4>
							<i class="fa-solid fa-chevron-down"></i>
						</div>
						<div class="text-additional-information hidden">
							<p>-----------</p>
						</div>
					</div>

					<div class="container-reviews">
						<div class="title-reviews">
							<h4>Reseñas</h4>
							<i class="fa-solid fa-chevron-down"></i>
						</div>
						<div class="text-reviews hidden">
							<p>-----------</p>
						</div>
					</div>

					<div class="container-social">
						<span>Compartir</span>
						<div class="container-buttons-social">
							<a href="#"><i class="fa-solid fa-envelope"></i></a>
							<a href="#"><i class="fa-brands fa-facebook"></i></a>
							<a href="#"><i class="fa-brands fa-twitter"></i></a>
							<a href="#"><i class="fa-brands fa-instagram"></i></a>
							<a href="#"><i class="fa-brands fa-pinterest"></i></a>
						</div>
					</div>
				</section>
			</div>
		</main>
		<?php require '../Static/footer.php';?>
	</body>

