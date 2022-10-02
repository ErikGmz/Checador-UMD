// Función para verificar que los valores de 
// la contraseña y su confirmación sean iguales.
function verificarClavesIguales(claveActual, confirmacionClave) {
    let clave = document.getElementById(claveActual);
    let confirmacion = document.getElementById(confirmacionClave);

    if(clave.value != confirmacion.value) {
        clave.setCustomValidity("La contraseña y confirmación introducidas deben ser iguales.");
        confirmacion.setCustomValidity("La contraseña y confirmación introducidas deben ser iguales.");
    }
    else {
        clave.setCustomValidity("");
        confirmacion.setCustomValidity("");
    }
}