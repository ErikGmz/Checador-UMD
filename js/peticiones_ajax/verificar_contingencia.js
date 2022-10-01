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
                    if(tipoComportamiento == 1) {
                        document.getElementById(IDCampo).setCustomValidity("Ya existe una contingencia para el colaborador en la fecha especificada.");
                    }
                    else {
                        document.getElementById(IDCampo).setCustomValidity("");
                    }
                break;
                
                default:
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("");
                        break;  
                        
                        case 2:
                        default:
                            document.getElementById(IDCampo).setCustomValidity("La contingencia del colaborador y fechas especificados es inexistente.");
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