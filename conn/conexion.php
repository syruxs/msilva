<?php
$host = 'localhost';
$usuario = 'u187168776_msilva';
$contraseña = '5#ZrP@1*o!9E7%m6q2&s';
$base_de_datos = 'u187168776_parking';

$conn= mysqli_connect($host, $usuario, $contraseña, $base_de_datos);

if (!$conn) {
    die("La conexión falló: " . mysqli_connect_error());
}

?>