<?php 
session_start(); // Asegúrate de iniciar la sesión al principio
require '../Static/head.php'; // Usar $_SERVER['DOCUMENT_ROOT'] para rutas absolutas

?>
<link rel="stylesheet" href="../Static/css/IndexStyle.css" />
<script src="../Static/js/Index.js"></script>

<body>
    <header>
        <!-- Parte fija siempre visible -->
        <div class="container-hero">
            <div class="hero">
                <div class="customer-support">
                    <i class="fa-solid fa-headset"></i>
                    <div class="content-customer-support">
                        <span class="text">Soporte al cliente</span>
                        <span class="number">123-456-7890</span>
                    </div>
                </div>
                <div class="container-logo">
                    <img src="../Static/img/clausLogo.png" alt="Claus Logo" />
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
                    <div class="content-shopping-cart"></div>
                    <span class="text">Artículos</span>
                    <span class="number">(<?php echo $numero_articulos; ?>)</span>
                </div>
            </div>
        </div>

        <!-- Parte visible solo al deslizar hacia arriba -->
        <div class="container-navbar">
            <nav class="navbar ">
                <button class="navbar-toggler" onclick="toggleMenu()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <ul class="menu">
                    <li><a href="#pasteles">Pasteles</a></li>
                    <li><a href="#recetas">Recetas</a></li>
                    <li><a href="#quienessomos">¿Quienes Somos?</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    $image_url = '../Static/img/IndexBanner.png';
    ?>

    <section class="banner" style="background-image: linear-gradient(100deg, #000000, #00000020), url('<?php echo $image_url; ?>');">
        <div class="content-banner">
            <h2>100% Caseros <br />Realiza tu pedido</h2>
            <a href="#">Apartalo ahora</a>
        </div>
    </section>

    <section class="container specials">
        <a name="pasteles"></a>
        <h1 class="heading-1">Personalizables</h1>
        <div class="container-personalizable">
            <?php
            $counter = 0;
            foreach ($pasteles as $row) {
                if ($counter >= 3) {
                    break; // Sal del bucle después de mostrar los 3 primeros pasteles personalizables
                }
                $id = $row['id_pastel'];
                $desc = $row['descripcion'];
                $imagen = "../Static/img/Productos/" . $row['nombreP'] . ".png";
                $url = $row['nombreP'] . ".php";

                if (!file_exists($imagen)) {
                    $imagen = "../Static/img/nophoto.png";
                }
                ?>
                <div class="card-personalizable">
                    <div class="container-img-personalizable">
                        <img onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')" src="<?php echo $imagen; ?>" alt="<?php echo $row['nombreP']; ?>" />
                        <div class="button-group-personalizable">
                            <span class="add-res-personalizable"
                                onclick="VerResenias(<?php echo $id; ?>, '<?php echo $url; ?>', <?php echo $id; ?>, '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')">
                                <i class="fa fa-comments"></i>
                            </span>
                        </div>
                    </div>
                    <div class="content-card-personalizable">
                        <h3><?php echo $row['nombreP']; ?></h3>
                        <p class="price-personalizable">$<?php echo number_format($row['precio'], 2, '.', '.'); ?></p>
                        <span class="add-cart-personalizable"
                            onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </span>
                    </div>
                </div>
                <?php
                $counter++;
            }
            ?>
        </div>
    </section>

    <section class="gallery">
        <img src="../Static/img/Pgallery1.png" alt="Gallery Img1" class="gallery-img-1" />
        <img src="../Static/img/Pgallery2.png" alt="Gallery Img2" class="gallery-img-2" />
        <img src="../Static/img/Pgallery3.png" alt="Gallery Img3" class="gallery-img-3" />
        <img src="../Static/img/Pgallery4.png" alt="Gallery Img4" class="gallery-img-4" />
        <img src="../Static/img/Pgallery5.png" alt="Gallery Img5" class="gallery-img-5" />
    </section>

    <section class="container specials">
        <h1 class="heading-1">Especiales</h1>
        <div class="container-products">
            <?php
            foreach (array_slice($pasteles, 3) as $row) {
                $id = $row['id_pastel'];
                $desc = $row['descripcion'];
                $imagen = "../Static/img/Productos/" . $row['nombreP'] . ".png";
                $url = '../Vista/' . $row['nombreP'] . ".php";

                if (!file_exists($imagen)) {
                    $imagen = "../Static/img/nophoto.png";
                }
                ?>
                <div class="card-product">
                    <div class="container-img">
                        <img onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, 
                            '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')"src="<?php echo $imagen; ?>" alt="<?php echo $row['nombreP']; ?>" />
                        <div class="button-group">
                            <span class="add-res"
                                onclick="VerResenias(<?php echo $id; ?>, '<?php echo $url; ?>', <?php echo $id; ?>, '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')">
                                <i class="fa fa-comments"></i>
                            </span>
                        </div>
                    </div>
                    <div class="content-card-product">
                        <h3><?php echo $row['nombreP']; ?></h3>
                        <p class="price">$<?php echo number_format($row['precio'], 2, '.', '.'); ?></p>
                        <span class="add-cart"
                            onclick="handleButtonClick(<?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id_pastel']; ?>, 
                            '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>')">
                            <i class="fa-solid fa-basket-shopping"></i>
                        </span>
                    </div>
                </div>
            <?php
            }
            ?>
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
            <h1 class="heading-1">Recetas</h1>
        </div>

        <!-- Contenedor principal de Swiper -->
        <div class="swiper produ-row">
            <div class="swiper-wrapper">
                <?php
                $counter = 0;
                foreach($recetas as $row) {
                    $id = $row['id'];
                    $desc = $row['receta'];
                    $imagen = "../Static/img/Recetas/" . $row['nombre'] . ".png";

                    if (!file_exists($imagen)) {
                        $imagen = "../Static/img/nophoto.png";
                    }
                    ?>
                    <!-- Cada item o "slide" dentro del swiper -->
                    <div class="swiper-slide box">
                        <div class="img">
                            <img onclick="handleButtonClick2(
                                    <?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id']; ?>, 
                                    '<?php echo htmlspecialchars($row['receta'], ENT_QUOTES); ?>')"src="<?php echo $imagen; ?>" alt="">
                        </div>
                        <div class="produ-content">
                            <h3><?php echo $row['nombre']; ?>.</h3>
                            <div class="orderNow">
                                <button class="fa-regular fa-eye order-btn" onclick="handleButtonClick2(
                                    <?php echo $counter; ?>, '<?php echo $url; ?>', <?php echo $row['id']; ?>, 
                                    '<?php echo htmlspecialchars($row['receta'], ENT_QUOTES); ?>')">
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

    <?php require '../Static/footer.php'; ?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>

