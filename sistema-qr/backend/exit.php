<?php
session_start(); // Inicia la sesión

unset($_SESSION['user_id']);
unset($_SESSION['username']);

header('Location: login.php');

?>