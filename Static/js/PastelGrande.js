//HEADER
function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
  }

//BARRA DE TEXTO
function actualizarContador() {
    const input = document.getElementById("inputTexto");
    const contadorTexto = document.getElementById("contadorTexto");
    const maximo = input.maxLength;
    const actual = input.value.length;

    // Actualiza el contador de caracteres
    contadorTexto.textContent = `${actual}/${maximo}`;
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
