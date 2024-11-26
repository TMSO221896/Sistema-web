function mostrarLogueoSuccess() {
    const Toast = Swal.mixin({
       toast: true,
       position: "top-end",
       showConfirmButton: false,
       timer: 3000,
       timerProgressBar: true,
       didOpen: (toast) => {
         toast.onmouseenter = Swal.stopTimer;
         toast.onmouseleave = Swal.resumeTimer;
       }
     });
     Toast.fire({
       icon: "success",
       title: "Inicio de sesión exitoso"
     });
 }
 

function validarContrasenas(event) {
    let Acontraseña = document.querySelector("input[name='Acontraseña']").value.trim();
    let Ncontraseña = document.querySelector("input[name='Ncontraseña']").value.trim();

    if (!Acontraseña || !Ncontraseña) {
        mostrarAlerta("Todos los campos son obligatorios", "error");
        return false;
    }else{
        GuardarContrasenas(event);
        return true;
    }  
}


function validarCredenciales(event) {
    const fields = {
        usuario: document.querySelector("input[name='usuario']").value.trim(),
        nombre: document.querySelector("input[name='nombre']").value.trim(),
        apellido: document.querySelector("input[name='apellido']").value.trim(),
        correo: document.querySelector("input[name='correo']").value.trim()
    };

    // Validar que todos los campos son obligatorios
    if (Object.values(fields).some(field => !field)) {
        mostrarAlerta("Todos los campos son obligatorios", "error");
        return false;
    }

    // Validar el formato del correo
    const emailPattern = /@(gmail\.com|hotmail\.com)$/;
    if (!emailPattern.test(fields.correo)) {
        mostrarAlerta("El correo debe ser de tipo @gmail.com o @hotmail.com", "error");
        return false;
    }

    GuardarCredenciales(event);
    return true;
}

function validarEmpleado(event) {
    let usuarioEmp = document.querySelector("input[name='usuarioEmp']").value.trim();
    let nombreEmp = document.querySelector("input[name='nombreEmp']").value.trim();
    let apellidoEmp = document.querySelector("input[name='apellidoEmp']").value.trim();
    let correoEmp = document.querySelector("input[name='correoEmp']").value.trim();
    let contraseñaEmp = document.querySelector("input[name='contraseñaEmp']").value.trim();

    if (!usuarioEmp || !nombreEmp || !apellidoEmp || !correoEmp || !contraseñaEmp) {
        mostrarAlerta("Todos los campos son obligatorios", "error");
        return false;
    }else{
        GuardarEmpleado(event);
        return true;
    }
    
}

function validarProducto(event) {
    let idproducto = document.querySelector("input[name='idproducto']").value.trim();
    let nombreproducto = document.querySelector("input[name='nombreproducto']").value.trim();
    let descripcion = document.querySelector("textarea[name='descripcion']").value.trim();
    let precio = document.querySelector("input[name='precio']").value.trim();

    if (!idproducto || !nombreproducto || !descripcion || !precio) {
        mostrarAlerta("Todos los campos son obligatorios", "error");
        return false;
    }else{
        GuardarProducto(event);
        return true;
    }
}

function validarReceta(event) {
    let idreceta = document.querySelector("input[name='idreceta']").value.trim();
    let nombrereceta = document.querySelector("input[name='nombrereceta']").value.trim();
    let descripcionR = document.querySelector("textarea[name='descripcionR']").value.trim();

    if (!idreceta || !nombrereceta || !descripcionR ) {
        mostrarAlerta("Todos los campos son obligatorios", "error");
        return false;
    }else{
        GuardarReceta(event);
        return true;
    }
}

function validarDireccion(event) {
    let calle = document.querySelector("input[name='calle']").value.trim();
    let colonia = document.querySelector("input[name='colonia']").value.trim();
    let codPostal = document.querySelector("input[name='cod_postal']").value.trim();
    let numero = document.querySelector("input[name='numero']").value.trim();
    let estado = document.querySelector("input[name='estado']").value.trim();
    let ciudad = document.querySelector("input[name='ciudad']").value.trim();

    if (!calle || !colonia || !codPostal || !numero || !estado || !ciudad) {
        mostrarAlerta("Todos los campos de la dirección son obligatorios", "error");
        return false;
    } else {
        GuardarDireccion(event);  // Llamada a la función para guardar la dirección
        return true;
    }
}


