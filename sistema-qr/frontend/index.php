<?php
session_start();

require '../backend/index.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // El usuario no está logueado, redirige a la página de login o muestra un mensaje
    header('Location: login.php'); // Redirige a la página de login
    exit(); // Detiene la ejecución del script
}



// Incluimos la vista
include 'vista_invitado.php';
