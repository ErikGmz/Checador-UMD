// Función para verificar si una
// contingencia ya está registrada
// en la base de datos.
function verificarContingencia(IDColaborador, fechaRegistro, IDCampo, tipoComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            console.log(xhttp.response);
            switch(xhttp.responseText) {
                case "true":
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("Ya existe una contingencia para el colaborador en la fecha especificada.");
                        break;
                
                        case 2:
                            if(document.getElementById("anterior-ID-colaborador").value == IDColaborador 
                            && document.getElementById("fecha-anterior").value == fechaRegistro) {
                                document.getElementById(IDCampo).setCustomValidity("");
                            }
                            else {
                                document.getElementById(IDCampo).setCustomValidity("Ya existe una contingencia para el colaborador en la fecha especificada.");
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
                            document.getElementById(IDCampo).setCustomValidity("La contingencia con el colaborador y fecha especificados es inexistente.");
                        break;
                    }
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_contingencia.php" +
    "?ID-colaborador=" + IDColaborador + "&fecha-registro=" + fechaRegistro;
    console.log(url);

    xhttp.open("GET", url, true);
    xhttp.send();
}