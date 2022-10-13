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
        text: "¿Está seguro de que realmente desea agregar al colaborador?"
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
        text: "¿Está seguro de que realmente desea modificar el colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// eliminación de un colaborador.
function confirmarEliminacionColaborador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Eliminación de un colaborador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea eliminar al colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar el
// desbloqueo de un colaborador.
function confirmarDesbloqueoColaborador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Desbloqueo de un colaborador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea desbloquear el ID del colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar el
// bloqueo de un colaborador.
function confirmarBloqueoColaborador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Bloqueo de un colaborador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea bloquear el ID del colaborador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar el
// registro de una contingencia.
function confirmarRegistroContingencia(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Registro de una contingencia",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea registrar la contingencia?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// modificación de una contingencia.
function confirmarModificacionContingencia(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Modificación de una contingencia",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea modificar la contingencia?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// eliminación de una contingencia.
function confirmarEliminacionContingencia(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Eliminación de una contingencia",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea eliminar la contingencia?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// adición de un administrador.
function confirmarRegistroAdministrador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Adición de un administrador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea agregar al administrador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// modificación de un administrador.
function confirmarModificacionAdministrador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Modificación de un administrador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea modificar al administrador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// eliminación de un administrador.
function confirmarEliminacionAdministrador(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Eliminación de un administrador",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea eliminar al administrador?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}

// Función para confirmar la
// adición de un chequeo.
function confirmarRegistroChequeo(evento) {
    evento.preventDefault();

    Swal.fire({
        icon: "question",
        title: "Adición de un chequeo",
        showConfirmButton: true,
        confirmButtonText: "Sí",
        showDenyButton: true,
        text: "¿Está seguro de que realmente desea agregar el chequeo?"
    }).then((resultado) => {
        if(resultado.isConfirmed) {
            evento.target.submit();
        }
    });
}