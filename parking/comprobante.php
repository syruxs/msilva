<?php
ob_start();
session_start();
error_reporting(0);

include_once("../conn/conexion.php");
$Id = $_GET['ID'];

$buscar = mysqli_query($conn, "SELECT * FROM parking WHERE id_parking = '$Id'");
while ($res = mysqli_fetch_array($buscar)){
    $patente = $res['TRACTO'];
    $semi = $res['SEMI'];
    $obs = $res['OBSER'];
    $entrada = $res['FECHA_INGRESO'];
    $entrada = date("d-m-Y H:i:s", strtotime($entrada));
    $salida = $res['FECHA_SALIDA'];
    $salida = date("d-m-Y H:i:s", strtotime($salida));
    $abono = $res['ABONO'];
    $total = $res['TOTAL'];
    $Total = intval($total) + intval($abono);

    $Total = number_format($Total, 0, ',', '.');
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Salida</title>
    <style>
        body {
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        .identificador{
            width: 100%;
            height: auto;
            border: 1px solid black;
            text-align: center;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <label style="font-size: 49px;">COMPROBANTE DE SALIDA</label>
    **********************************************************************************************************************
    <br>
    <br>
    <div class="identificador">
        <label style="font-size: 40px;">PARQUEADERO</label><br>
        <label style="font-size: 40px;">M. SILVA SPA</label><br>
        <label style="font-size: 30px;">RUT 77.521.523-2</label><br>
        <label style="font-size: 30px;">FONO +56 9 69076238</label>
    </div>
    <br>
    <br>
    <table width="100%" border="0">
        <tr>
            <td>
                <label style="font-size: 40px;">TRACTO </label>
            </td>
            <td>
                <label style="font-size: 40px;"> : </label>
            </td>
            <td>
            <label style="font-size: 40px;"> <?php echo $patente;?></label>
            </td>
        </tr>
        <tr>
            <td>
                <label style="font-size: 40px;">SEMI </label>
            </td>
            <td>
                <label style="font-size: 40px;"> : </label>
            </td>
            <td>
                <label style="font-size: 40px;"> <?php echo $semi;?></label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label style="font-size: 40px;"><?php echo $obs;?></label>
            </td>
        </tr>
        <tr>
            <td>
                <label style="font-size: 32px;">FECHA ENTRADA </label>
            </td>
            <td>
                <label style="font-size: 32px;"> : </label>
            </td>
            <td>
                <label style="font-size: 32px;"> <?php echo $entrada;?></label>
            </td>
        </tr>
        <tr>
            <td>
                <label style="font-size: 32px;">FECHA SALIDA </label>
            </td>
            <td>
                <label style="font-size: 32px;"> : </label>
            </td>
            <td>
                <label style="font-size: 32px;"> <?php echo $salida;?></label>
            </td>
        </tr>
        <tr>
            <td>
                <label style="font-size: 32px;">VALOR PAGADO </label>
            </td>
            <td>
                <label style="font-size: 32px;"> : </label>
            </td>
            <td>
                <label style="font-size: 32px;"> $ <?php echo $Total;?></label>
            </td>
        </tr>
    </table>
    <label style="font-size: 170px;">PAGADO</label>
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
