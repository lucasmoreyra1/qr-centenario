<?php
// archivo: conexion.php

// Datos de conexión
$host = 'localhost';
$dbname = 'invitados';
$usuario = 'root';
$password = '';
$charset = 'utf8';

// Configurar el DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    // Crear una instancia de PDO para conectarse a la base de datos
    $pdo = new PDO($dsn, $usuario, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Modo de error: lanzar excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Modo de obtención de resultados: array asociativo
    ]);
    
    // Puedes imprimir un mensaje de conexión exitosa (opcional)
    // echo "Conexión exitosa a la base de datos";

} catch (PDOException $e) {
    // Manejo de errores
    die('Error en la conexión: ' . $e->getMessage());
}
