<?php
ob_start();
session_start();
error_reporting(0);
include_once("../conn/conexion.php");
date_default_timezone_set('America/Santiago');
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


$dateIn = $_GET['dateIn'];
$dateOut = $_GET['dateOut'];
$tipo = $_GET['tipo'];
$user = $_GET['user'];
$cliente = $_GET['cli'];
$fechaActual = date("d-m-Y H:i:s");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= stylesheet href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/bootstrap/js/bootstrap.min.js"></script>
    <title>INFORME PDF</title>
</head>
<body>
    <h1>INFORME PARQUEADERO "M. SILVA SPA"</h1>
    <hr>
    <?php
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
    echo '<table class="table table-striped table-hover tabla" width="100%" style="font-size: 10px;">';
    echo '<tr><th>N°</th><th>TRACTO</th><th>SEMI</th><th>TIPO</th><th>CARGA</th><th>CHOFER</th><th>TELEFONO</th><th>CLIENTE</th><th>'.$fecha.'</th><th>OBSER.</th><th>TOTAL</th><th>'.$usuario.'</th></tr>';

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
        echo '<td> '. $row['TRACTO'] .' </td>';
        echo '<td> '. $row['SEMI'] .' </td>';
        echo '<td> '. $row['TIPO'] .' </td>';
        echo '<td> '. $row['CARGA'] .' </td>';
        echo '<td> '. ucwords(strtolower($row['CHOFER'])) .' </td>';
        echo '<td> '. $row['TELEFONO'] .' </td>';
        echo '<td> '. $row['CLIENTE'] .' </td>';
        echo '<td> '. date("d-m-Y H:i:s", strtotime($row[$fecha])) .' </td>';
        echo '<td> '. $row['OBSER'] .' </td>';
        echo '<td> $ '. $totalFormatted .' </td>';
        echo '<td> '. $row[$usuario] .' </td>';
        echo '</tr>';
        $n++;
    }

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
    echo '<hr>';
    echo 'Valor total del reporte: $ '. $totalesSum .'';
    echo '<br>';
    echo 'Emitido por '.$nombre.' el '.$fechaActual.'';
}
    ?>
</body>
</html>
<?php
$html = ob_get_clean();
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream("comprobante.pdf", array("Attachment"=> false));
?>
?>