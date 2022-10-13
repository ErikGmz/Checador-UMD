// Función para verificar si un
// chequeo ya está registrado
// en la base de datos.
function verificarChequeo(IDColaborador, fechaChequeo, IDCampo, tipoComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("Ya existe un chequeo para el colaborador en la fecha especificada.");
                        break;
                
                        case 2:
                            if(document.getElementById("anterior-ID-colaborador").value == IDColaborador 
                            && document.getElementById("fecha-anterior").value == fechaChequeo) {
                                document.getElementById(IDCampo).setCustomValidity("");
                            }
                            else {
                                document.getElementById(IDCampo).setCustomValidity("Ya existe un chequeo para el colaborador en la fecha especificada.");
                            }
                        break;

                        default:
                            document.getElementById(IDCampo).setCustomValidity("");
                        break;
                    }
                break;

                default:
                    switch(tipoComportamiento) {
                        case 1:
                        case 2:
                            document.getElementById(IDCampo).setCustomValidity("");
                        break;

                        default:
                            document.getElementById(IDCampo).setCustomValidity("El chequeo con el colaborador y fecha especificados es inexistente.");
                        break;
                    }
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_chequeo.php" +
    "?ID-colaborador=" + IDColaborador + "&fecha-chequeo=" + fechaChequeo;

    xhttp.open("GET", url, true);
    xhttp.send();
}