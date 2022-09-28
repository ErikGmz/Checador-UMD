// Función para confirmar el cierre
// de sesión de un administrador.
function confirmarCierreSesion(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Cierre de sesión",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea cerrar sesión?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// adición de un colaborador.
function confirmarRegistroColaborador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Adición de un colaborador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente agregar al colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// modificación de un colaborador.
function confirmarModificacionColaborador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Modificación de un colaborador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente modificar el colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}