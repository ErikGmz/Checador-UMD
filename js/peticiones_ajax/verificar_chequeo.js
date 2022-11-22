// Función para verificar si un
// chequeo ya está registrado
// en la base de datos y controlar su
// comportamiento en la aplicación.
function verificarChequeo(IDColaborador, fechaChequeo, IDCampo, 
IDCampoTextoHoraInicial, IDCampoTextoHoraFinal, IDCampoHoraInicial, 
IDCampoHoraFinal, tipoComportamiento, numeroChequeo) {
    const xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText.split("-")[0].trim()) {
                case "true":
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("No se puede agregar un nuevo chequeo si el anterior no fue completado.");

                            document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                            document.getElementById(IDCampoHoraInicial).value = null;
                            document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                            document.getElementById(IDCampoHoraFinal).value = null;
                        break;
                
                        case 2:
                            document.getElementById(IDCampo).setCustomValidity("El chequeo con el colaborador, fecha y número especificados es inexistente.");

                            document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                            document.getElementById(IDCampoHoraInicial).value = null;
                            document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                            document.getElementById(IDCampoHoraFinal).value = null;
                        break;

                        default:
                            document.getElementById(IDCampo).setCustomValidity("");

                            document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                            document.getElementById(IDCampoHoraInicial).value = "08:00:00";
                            document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                            document.getElementById(IDCampoHoraFinal).value = "12:00:00";
                        break;
                    }
                break;

                default:
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("");
                            if(xhttp.responseText.split("-")[1] != undefined) {
                                horaReferencia = xhttp.responseText.split("-")[1].trim();
                                parametrosHora = horaReferencia.split(":");
                                fechaHora = new Date(2021, 01, 01, parametrosHora[0], parametrosHora[1], (parametrosHora[2] == undefined) ? 0 : parametrosHora[2]);

                                horaCondicion = fechaHora.toTimeString().split(' ')[0];
                                fechaHora.setHours(fechaHora.getHours() + 2);
                                if(fechaHora.getDate() > 1) {
                                    horaFinalCondicion = "23:59:59";
                                }
                                else {
                                    horaFinalCondicion = fechaHora.toTimeString().split(' ')[0];
                                }
                                
                                document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                                " 24:00:00 horas. " + "Debe ser mayor o igual a " + horaReferencia + "."

                                document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                                " 24:00:00 horas. " + "Debe ser mayor a " + horaReferencia + " y estrictamente mayor a la hora de entrada."

                                document.getElementById(IDCampoHoraInicial).setAttribute("min", horaCondicion);
                                document.getElementById(IDCampoHoraInicial).value = horaCondicion;
                                document.getElementById(IDCampoHoraFinal).setAttribute("min", horaCondicion);
                                document.getElementById(IDCampoHoraFinal).value = horaFinalCondicion;
                            }
                            else {
                                document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                                " 24:00:00 horas."

                                document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                                " 24:00:00 horas."

                                document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                                document.getElementById(IDCampoHoraInicial).value = "08:00:00";
                                document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                                document.getElementById(IDCampoHoraFinal).value = "12:00:00";
                            }
                        break;

                        case 2:
                            document.getElementById(IDCampo).setCustomValidity("");
                            let mensaje_hora_inicial = "";
                            let mensaje_hora_final = "";

                            let horaFinalChequeoAnterior = xhttp.responseText.split("-")[1].trim();
                            let horaFinalChequeoActual = xhttp.responseText.split("-")[3].trim();
                            let horaInicialChequeoActual = xhttp.responseText.split("-")[4].trim();
                            let horaInicialChequeoPosterior = xhttp.responseText.split("-")[6].trim();
                        
                            if(horaFinalChequeoAnterior && horaFinalChequeoAnterior.length > 0) {
                                if(!horaInicialChequeoPosterior && horaInicialChequeoPosterior.length <= 0) {
                                    mensaje_hora_inicial += " Debe ser mayor o igual a " + horaFinalChequeoAnterior + ".";
                                    mensaje_hora_final += " Debe ser mayor o igual a " + horaFinalChequeoAnterior + " y estrictamente mayor a la hora de entrada.";
                                }
                                else {
                                    mensaje_hora_inicial += " Debe ser mayor o igual a " + horaFinalChequeoAnterior + " ";
                                    mensaje_hora_final += " Debe ser mayor o igual a " + horaFinalChequeoAnterior;
                                }

                                document.getElementById(IDCampoHoraInicial).setAttribute("min", horaFinalChequeoAnterior);
                                document.getElementById(IDCampoHoraFinal).setAttribute("min", horaFinalChequeoAnterior);
                            }
                            else {
                                document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                                document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                            }

                            if(horaInicialChequeoActual && horaInicialChequeoActual.length > 0) {
                                document.getElementById(IDCampoHoraInicial).value = horaInicialChequeoActual;
                                document.getElementById(IDCampoHoraFinal).value = horaFinalChequeoActual;
                            }
                            else {
                                document.getElementById(IDCampoHoraInicial).value = null;
                                document.getElementById(IDCampoHoraFinal).value = null;
                            }

                            if(horaInicialChequeoPosterior && horaInicialChequeoPosterior.length > 0) {
                                if(!horaFinalChequeoAnterior && horaFinalChequeoAnterior.length <= 0) {
                                    mensaje_hora_inicial += " Debe ser menor o igual a " + horaInicialChequeoPosterior + ".";
                                    mensaje_hora_final += " Debe ser menor o igual a " + horaInicialChequeoPosterior + " y estrictamente mayor a la hora de entrada.";  
                                }
                                else {
                                    mensaje_hora_inicial += "y menor o igual a " + horaInicialChequeoPosterior + ".";
                                    mensaje_hora_final += ", menor o igual a " + horaInicialChequeoPosterior + " y estrictamente mayor a la hora de entrada.";    
                                }

                                document.getElementById(IDCampoHoraInicial).setAttribute("max", horaInicialChequeoPosterior);
                                document.getElementById(IDCampoHoraFinal).setAttribute("max", horaInicialChequeoPosterior);
                            }
                            else {
                                document.getElementById(IDCampoHoraInicial).removeAttribute("max");
                                document.getElementById(IDCampoHoraFinal).removeAttribute("max");
                            }

                            document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                            " 24:00:00 horas." + mensaje_hora_inicial;
                            document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                            " 24:00:00 horas." + mensaje_hora_final;
                        break;

                        default:
                            document.getElementById(IDCampo).setCustomValidity("El chequeo con el colaborador, fecha y número especificados es inexistente.");

                            document.getElementById(IDCampoTextoHoraInicial).innerHTML = "Campo obligatorio. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoTextoHoraFinal).innerHTML = "Campo opcional. Formato de 00:00:00 a " +
                            " 24:00:00 horas."

                            document.getElementById(IDCampoHoraInicial).removeAttribute("min");
                            document.getElementById(IDCampoHoraInicial).value = null;
                            document.getElementById(IDCampoHoraFinal).removeAttribute("min");
                            document.getElementById(IDCampoHoraFinal).value = null;
                        break;
                    }
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_chequeo.php" +
    "?ID-colaborador=" + IDColaborador + "&fecha-chequeo=" + fechaChequeo
    + "&tipo-operacion=" + tipoComportamiento + "&numero-chequeo=" + numeroChequeo;

    xhttp.open("GET", url, true);
    xhttp.send();
}