function mostrarAlerta(mensaje,alerta) {
    Swal.fire({
        text: mensaje,
        icon: alerta
    });
}

function CondirmarEliminacionCuenta(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás deshacer esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('drop-account-').submit();
        } 
    });
}

function confirmarEliminacionPedido(index) {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',    
    confirmButtonText: 'Sí, eliminarlo',
    cancelButtonText: 'Cancelar'                                        
}).then((result) => {                            
    if (result.isConfirmed) {
        Swal.fire({
            title: "¡Eliminado!",
            text: "El producto ha sido eliminado con éxito.",
            icon: "success"
        }).then(() => {
            // Espera un tiempo para mostrar el mensaje y luego envía el formulario
            setTimeout(() => {
                document.getElementById('form-eliminarPed-' + index).submit();
            }, 500);   
        });                        
    } 
});
}

function confirmarEliminacion(event,index) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás deshacer esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-eliminar-' + index).submit();
        } 
    });
}

function confirmarEliminacionPed(event,index) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás deshacer esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-eliminarPed-' + index).submit();
        } 
    });
}

function confirmarEliminacionEmp(index) {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',    
    confirmButtonText: 'Sí, eliminarlo',
    cancelButtonText: 'Cancelar'                                        
}).then((result) => {                            
    if (result.isConfirmed) {
        Swal.fire({
            title: "¡Eliminado!",
            text: "El empleado ha sido eliminado con éxito.",
            icon: "success"
        }).then(() => {
            // Espera un tiempo para mostrar el mensaje y luego envía el formulario
            setTimeout(() => {
                document.getElementById('form-eliminaremp-' + index).submit();
            }, 500);   
        });                        
    } 
});
}

function confirmarEliminacionRec(index) {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',    
    confirmButtonText: 'Sí, eliminarlo',
    cancelButtonText: 'Cancelar'                                        
}).then((result) => {                            
    if (result.isConfirmed) {
        Swal.fire({
            title: "¡Eliminado!",
            text: "La receta ha sido eliminada con éxito.",
            icon: "success"
        }).then(() => {
            setTimeout(() => {
                document.getElementById('form-eliminarRec-' + index).submit();
            }, 500);   
        });                        
    } 
});
}

function confirmarEliminacionDir(index) {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',    
    confirmButtonText: 'Sí, eliminarlo',
    cancelButtonText: 'Cancelar'                                        
}).then((result) => {                            
    if (result.isConfirmed) {
        Swal.fire({
            title: "¡Eliminado!",
            text: "La dirección ha sido eliminada con éxito.",
            icon: "success"
        }).then(() => {
            // Espera un tiempo para mostrar el mensaje y luego envía el formulario
            setTimeout(() => {
                document.getElementById('form-eliminar-' + index).submit();
            }, 500);   
        });                        
    } 
});
}

function confirmarEliminacionCom(index) {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás deshacer esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',    
    confirmButtonText: 'Sí, eliminarlo',
    cancelButtonText: 'Cancelar'                                        
}).then((result) => {                            
    if (result.isConfirmed) {
        Swal.fire({
            title: "¡Eliminado!",
            text: "El Comentario ha sido eliminado con éxito.",
            icon: "success"
        }).then(() => {
            // Espera un tiempo para mostrar el mensaje y luego envía el formulario
            setTimeout(() => {
                document.getElementById('form-eliminarCom-' + index).submit();
            }, 500);   
        });                        
    } 
});
}

