<?php
require 'conexion.php';

$id_invitado = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$sql = "UPDATE invitados SET invitacion_uso = 1 WHERE id_invitado = :id_invitado";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    ':id_invitado' => $id_invitado
    ]);


?>