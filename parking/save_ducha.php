<?php
session_start();
error_reporting(0);

include_once("../conn/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: ../index.php");
    exit();
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $showerId = $_POST['showerId'];
    $totalDucha = $_POST['totalDucha'];
    $accion = $_POST['accion'];

    $timezone = new DateTimeZone('America/Santiago');
    $fechaSalida = new DateTime('now', $timezone);
    $fechaSalida = $fechaSalida->format('Y-m-d H:i:s');

    $estado = "INACTIVO";

    if ($accion === 'pagar') {
        $sql = "UPDATE parking SET `VALOR_SISTEM` = ? , `TOTAL` = ? , `ESTADO` = ? , `FECHA_SALIDA` = ? , `USER_SALIDA` = ? WHERE id_parking = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $totalDucha, $totalDucha, $estado, $fechaSalida, $username, $showerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        $response = array(
            'status' => 'success',
            'message' => 'El expediente ha sido sacado correctamente.'
        );
        echo json_encode($response);

    } elseif ($accion === 'ingresar') {
        $ducha = "no";
        $obser = "Pasa a parqueadero";
        $sql = "UPDATE parking SET `DUCHA` = ? , `OBSER` = ?  WHERE id_parking = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $ducha, $obser, $showerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        $response = array(
            'status' => 'success',
            'message' => 'El expediente ha sido ingresado a parqueadero correctamente.'
        );
        echo json_encode($response);
        //, 
    }
}

?>