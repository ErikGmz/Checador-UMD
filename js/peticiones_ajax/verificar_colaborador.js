// Función para verificar si el ID de un
// colaborador ya está registrado 
// en la base de datos.
function verificarColaborador(IDColaborador, IDCampo, tipoComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("El ID introducido ya está registrado.");
                        break;
                
                        case 2:
                            if(document.getElementById("anterior-ID-colaborador").value == IDColaborador) {
                                document.getElementById(IDCampo).setCustomValidity("");
                            }
                            else {
                                document.getElementById(IDCampo).setCustomValidity("El ID introducido ya está registrado.");
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
                            document.getElementById(IDCampo).setCustomValidity("El ID introducido es inexistente.");
                        break;
                    }
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_colaborador.php" +
    "?ID-colaborador=" + IDColaborador;

    xhttp.open("GET", url, true);
    xhttp.send();
}