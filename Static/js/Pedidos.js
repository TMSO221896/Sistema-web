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
