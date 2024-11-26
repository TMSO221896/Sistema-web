function validarDireccion(event) {
    event.preventDefault();

    const form = document.querySelector('.create-dir');
    const calle = form.querySelector('[name="calle"]').value.trim();
    const colonia = form.querySelector('[name="colonia"]').value.trim();
    const cod_postal = form.querySelector('[name="cod_postal"]').value.trim();
    const numero = form.querySelector('[name="numero"]').value.trim();
    const estado = form.querySelector('[name="estado"]').value.trim();
    const ciudad = form.querySelector('[name="ciudad"]').value.trim();

    // Validaciones adicionales
    if (!calle || !colonia || !cod_postal || !numero || !estado || !ciudad) {
        alert('Todos los campos son obligatorios');
        return;
    }

    if (!/^\d{1,5}$/.test(cod_postal)) {
        alert('El código postal debe ser un número de hasta 4 dígitos');
        return;
    }

    if (isNaN(numero) || parseFloat(numero) <= 0) {
        alert('El campo "número" debe ser un número mayor a 0 y válido');
        return;
    }

    // Si todo es válido, se envía el formulario
    form.submit();
}
