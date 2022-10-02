// Función para verificar si la contraseña
// de un administrador concuerda con la
// registrada en la base de datos.
function verificarClaveOriginal(claveOriginal, IDCampo, IDAdministrador) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            switch(xhttp.responseText) {
                case "true":
                    document.getElementById(IDCampo).setCustomValidity("");
                break;

                default:
                    document.getElementById(IDCampo).setCustomValidity("La contraseña introducida no concuerda con la registrada.");
                break;
            }
        }
    }
    const url = "../../funciones_adicionales/verificar_clave_original.php" +
    "?ID-administrador=" + IDAdministrador + "&clave-original=" + claveOriginal;

    xhttp.open("GET", url, true);
    xhttp.send();
}