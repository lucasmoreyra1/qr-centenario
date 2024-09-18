<?php
require 'conexion.php';
$id_invitado = isset($_POST['id']) ? (int)$_POST['id'] : 0;


$sql = "SELECT invitacion_uso FROM invitados WHERE id_invitado = :id_invitado";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    ':id_invitado' => $id_invitado
    ]);
    $uso = $stmt->fetch();

echo $uso['invitacion_uso'];
?>