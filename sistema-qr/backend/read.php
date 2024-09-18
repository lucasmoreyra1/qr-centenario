<?php

try {
    require 'vendor/autoload.php'; // Asegúrate de tener la librería PHPSpreadsheet instalada
    require 'conexion.php';
} catch (\Throwable $th) {
    throw new Exception("Error al incluir archivos");
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

try {
    // Verificar que el archivo existe en la ruta especificada
    $archivoExcel = '../../Invitados.xlsx'; // Reemplazar por la ruta correcta
    if (!file_exists($archivoExcel)) {
        throw new Exception("El archivo Excel no se encuentra en la ruta: $archivoExcel");
    }

    // Cargar el archivo Excel
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

        // Verificar si los datos están siendo leídos correctamente
        if (empty($data)) {
            throw new Exception("Error al leer los datos en la fila: " . $fila->getRowIndex());
        }

        // Extraer los valores de las columnas
        $mesa = $data[0];
        $nombre = $data[1];
        $promocion = $data[2] ?? null; // Permitir valores nulos
        $especialidad = $data[3] ?? null; // Permitir valores nulos

        // Verificar si el nombre está vacío
        if (empty($nombre)) {
            echo "Fila ignorada, ya que el nombre está vacío en la fila: " . $fila->getRowIndex() . "<br>";
            continue;
        }

        // Verificar si la conexión a la base de datos está establecida
        if (!$pdo) {
            throw new Exception("Error al conectar con la base de datos.");
        }

        // Verificar si se está ejecutando correctamente la inserción en la base de datos
        $sql = "INSERT INTO invitados (id_mesa, nombre, promocion, especialidad, invitacion_uso)
                VALUES (:id_mesa, :nombre, :promocion, :especialidad, 0)";
        $stmt = $pdo->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta SQL: " . implode(" ", $pdo->errorInfo()));
        }

        // Ejecutar la consulta
        $stmt->execute([
            ':id_mesa' => $mesa,
            ':nombre' => $nombre,
            ':promocion' => $promocion,
            ':especialidad' => $especialidad,
        ]);

        // Verificar si la consulta fue exitosa
        if ($stmt->rowCount() === 0) {
            throw new Exception("Error al insertar los datos de la fila: " . $fila->getRowIndex());
        }
    }

    echo "Datos insertados correctamente en la base de datos.";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


