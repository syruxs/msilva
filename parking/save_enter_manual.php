<?php
session_start();
include_once("../conn/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: ../index.php");
    exit();
} 

    // Verificar si se recibieron los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Obtener los valores de los campos del formulario
        $tracto = mysqli_real_escape_string($conn, $_POST['tracto']);
        $semi = mysqli_real_escape_string($conn, $_POST['semi']);
        $ducha = mysqli_real_escape_string($conn, $_POST['ducha']);
        $numero = mysqli_real_escape_string($conn, $_POST['duchas']);
        $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
        $carga = mysqli_real_escape_string($conn, $_POST['radio_select']);
        $chofer = mysqli_real_escape_string($conn, $_POST['name']);
        $fone = mysqli_real_escape_string($conn, $_POST['fone']);
        $obs = mysqli_real_escape_string($conn, $_POST['obs']);
        $cliente = mysqli_real_escape_string($conn, $_POST['opcionesCliente']);

        if($cliente == "0"){
            $cliente = "";
            $cli = "NO";
        }else {
            $cli = "SI";
        }
        // Obtener la fecha y hora actual en la zona horaria de Santiago de Chile
        $fechaIngreso = $_POST['fechaEntrada'];
        
        $userIngeso = $username;
        $abono = "0";
        $moroso = "NO";
        $estado = "ACTIVO";

        $buscarTracto = mysqli_query($conn, "SELECT * FROM `parking` WHERE TRACTO = '$tracto' AND ESTADO = 'ACTIVO'");
        //$buscarSemi = mysqli_query($conn, "SELECT * FROM `parking` WHERE SEMI = '$semi' AND ESTADO = 'ACTIVO'");
        
        if (mysqli_num_rows($buscarTracto) > 0) {
            $response = array(
                'status' => 'error',
                'message' => 'Error: El tracto ya se encuentra en el estacionamiento.'
            );
            echo json_encode($response);
            exit();
        }/*
        if(mysqli_num_rows($buscarSemi) > 0){
            $response = array(
                'status' => 'error',
                'message' => 'Error: El Semi ya se encuentra en el estacionamiento.'
            );
            echo json_encode($response);
            exit();
        }*/
        else{
            // Preparar y ejecutar la consulta preparada
            $sql = "INSERT INTO `parking` (`TRACTO`, `SEMI`, `DUCHA`, `NUMERO`, `TIPO`, `CARGA`, `CHOFER`, `TELEFONO`, `OBSER`, `FECHA_INGRESO`, `USER_INGRESO`, `ABONO`, `MOROSO`, `ESTADO`, `CLI`, `CLIENTE`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssissssssssssss", $tracto, $semi, $ducha, $numero, $tipo, $carga, $chofer, $fone, $obs, $fechaIngreso, $userIngeso, $abono, $moroso, $estado, $cli, $cliente);

            if (mysqli_stmt_execute($stmt)) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Datos guardados correctamente.',
                    'resetForm' => true
                );
                echo json_encode($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Error: ' . mysqli_stmt_error($stmt)
                );
                echo json_encode($response);
            }

            mysqli_stmt_close($stmt);
        }

    } else {
        // La solicitud no es válida, devolver un mensaje de error
        echo "Error: Método de solicitud no válido.";
    }
?>
