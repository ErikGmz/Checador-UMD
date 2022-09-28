// Función para verificar que la hora
// de entrada de un colaborador no sea
// mayor que su tiempo de salida.
function verificarRangosHoras(horaEntrada, horaSalida, campoActual) {
    let campoEntrada = document.getElementById(horaEntrada);
    let campoSalida = document.getElementById(horaSalida);

    let tiempoEntrada = campoEntrada.value.split(":");
    let tiempoSalida = campoSalida.value.split(":");

    let referenciaEntrada = new Date(2021, 01, 01, tiempoEntrada[0], tiempoEntrada[1], 0).getTime();
    let referenciaSalida = new Date(2021, 01, 01, tiempoSalida[0], tiempoSalida[1], 0).getTime();

    if(referenciaEntrada >= referenciaSalida) {
        campoEntrada.setCustomValidity("La hora de entrada no puede ser mayor o igual a la de salida.");
        campoSalida.setCustomValidity("La hora de salida no puede ser menor o igual a la de salida.");
        campoEntrada
        if(campoActual == 1) {
            campoEntrada.reportValidity();
        }
        else {
            campoSalida.reportValidity();
        }
    }
    else {
        campoEntrada.setCustomValidity("");
        campoSalida.setCustomValidity("");
    }
}