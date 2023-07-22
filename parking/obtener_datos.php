<?php
include_once("../conn/conexion.php"); error_reporting(0);
// Realiza las consultas o lógica necesaria para obtener los valores de camiones en patio y disponibilidad
$patio = mysqli_query($conn, "SELECT COUNT(*) FROM `parking` WHERE ESTADO = 'ACTIVO'");
$row = mysqli_fetch_row($patio);
$stock = $row[0];

$capacidad = "300";
$disponibilidad = $capacidad - $stock;

$response = array(
    'camionesEnPatio' => $stock,
    'disponibilidad' => $disponibilidad
);

echo json_encode($response);
?>
