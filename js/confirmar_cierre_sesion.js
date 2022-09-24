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