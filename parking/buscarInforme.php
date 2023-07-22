<?php
session_start();
include_once("../conn/conexion.php");
// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
	$seachUser = mysqli_query($conn, "SELECT * FROM `user` WHERE `user` = '$username'");
	while ($row = mysqli_fetch_array($seachUser)) {
		$nombre = $row['nombre'];
	}
} else {
    header("Location: ../index.php");
} 
date_default_timezone_set('America/Santiago');

// Obtener los valores enviados por AJAX
$dateIn = $_POST['dateIn'];
$dateOut = $_POST['dateOut'];
$tipo = $_POST['tipo'];
$user = $_POST['user'];
$cliente = $_POST['cli'];

if($tipo == '1'){
    $fecha = 'FECHA_INGRESO';
    $usuario = 'USER_INGRESO';
    $estado = 'ACTIVO';
}else{
    $fecha = 'FECHA_SALIDA';
    $usuario = 'USER_SALIDA';
    $estado = 'INACTIVO';
}

// Construir la consulta SQL
$sql = "SELECT * FROM `parking` WHERE `$fecha` BETWEEN '$dateIn' AND '$dateOut' AND `ESTADO` = '$estado'";
$Sql_total = "SELECT SUM(TOTAL) AS total_sum FROM `parking` WHERE `$fecha` BETWEEN '$dateIn' AND '$dateOut' AND `ESTADO` = '$estado'";
$Sql_abono = "SELECT SUM(ABONO) AS abono_sum FROM `parking` WHERE `$fecha` BETWEEN '$dateIn' AND '$dateOut' AND `ESTADO` = '$estado'";

// Si se seleccionó un usuario específico, agregarlo a la consulta
if ($user != '1') {
    if($fecha == 'FECHA_INGRESO'){
        $sql .= " AND `USER_INGRESO` = '$user'";
        $Sql_total .= " AND `USER_INGRESO` = '$user'";
        $Sql_abono .= " AND `USER_INGRESO` = '$user'";

    }elseif($fecha == 'FECHA_SALIDA'){
        $sql .= " AND `USER_SALIDA` = '$user'";
        $Sql_total .= " AND `USER_SALIDA` = '$user'";
        $Sql_abono .= " AND `USER_SALIDA` = '$user'";
    }
}
if($cliente != '1'){
    $sql .= " AND `CLIENTE` = '$cliente'";
    $Sql_total .= " AND `CLIENTE` = '$cliente'";
    $Sql_abono .= " AND `CLIENTE` = '$cliente'";
}


// Ejecutar la consulta
$result = mysqli_query($conn, $sql);
$result_total = mysqli_query($conn, $Sql_total);
$result_abono = mysqli_query($conn, $Sql_abono);

// Verificar si se obtuvieron resultados
if (mysqli_num_rows($result) > 0) {
    // Crear la tabla HTML para mostrar los resultados
    echo '<table class="table table-striped table-hover tabla" width="100%" style="font-size: 12px;">';
    echo '<tr><th width="auto">N°</th><th width="auto">TRACTO</th><th width="auto">SEMI</th><th width="auto">TIPO</th><th width="auto">CARGA</th><th width="auto">CHOFER</th><th width="auto">TELEFONO</th><th width="auto">CLIENTE</th><th width="auto">'.$fecha.'</th><th width="auto">OBSER.</th><th width="auto">TOTAL</th><th width="auto">'.$usuario.'</th></tr>';
    
    $n = 1;
    // Recorrer los resultados y mostrarlos en filas de la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        $abono = $row['ABONO'];
        $Totales = $row['TOTAL'];
        if($abono != '0'){
            $suma = $Totales + $abono;
            $totalFormatted = number_format($suma, 0, ',', '.');
        }else{
            $abono = intval($abono);
            $Totales = intval($Totales);
            $totalFormatted = number_format($Totales, 0, ',', '.'); 
        }

            echo '<tr>';
        echo '<td> '. $n .' </td>';
        echo '<td><a href="comprobante.php?ID='.$row['id_parking'].'" target="_blank" style="text-decoration: none;">'. $row['TRACTO'] .' </a> </td>';
        echo '<td> '. $row['SEMI'] .' </td>';
        echo '<td> '. $row['TIPO'] .' </td>';
        echo '<td> '. $row['CARGA'] .' </td>';
        echo '<td> '. ucwords(strtolower($row['CHOFER'])) .' </td>';
        echo '<td> '. $row['TELEFONO'] .' </td>';
        echo '<td> '. $row['CLIENTE'] .' </td>';
        echo '<td> '. date("d-m-Y H:i:s", strtotime($row[$fecha])) .' </td>';
        echo '<td> '. $row['OBSER'] .' </td>';
        echo '<td> $ '. $totalFormatted .' </td>';
        echo '<td aling="right"> '. $row[$usuario] .' </td>';
        echo '</tr>';
        $n++;
    }/* 
        echo '
        <tr>
            <td colspan="12" aling="center">
                <nav aria-label="...">
                    <ul class="pagination">
                        <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </td>
        </tr>';*/
    echo '</table>';

    if ($result_total && mysqli_num_rows($result_total) > 0) {
        $rowS = mysqli_fetch_assoc($result_total);
        $totalSum = $rowS['total_sum'];
    }
    if($result_abono && mysqli_num_rows($result_abono) > 0){
        $rowA = mysqli_fetch_assoc($result_abono);
        $abonoSum = $rowA['abono_sum'];
    }

    $totalSum = intval($totalSum);
    $abonoSum = intval($abonoSum);
    $totalesSum = $totalSum + $abonoSum;
    $totalesSum = number_format($totalesSum, 0, ',', '.');

    echo 'Total acumulado $ '. $totalesSum . ' <input type="button" name="pdf" id="pdf" value="VER INFORME" class="btn btn-primary" onclick="window.open(\'InformePdf.php?dateIn='.$dateIn.'&dateOut='.$dateOut.'&tipo='.$tipo.'&user='.$user.'&cli='.$cliente.'\')">';
} else {
    echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            No se han encontrado resultados!
        </div>
    </div>';
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

?>