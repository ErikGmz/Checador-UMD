// Función para verificar si el ID de un
// colaborador ya está registrado 
// en la basede datos.
function verificarColaborador(IDColaborador, IDCampo, invertirComportamiento) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    if(!invertirComportamiento) {
                        document.getElementById(IDCampo).setCustomValidity("El ID introducido ya está registrado.");
                    }
                    else {
                        document.getElementById(IDCampo).setCustomValidity("");
                    }
                break;
                
                case "false":
                default:
                    if(invertirComportamiento) {
                        document.getElementById(IDCampo).setCustomValidity("El ID introducido no existe.");
                    }
                    else {
                        document.getElementById(IDCampo).setCustomValidity("");
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