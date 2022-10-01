// Función para verificar si una
// contingencia ya está registrada
// en la base de datos.
function verificarContingencia(IDColaborador, fechaRegistro, IDCampo, tipoComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    if(tipoComportamiento == 1) {
                        document.getElementById(IDCampo).setCustomValidity("Ya existe una contingencia para el colaborador en la fecha especificada.");
                    }
                    else {
                        if(document.getElementById("anterior-ID-colaborador").value == IDColaborador 
                        && document.getElementById("fecha-anterior").value == fechaRegistro) {
                            document.getElementById(IDCampo).setCustomValidity("");
                        }
                        else {
                            document.getElementById(IDCampo).setCustomValidity("Ya existe una contingencia para el colaborador en la fecha especificada.");
                        }
                    }
                break;

                default:
                    document.getElementById(IDCampo).setCustomValidity("");
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_contingencia.php" +
    "?ID-colaborador=" + IDColaborador + "&fecha-registro=" + fechaRegistro;

    xhttp.open("GET", url, true);
    xhttp.send();
}