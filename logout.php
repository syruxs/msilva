<?php
session_start(); // Inicia la sesión (asegúrate de que esta línea esté presente en todas las páginas que utilizan sesiones)

// Realiza el logout
session_destroy();

// Redirecciona a la página de inicio de sesión u a otra página de tu elección
header("Location: index.php");
exit();
?>