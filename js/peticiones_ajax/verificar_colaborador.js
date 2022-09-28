// Función para verificar si el ID de un
// colaborador ya está registrado 
// en la basede datos.
function verificarColaborador(IDColaborador, IDCampo, tipoComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    if(tipoComportamiento == 1) {
                        document.getElementById(IDCampo).setCustomValidity("El ID introducido ya está registrado.");
                        document.getElementById(IDCampo).reportValidity();
                    }
                    else {
                        document.getElementById(IDCampo).setCustomValidity("");
                    }
                break;
                
                case "false":
                default:
                    switch(tipoComportamiento) {
                        case 1:
                            document.getElementById(IDCampo).setCustomValidity("");
                        break;  
                        
                        case 2:
                        default:
                            document.getElementById(IDCampo).setCustomValidity("El ID introducido no existe.");
                            document.getElementById(IDCampo).reportValidity();
                        break;
                    }
                break;
            }
        }
    }
    const url = "../funciones_adicionales/verificar_colaborador.php" +
    "?ID-colaborador=" + IDColaborador;

    xhttp.open("GET", url, true);
    xhttp.send();
}