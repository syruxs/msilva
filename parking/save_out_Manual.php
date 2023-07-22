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
    // Obtener el valor del parámetro 'accion'
    $accion = $_POST['accion'];
    $Id = $_POST['Id'];
    $abono = $_POST['abono'];
    $abono = str_replace('.', '', $abono);
    $abono = intval($abono);
    $total = $_POST['total'];
    $total = str_replace('.', '', $total);
    $total = intval($total);
    $moroso = $_POST['moroso'];
    $ob = $_POST['ob'];
    $cli = $_POST['cli'];
    $nameCliente = $_POST['selectCliente'];
    if($nameCliente == "0"){
        $nameCliente = "";
    }if($cli == "NO"){
        $nameCliente = "";
    }

    $fechaSalida = $_POST['dateSalida'];
    
    $sistema = $_POST['sistema'];

    // Realizar las acciones según el valor de 'accion'
    if ($accion === 'modificar') {
        // Acciones para guardar la modificación
        $sql = "UPDATE parking SET `OBSER` = ?, `ABONO` = ?, `MOROSO` = ?, `CLI` = ?, `CLIENTE` = ?  WHERE id_parking = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $ob, $abono, $moroso, $cli, $nameCliente, $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        $response = array(
            'status' => 'success',
            'message' => 'Datos han sido modicados correctamente.',
            'resetForm' => true
        );
        echo json_encode($response);

    } elseif ($accion === 'salida') {
        // Acciones para guardar la salida
        $estado = "INACTIVO";

        $sql = "UPDATE parking SET `OBSER` = ?, `ABONO` = ?, `MOROSO` = ? , `VALOR_SISTEM` = ? , `TOTAL` = ? , `ESTADO` = ? , `FECHA_SALIDA` = ? , `USER_SALIDA` = ?  WHERE id_parking = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $ob, $abono, $moroso, $sistema, $total, $estado, $fechaSalida, $username, $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        
        $response = array(
            'status' => 'success',
            'message' => 'La salida se ha registrado correctamente.',
            'link' => 'comprobante.php?ID=' .$Id  
        );

        // Devolver la respuesta en formato JSON |'comprobante.php?id=' . $Id
        echo json_encode($response);
        
    } else {
        // Acciones para otros casos

        // Ejemplo de respuesta en formato JSON
        $response = array(
            'status' => 'error',
            'message' => 'Acción desconocida.'
        );

        // Devolver la respuesta en formato JSON
        echo json_encode($response);
    }
}
?>