function ConfirmarEliminacionRes(event, id_resenia) {
    event.preventDefault();  // Evitar el envío del formulario

    // Confirmación con SweetAlert
    Swal.fire({
        title: '¿Está seguro que desea eliminar esta reseña?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, enviamos la solicitud AJAX
            fetch('../Controlador/Drop_resenia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'id_resenia': id_resenia  // Enviamos el id_resenia directamente
                }).toString()
            })
            .then(response => response.text())
            .then(data => {
                // Si se elimina correctamente, mostrar un mensaje y recargar la página
                Swal.fire({
                    title: 'Reseña eliminada',
                    icon: 'success',
                    text: 'La reseña ha sido eliminada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload(); // Recargar la página
                });
            })
            .catch(error => {
                // Si hay un error, mostrar un mensaje de error
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al eliminar la reseña.'
                });
                console.error('Error:', error);
            });
        }
    });
}



function GuardarCredenciales(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('update_credenciales-').submit();
        } 
    });
}

function GuardarDireccion(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar la dirección?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('create-dir-').submit();
        } 
    });
}

function GuardarContrasenas(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
                document.getElementById('update_contraseñas-').submit();
        } 
    });
}

function GuardarEmpleado(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
                document.getElementById('create_Empleado-').submit();
        } 
    });
}

function GuardarProducto(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
                document.getElementById('create_producto-').submit();
        } 
    });
}

function GuardarReceta(event) {
    event.preventDefault();
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',    
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
                document.getElementById('create_receta-').submit();
        } 
    });
}

function GuardarCambiosPastel(formValues) {
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Controlador/update_pastel.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formValues).toString()
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Producto actualizado',
                    icon: 'success',
                    text: 'El producto ha sido actualizado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al actualizar el producto.'
                });
                console.error('Error:', error);
            });
        }
    });
}

function GuardarCambiosReceta(formValues) {
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviamos los datos con fetch a update_receta.php
            fetch('../Controlador/update_receta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formValues).toString()
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Receta actualizada',
                    icon: 'success',
                    text: 'La receta ha sido actualizada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Recarga la página para ver los cambios
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al actualizar la receta.'
                });
                console.error('Error:', error);
            });
        }
    });
}

function GuardarCambiosDireccion(formValues) {
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Controlador/update_direccion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formValues).toString()
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Dirección actualizada',
                    icon: 'success',
                    text: 'La dirección ha sido actualizada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al actualizar la dirección.'
                });
                console.error('Error:', error);
            });
        }
    });
}

function GuardarCambiosComentarios(formValues) {
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza la solicitud fetch con los valores actualizados
            fetch('../Controlador/update_comentario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formValues).toString() // Convertir los datos a una cadena
            })
            .then(response => response.text())
            .then(data => {
                // Mostrar notificación de éxito y recargar la página
                Swal.fire({
                    title: 'Comentario actualizado',
                    icon: 'success',
                    text: 'El comentario ha sido actualizado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload(); // Recargar la página
                });
            })
            .catch(error => {
                // Mostrar notificación de error si ocurre un problema
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al actualizar el comentario.'
                });
                console.error('Error:', error);
            });
        }
    });
}


function GuardarCambiosResenias(formValues) {
    Swal.fire({
        title: '¿Desea guardar los cambios?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Controlador/update_resenia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formValues).toString()
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Reseña actualizada',
                    icon: 'success',
                    text: 'La reseña ha sido actualizada correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Hubo un problema al actualizar la reseña.'
                });
                console.error('Error:', error);
            });
        }
    });
}

async function ActualizarResenia(event, id_resenia) {
    event.preventDefault();

    // Aseguramos que estamos obteniendo el botón
    const button = event.target.closest('button');
    const resenia = button.getAttribute("data-resenia");

    const { value: formValues } = await Swal.fire({
        title: "Actualizar Reseña",
        html: `
            <textarea name="resenia" id="swal-resenia" class="swal2-input" rows="4" style="width: 100%;" required>${resenia}</textarea>
            <input type="hidden" name="id_resenia" value="${id_resenia}">
        `,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        preConfirm: () => {
            const reseniaValue = document.getElementById('swal-resenia').value.trim();
            if (!reseniaValue) {
                Swal.showValidationMessage('La reseña no puede estar vacía');
                return false; // Bloquea el envío si la validación falla
            }
            return {
                resenia: reseniaValue,
                id_resenia: id_resenia
            };
        }
    });

    if (formValues) {
        GuardarCambiosResenias(formValues);
    }
}

