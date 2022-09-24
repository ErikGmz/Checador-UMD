<?php
    # Función para rellenar el cero izquierdo
    # de un número menor a 10.
    function completar_cero($numero) {
        if($numero < 10) {
            return str_pad($numero, 2, '0', STR_PAD_LEFT);
        }
        else {
            return (string)$numero;
        }
    }
?>