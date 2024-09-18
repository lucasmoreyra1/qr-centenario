<?php
/*     require 'read.php';
    require 'generar_qr.php'; */

    verificarCarpeta(__DIR__ . '/qrcodes/');


    function verificarCarpeta($rutaCarpeta) {
        // Verificar si la carpeta existe
        if (!is_dir($rutaCarpeta)) {
            // Si la carpeta no existe, incluir los archivos
                // require 'read.php';
                require 'generar_qr.php';

        }
    }

?>