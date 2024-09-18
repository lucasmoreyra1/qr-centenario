<?php

require 'vendor/autoload.php'; // Asegúrate de tener la librería de QR instalada
require 'conexion.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

try {

    // Seleccionar todos los invitados
    $sql = "SELECT id_invitado, id_mesa,  nombre, invitacion_uso FROM invitados";
    $stmt = $pdo->query($sql);
    $invitados = $stmt->fetchAll();

    // Ruta donde se guardarán los códigos QR
    $carpetaQr = __DIR__ . '/qrcodes/'; // Carpeta "qrcodes" en la raíz del proyecto

    // Verifica si la carpeta existe, si no, la crea
    if (!is_dir($carpetaQr)) {
        mkdir($carpetaQr, 0755, true);
    }

    foreach ($invitados as $invitado) {
        // Generar el enlace único para cada invitado
        // $enlace = "localhost/AD%20HONOREM/sistema%20qr/frontend/invitado.php?id=" . $invitado['id_invitado'];

        $invitado_data = json_encode([
            "id" => $invitado['id_invitado'],
            "mesa" => $invitado['id_mesa'],
            "nombre" => $invitado['nombre'],
            "uso" => $invitado['invitacion_uso']
        ]);
        $enlace = $invitado_data;

        // Crear el QR para el enlace
        $qrCode = QrCode::create($enlace);
        $writer = new PngWriter();
        $nombreArchivo = $invitado['nombre'] ." - " . $invitado['id_mesa'] . '.png';

        // Guardar el código QR en la carpeta "qrcodes"
        $rutaCompleta = $carpetaQr . $nombreArchivo;
        $result = $writer->write($qrCode);
        $result->saveToFile($rutaCompleta);

        // También puedes mostrar el QR en la página (en base64)
/*         $qrDataUri = $result->getDataUri();
        echo "<h3>{$invitado['nombre']}</h3>";
        echo "<img src='{$qrDataUri}' alt='QR Code'><br><br>"; */
    }

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
