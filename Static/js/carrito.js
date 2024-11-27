
function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
  }
  
  /* ********************************** */
/*               NavBar              */
/* ********************************** */

  document.addEventListener('DOMContentLoaded', () => {
    let lastScrollY = window.scrollY;
    const containerHero = document.querySelector('.container-hero');
    const containerNavbar = document.querySelector('.container-navbar');

    if (!containerHero || !containerNavbar) {
        console.error("Los elementos .container-hero o .container-navbar no se encontraron.");
        return;
    }

    window.addEventListener('scroll', () => {
        if (window.scrollY > lastScrollY) {
            // Deslizándose hacia abajo
            containerHero.classList.add('hidden'); // Oculta el hero
            containerNavbar.classList.add('hidden'); // Oculta la navbar
        } else {
            // Deslizándose hacia arriba
            containerHero.classList.remove('hidden'); // Muestra el hero
            containerNavbar.classList.remove('hidden'); // Muestra la navbar
        }
        lastScrollY = window.scrollY;
    });
});

function toggleMenu() {
    const menu = document.querySelector('.menu');
    if (menu) {
        menu.classList.toggle('show'); // Alterna la clase para mostrar/ocultar
    }
}

document.addEventListener('click', (event) => {
    const menu = document.querySelector('.menu');
    const toggler = document.querySelector('.navbar-toggler');
    if (menu && !menu.contains(event.target) && !toggler.contains(event.target)) {
        menu.classList.remove('show'); // Cierra el menú si haces clic fuera de él
    }
});

/* ********************************** */
/*               ProduActu            */
/* ********************************** */
document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper(".produ-row", {
        spaceBetween: 30,  // Espacio entre los slides
        loop: true,        // Loop infinito
        centeredSlides: true,  // Centra el slide activo
        autoplay: {
            delay: 9500,  // Tiempo de autoplay
            disableOnInteraction: false,  // No desactiva el autoplay al interactuar
        },
        pagination: {
            el: ".swiper-pagination",  // Paginación debajo del swiper
            clickable: true,  // Habilita la paginación clickeable
        },
        breakpoints: {
            0: {
                slidesPerView: 1,  // 1 slide en pantallas pequeñas
            },
            768: {
                slidesPerView: 2,  // 2 slides en pantallas medianas
            },
            1024: {
                slidesPerView: 3,  // 3 slides en pantallas grandes
            },
        },
    });
});


/* ********************************** */
/*               BlogActu           */
/* ********************************** */
document.addEventListener("DOMContentLoaded", function() {
    var swiper = new Swiper(".blogs-row", {
        spaceBetween: 30,
        loop:true,
        centeredSlides:true,
        autoplay:{
            delay:9500,
            disableOnInteraction:false,
        },
        pagination: {
        el: ".swiper-pagination",
        clickable: true,
        },
        navigation:{
            nextE1 :".swiper-button-next",
            prevE1 :".swiper-button-prev",
        },
        breakpoints: {
        0: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 1,
        },
        1024: {
            slidesPerView: 1,
        },
        },
    });
});

// Función principal que maneja el clic del botón
function handleButtonClick(counter, url, id, descripcion) {
    if (counter < 3) {
        window.location.href = url; // Redirige si el contador es menor que 3
    } else {
        openSweetAlert(id, descripcion); // Abre SweetAlert con los detalles del pastel
    }
}

function openSweetAlert(id, descripcion) {
    Swal.fire({
        title: 'Selecciona la fecha de entrega',
        html: `
            <form id="modalForm" method="post" action="../Controlador/add_carrito.php">
                <label for="fecha_entrega">Fecha de entrega:</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" required style="display: block; margin: 10px 0;">
                <input type="hidden" name="id_pastel" value="${id}">

                <section class="container-info-product" style="text-align: left; margin-top: 15px;">
                    <div class="container-description">
                        <div class="title-description">
                            <h4 style="display: inline;">Descripción</h4>
                            <i class="fa-solid fa-chevron-down" onclick="toggleContent('.text-description')"></i>
                        </div>
                        <div class="text-description" style="display: none;">
                            <p>${descripcion}</p>
                        </div>
                    </div>

                    <div class="container-additional-information">
                        <div class="title-additional-information">
                            <h4 style="display: inline;">Información adicional</h4>
                            <i class="fa-solid fa-chevron-down" onclick="toggleContent('.text-additional-information')"></i>
                        </div>
                        <div class="text-additional-information" style="display: none;">
                            <p>-----------</p>
                        </div>
                    </div>
                </section>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const fechaEntrega = document.getElementById("fecha_entrega").value;
            if (!fechaEntrega) {
                Swal.showValidationMessage("Por favor selecciona una fecha de entrega.");
                return false; // No continuar si la fecha está vacía
            }
            document.getElementById("modalForm").submit(); // Envía el formulario al confirmar
        }
    });
}


// Función para alternar la visibilidad de las secciones de contenido
function toggleContent(selector) {
const element = document.querySelector(selector);
element.style.display = element.style.display === 'none' ? 'block' : 'none';
}

// Constantes Toggle Titles
const toggleDescription = document.querySelector(
'.title-description'
);
const toggleAdditionalInformation = document.querySelector(
'.title-additional-information'
);
const toggleReviews = document.querySelector('.title-reviews');

// Constantes Contenido Texto
const contentDescription = document.querySelector(
'.text-description'
);
const contentAdditionalInformation = document.querySelector(
'.text-additional-information'
);
const contentReviews = document.querySelector('.text-reviews');

// Funciones Toggle
toggleDescription.addEventListener('click', () => {
contentDescription.classList.toggle('hidden');
});

toggleAdditionalInformation.addEventListener('click', () => {
contentAdditionalInformation.classList.toggle('hidden');
});

toggleReviews.addEventListener('click', () => {
contentReviews.classList.toggle('hidden');
});


function actualizarTotal(index, precioUnitario) {
    // Obtener el valor actual de la cantidad
    const cantidadInput = document.getElementById(`cantidad-${index}`);
    const cantidad = parseInt(cantidadInput.value, 10);

    if (isNaN(cantidad) || cantidad < 1) {
        alert('Por favor, ingrese una cantidad válida.');
        cantidadInput.value = 1;
        return;
    }

    // Calcular el subtotal
    const subtotal = cantidad * precioUnitario;

    // Actualizar el subtotal mostrado
    const subtotalElement = document.getElementById(`subtotal-${index}`);
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;

    // Recalcular el total general
    recalcularTotal();
}

function recalcularTotal() {
    // Buscar todos los elementos de cantidad y calcular subtotales
    const cantidadInputs = document.querySelectorAll('input[name="quantity"]');
    let total = 0;

    cantidadInputs.forEach(input => {
        const index = input.id.split('-')[1];
        const precioUnitario = parseFloat(input.getAttribute('data-precio-unitario')); // Obtener precio desde el atributo
        const cantidad = parseInt(input.value, 10);

        if (!isNaN(precioUnitario) && !isNaN(cantidad)) {
            total += cantidad * precioUnitario;
        }
    });

    // Actualizar el total general mostrado
    const totalElement = document.getElementById('total-general');
    totalElement.textContent = `$${total.toFixed(2)}`;

    // Actualizar el resumen de productos
    const resumenProductos = document.querySelector('.list-group-item span');
    resumenProductos.textContent = `$${total.toFixed(2)}`;
}
