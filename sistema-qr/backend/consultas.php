<?php
// session_start();
require 'conexion.php'; // Conexión a la base de datos

// Consulta para obtener la información de los invitados
$sql = "SELECT * FROM invitados";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$invitados = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* // Comprobar si se encontró el invitado
if (!$invitados) {
    $mensaje = "Sin invitados.";
} */
// Retornar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($invitados);
?>
