<?php


require 'vendor/autoload.php'; // Asegúrate de tener la librería PHPSpreadsheet instalada
require 'conexion.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

try {
    // Cargar el archivo Excel
    $archivoExcel = '../../Invitados.xlsx'; // Reemplazar por la ruta correcta
    $spreadsheet = IOFactory::load($archivoExcel);
    $worksheet = $spreadsheet->getActiveSheet();
    $esPrimeraFila = true;

    // Recorrer las filas del archivo Excel
    foreach ($worksheet->getRowIterator() as $fila) {
        if ($esPrimeraFila) {
            $esPrimeraFila = false;
            continue;
        }

        $cellIterator = $fila->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // Permitir iterar sobre celdas vacías

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue(); // Obtener el valor de cada celda
        }

        // Extraer los valores de las columnas
        $mesa = $data[0];
        $nombre = $data[1];
        $promocion = $data[2] ?? null; // Permitir valores nulos
        $especialidad = $data[3] ?? null; // Permitir valores nulos

        // Si el nombre está vacío, ignoramos la fila
        if (empty($nombre)) {
            continue;
        }

        // Insertar en la base de datos
        $sql = "INSERT INTO invitados (id_mesa, nombre, promocion, especialidad, invitacion_uso)
                VALUES (:id_mesa, :nombre, :promocion, :especialidad, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_mesa' => $mesa,
            ':nombre' => $nombre,
            ':promocion' => $promocion,
            ':especialidad' => $especialidad,
        ]);
    }

    echo "Datos insertados correctamente en la base de datos.";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}