<?php
include_once("conn/conexion.php");
// Recibir los valores del formulario de registro
$username = "dugalde";
$password = "sandy";
// Generar el hash de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar los datos en la base de datos
$stmt = $conn->prepare("INSERT INTO user (user, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $passwordHash);
$stmt->execute();
$stmt->close();
?>