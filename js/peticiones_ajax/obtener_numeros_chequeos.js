// Función para obtener todos los números
// de los chequeos correspondientes a un 
// colaborador y fecha específicos
function obtenerNumeroChequeos(IDColaborador, fechaChequeo, IDFechaChequeo, listaNumerosChequeos, numeroChequeo) {
    return new Promise(function(resolve, reject) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = () => {
            if(xhttp.readyState == 4 && xhttp.status == 200) {
                let campoFecha = document.getElementById(IDFechaChequeo);
                let arregloNumeros = xhttp.responseText.split("-").slice(0, -1);
                let selectorInicial = document.getElementById(listaNumerosChequeos);
                let selectorAuxiliar = selectorInicial.parentElement.lastElementChild.firstElementChild;
                let selectorModificado = selectorInicial.parentElement.lastElementChild.lastElementChild.lastElementChild.firstElementChild;
                selectorModificado.innerHTML = "";
                selectorInicial.innerHTML = "";

                if(arregloNumeros.length < 1) {
                    let opcion = document.createElement("option");
                    opcion.value = "-1";
                    opcion.innerHTML = "Chequeos no encontrados";
                    opcion.setAttribute("selected", "");
                    selectorInicial.appendChild(opcion);

                    opcion = document.createElement("button");
                    opcion.innerHTML = "Chequeos no encontrados";
                    opcion.classList.add("dropdown-item");
                    opcion.classList.add("active");
                    opcion.setAttribute("data-dselect-value", "-1");
                    opcion.setAttribute("type", "button");
                    opcion.setAttribute("onclick", "dselectUpdate(this, 'dselect-wrapper', 'form-select')");
                    selectorModificado.appendChild(opcion);
                    
                    selectorAuxiliar.setAttribute("data-dselect-text", "Chequeos no encontrados");
                    selectorAuxiliar.innerHTML = "Chequeos no encontrados";
                    campoFecha.setCustomValidity("El colaborador no tiene chequeos realizados en la fecha especificada.");
                }
                else {
                    arregloNumeros.forEach((numero, indice) => {
                        let numeroOpcion = parseInt(numero.trim());
                        let opcion = document.createElement("option");
                        opcion.value = numeroOpcion;
                        opcion.innerHTML = "Chequeo no. " + (indice + 1);

                        if(numeroChequeo == -1) {
                            if(indice == 0) {
                                opcion.setAttribute("selected", "");
                            }
                        }
                        else if(numeroOpcion == numeroChequeo) {
                            opcion.setAttribute("selected", "");
                        } 
                        selectorInicial.appendChild(opcion);

                        opcion = document.createElement("button");
                        opcion.innerHTML = "Chequeo no. " + (indice + 1);
                        opcion.classList.add("dropdown-item");

                        if(numeroChequeo == -1) {
                            if(indice == 0) {
                                opcion.classList.add("active");
                                selectorAuxiliar.setAttribute("data-dselect-text", "Chequeo no. " + (indice + 1));
                                selectorAuxiliar.innerHTML = "Chequeo no. " + (indice + 1);
                            }
                        }
                        else if(numeroOpcion == numeroChequeo) {
                            opcion.classList.add("active");
                            selectorAuxiliar.setAttribute("data-dselect-text", "Chequeo no. " + (indice + 1));
                            selectorAuxiliar.innerHTML = "Chequeo no. " + (indice + 1);
                        } 
    
                        opcion.setAttribute("data-dselect-value", numeroOpcion);
                        opcion.setAttribute("type", "button");
                        opcion.setAttribute("onclick", "dselectUpdate(this, 'dselect-wrapper', 'form-select')");
                        selectorModificado.appendChild(opcion);
                        campoFecha.setCustomValidity("");
                    });
                }
                resolve();
            }
        }
        const url = "../../funciones_adicionales/obtener_numeros_chequeos.php" +
        "?ID-colaborador=" + IDColaborador + "&fecha-chequeo=" + fechaChequeo;

        xhttp.open("GET", url, true);
        xhttp.send();
    });
}