<?php
if (isset($_SESSION['usuario']) && isset($_SESSION['login_success'])) { ?>
    <script>
        console.log(typeof Swal); // Esto debería mostrar "function"
        mostrarLogueoSuccess();
    </script>
<?php
    unset($_SESSION['login_success']);
}
?>

<script>
    async function VerResenias(id) {
        // Realiza una solicitud AJAX para obtener los datos del pedido
        const response = await fetch("../Controlador/read_resenias.php?id_pastel=" + id);
        const tableHTML = await response.text();

        // Lógica para verificar si el usuario está autenticado
        const isAuthenticated = <?php echo isset($_SESSION['usuario']) && $rol == 3 ? 'true' : 'false'; ?>;

        // Muestra el resultado en SweetAlert
        await Swal.fire({
            title: "Reseñas",
            html: `
                <form id="modalForm" method="post" action="../Controlador/Add_resenia.php" style="margin-top: 20px;">
                    <input type="hidden" name="id_pastel" value="${id}">
                    <section class="container-info-product" style="text-align: left; margin-top: 15px;">
                        <div class="container-reviews">
                            <div class="title-reviews">
                                <h4 style="display: inline;">Reseñas</h4>
                                <i class="fa-solid fa-chevron-down" onclick="toggleContent('.text-reviews')"></i>
                            </div>
                            <div class="text-reviews" style="display: block;">
                                ${tableHTML}
                            </div>
                        </div>
                    </section>
                    ${isAuthenticated ? `
                        <textarea name="resenia" id="swal-resenia" maxlength="30" rows="3" placeholder="Escribe tu reseña aquí (máx. 30 palabras)" style="width: 100%;" required></textarea>
                    ` : '<p>Por favor, inicie sesión para dejar una reseña.</p>'}
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: isAuthenticated ? 'Confirmar' : 'OK',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                if (isAuthenticated) {
                    const reseniaValue = document.getElementById('swal-resenia').value.trim();
                    if (!reseniaValue) {
                        Swal.showValidationMessage('La reseña no puede estar vacía');
                        return false; // Bloquea el envío si la validación falla
                    } else {
                        return true; // Permite que el formulario continúe si la validación se pasa
                    }
                }
            },
            didOpen: () => {
                const confirmButton = Swal.getConfirmButton();
                confirmButton.disabled = !isAuthenticated; 
            }
        }).then((result) => {
            if (result.isConfirmed && isAuthenticated) {
                document.getElementById("modalForm").submit(); // Enviar el formulario si se confirmó y está autenticado
            }
        });
    }
</script>