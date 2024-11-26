function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
  }

  async function ActualizarComentario(event, id_comentario) {
    event.preventDefault();

    // Aseguramos que estamos obteniendo el botón
    const button = event.target.closest('button');
    const comentario = button.getAttribute("data-comentario");

    const { value: formValues } = await Swal.fire({
        title: "Actualizar Comentario",
        html: `
            <textarea name="comentario" id="swal-comentario" class="swal2-input" rows="4" style="width: 100%;">${comentario}</textarea>
            <label for="swal-satisfaccion">Selecciona tu nivel de satisfacción:</label>
            <div id="swal-satisfaccion">
                <i class="fa fa-star" data-value="1" style="font-size: 1.5em; cursor: pointer;"></i>
                <i class="fa fa-star" data-value="2" style="font-size: 1.5em; cursor: pointer;"></i>
                <i class="fa fa-star" data-value="3" style="font-size: 1.5em; cursor: pointer;"></i>
                <i class="fa fa-star" data-value="4" style="font-size: 1.5em; cursor: pointer;"></i>
                <i class="fa fa-star" data-value="5" style="font-size: 1.5em; cursor: pointer;"></i>
            </div>
            <input type="hidden" id="swal-satisfaccion-value" name="satisfaccion" value="3">
        `,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        didOpen: () => {
            // Añade el evento de clic para las estrellas
            const stars = document.querySelectorAll('#swal-satisfaccion .fa-star');
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    // Quita la clase "selected" de todas las estrellas
                    stars.forEach(s => s.style.color = 'black');
                    // Marca las estrellas seleccionadas y anteriores
                    const rating = star.getAttribute('data-value');
                    document.getElementById('swal-satisfaccion-value').value = rating;
                    for (let i = 0; i < rating; i++) {
                        stars[i].style.color = 'gold';
                    }
                });
            });
        },
        preConfirm: () => {
            const comentarioValue = document.getElementById('swal-comentario').value.trim();
            const satisfaccionValue = document.getElementById('swal-satisfaccion-value').value;

            if (!comentarioValue) {
                Swal.showValidationMessage('El comentario no puede estar vacío');
                return false;
            }

            if (!satisfaccionValue || satisfaccionValue < 1 || satisfaccionValue > 5) {
                Swal.showValidationMessage('Por favor selecciona una calificación válida');
                return false;
            }

            return {
                comentario: comentarioValue,
                satisfaccion: satisfaccionValue,
                id_comentario: id_comentario
            };
        }
    });

    if (formValues) {
        GuardarCambiosComentarios(formValues);
    }
}



	
		