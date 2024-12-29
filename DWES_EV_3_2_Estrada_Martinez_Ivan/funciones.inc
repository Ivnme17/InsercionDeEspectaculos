<?php
function validarCadena($cadena) {
    $cadena = trim($cadena); // Eliminar espacios en blanco al inicio y al final
    $cadena = stripslashes($cadena); // Eliminar barras invertidas
    $cadena = htmlspecialchars($cadena); // Convertir caracteres especiales a entidades HTML
    return $cadena;
}

   function calificacionMarcada($estrellas){
            if($estrellas == 0 || $estrellas == 1 ||$estrellas == 2 ||$estrellas == 3 ||$estrellas == 4 ||$estrellas == 5){
                $estrellasValidas = $estrellas;
            }else{
                $estrellasValidas= false;
            }
          return intval($estrellasValidas);
              
        }
        