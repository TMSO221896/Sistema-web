
<footer class="footer">
			<div class="container container-footer">
				<div class="menu-footer">
					<div class="contact-info">
						<p class="title-footer">Información de Contacto</p>
						<ul>
							<li>
								Av.Insurgentes #226 Col. Emiliano Zapata, Cuautla Mor.
							</li>
							<li>Teléfono: 123-456-7890</li>
							<li>EmaiL: claumartello@claus.com</li>
						</ul>
						<div class="social-icons">
							<span class="facebook" >
								<a class="fa-brands fa-facebook-f" href="https://www.facebook.com/ClausDetallesYConfiteria/photos" role="button"></a>
							</span>
							<span class="tiktok">
								<a class="fa-brands fa-tiktok" href="https://www.tiktok.com/@claus.detalles.y" role="button"></a>
							</span>
							<span class="youtube">
								<a class="fa-brands fa-youtube" href="https://www.youtube.com/@clausdetallesyconfiteria" role="button"></a>
							</span>
							<span class="whatsapp">
								<a class="fa-brands fa-whatsapp" href="https://wa.link/69atyu" role="button"></a>
							</span>
							<span class="instagram">
								<a class="fa-brands fa-instagram" href="https://www.instagram.com/clausdetallesyconfiteria/" role="button"></a>
							</span>
						</div>
					</div>

					<div class="information">
						<p class="title-footer">Información</p>
						<ul>
							<li><a href="#quienessomos">Acerca de Nosotros</a></li>
							<li><a href="https://wa.link/69atyu">Contactános</a></li>
						</ul>
					</div>

					<div class="my-account">
						<?php if (isset($_SESSION['usuario']) && $rol == 1): ?>
							<p class="title-footer">Configuración Admin</p>
						
							<ul>
								<li><a href="../Vista/IndexAdmin.php">Mi cuenta</a></li>
							</ul>
						<?php elseif (isset($_SESSION['usuario']) && $rol == 2): ?>
							<p class="title-footer">Configuración Gerente</p>
						
							<ul>
								<li><a href="../Vista/IndexGerente.php">Mi cuenta</a></li>
							</ul>
						<?php else:  ?>
							<p class="title-footer">Mi cuenta</p>
						
							<ul>
								<li><a href="../Vista/Miperfil.php">Mi cuenta</a></li>
								<li><a href="../Vista/pedidos.php">Mis Pedidos</a></li>
								<li><a href="../Vista/carrito.php">Carrito</a></li>
							</ul>
						<?php endif; ?>
						
					</div>

					<div class="newsletter">
						<p class="title-footer">Comentarios</p>
						<div class="content">
              <div class="comentarios">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Usuario</th>
                                <th>Comentario</th>
                                <th>Satisfacción</th> <!-- Nueva columna para mostrar las estrellas -->
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consultar las reseñas del pastel incluyendo la satisfacción
                            $consulta = "SELECT c.idUsuario, c.id ,c.comentario, c.satisfaccion, u.nombre FROM comentarios c JOIN usuarios u ON c.idUsuario = u.usuario;";
                            $resultado = mysqli_query($conexion, $consulta);
                            $index = 0;

                            // Verificar si la consulta tiene resultados
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($row = mysqli_fetch_assoc($resultado)) {
                                    $nombre = $row['nombre'];
                                    $id_com = $row['id'];
                                    $com = $row['comentario'];
                                    $satisfaccion = $row['satisfaccion'];
                            ?>
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo $com; ?></td>
                                <td>
                                    <!-- Mostrar las estrellas según el valor de satisfaccion -->
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $satisfaccion) {
                                            echo '<i class="fa fa-star" style="color: gold;"></i>'; // Estrella llena
                                        } else {
                                            echo '<i class="fa fa-star" style="color: gray;"></i>'; // Estrella vacía
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                  <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === $row['idUsuario'] || $rol === 1): ?>
                                        <form method="POST" action="../Controlador/Drop_comentario.php" style="display: inline;" id="form-eliminarCom-<?php echo $index; ?>">
                                            <input type="hidden" name="id_comentario" value="<?php echo $id_com; ?>">
                                            <button type="button" onclick="confirmarEliminacionCom(<?php echo $index; ?>)" style="background: none; border: none; padding: 0;">
                                                <i class="fa fa-trash" aria-hidden="true" style="color: black; font-size: 0.9em; cursor: pointer;"></i>
                                            </button>
                                        </form>
                                  <?php endif; ?>
                                  <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario'] === $row['idUsuario']): ?>
                                        <input type="hidden" name="id_comentario" value="<?php echo $id_com; ?>">
                                        <button type="button" onclick="ActualizarComentario(event, <?php echo $id_com; ?>)" style="background: none; border: none; padding: 0;"
                                            data-comentario="<?php echo $com; ?>">
                                            <i class="fa fa-pencil" aria-hidden="true" style="color: black; font-size: 0.9em; cursor: pointer;"></i>
                                        </button>
                                  <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                                $index++;
                                }
                            } else {
                                echo '<tr><td colspan="4">No hay Comentarios disponibles.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
							<?php if (isset($_SESSION['usuario']) && $rol == 3): ?>
								<form method="POST" action="../Controlador/Add_comentario.php" id="form-comentario">
									<input type="hidden" name="calificacion" id="calificacion">
									<input type="hidden" name="descripcion" id="descripcion">
									<button type="button" id="btn-submit">Subir</button>
								</form>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="copyright">
					<p>
						@Derechos reservados.
					</p>
				</div>
			</div>
		</footer>

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
document.getElementById('btn-submit').addEventListener('click', function() {
  let calificacion = 0;
  const estrellasHtml = `
    <div id="estrellas-container" style="font-size: 2rem; cursor: pointer;">
      <span data-value="1" style="margin: 0 5px;">☆</span>
      <span data-value="2" style="margin: 0 5px;">☆</span>
      <span data-value="3" style="margin: 0 5px;">☆</span>
      <span data-value="4" style="margin: 0 5px;">☆</span>
      <span data-value="5" style="margin: 0 5px;">☆</span>
    </div>
  `;

  Swal.fire({
    title: 'Califica tu satisfacción',
    html: estrellasHtml + '<textarea id="input-descripcion" placeholder="Escribe tu comentario aquí..." style="width: 100%; margin-top: 10px;"></textarea>',
    showCancelButton: true,
    confirmButtonText: 'Enviar',
    preConfirm: () => {
      const descripcion = document.getElementById('input-descripcion').value;
      if (!calificacion) {
        Swal.showValidationMessage('Por favor selecciona una calificación');
        return false;
      }
      if (!descripcion.trim()) {
        Swal.showValidationMessage('Por favor ingresa una descripción');
        return false;
      }
      return { calificacion: calificacion, descripcion: descripcion };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      // Asignar valores al formulario oculto y enviarlo
      document.getElementById('calificacion').value = result.value.calificacion;
      document.getElementById('descripcion').value = result.value.descripcion;
      document.getElementById('form-comentario').submit();
    }
  });

  document.getElementById('estrellas-container').addEventListener('click', function(event) {
    if (event.target.tagName === 'SPAN') {
      calificacion = event.target.getAttribute('data-value');
      actualizarEstrellas(calificacion);
    }
  });

  function actualizarEstrellas(valor) {
    const estrellas = document.querySelectorAll('#estrellas-container span');
    estrellas.forEach((estrella, index) => {
      if (index < valor) {
        estrella.textContent = '★'; // Rellenar estrella
      } else {
        estrella.textContent = '☆'; // Estrella vacía
      }
    });
  }
});
</script>