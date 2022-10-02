// Función para verificar si el ID de un
// administrador ya está registrado 
// en la base de datos.
function verificarAdministrador(IDAdministrador, IDCampo, tipoComportamiento) {
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
                            if(document.getElementById("anterior-ID-administrador").value == IDAdministrador) {
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
    const url = "../../funciones_adicionales/verificar_administrador.php" +
    "?ID-administrador=" + IDAdministrador;

    xhttp.open("GET", url, true);
    xhttp.send();
}