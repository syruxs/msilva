<?php include('../template/header.php'); ob_start(); session_start(); error_reporting(0);

include_once("../conn/conexion.php");
?>
<div class="containerDIV">
  <div class="child">
  <?php

// Consulta SQL para obtener los datos de la columna "TIPO"
$query = "SELECT TIPO FROM parking WHERE ESTADO = 'ACTIVO'";
$result = $conn->query($query);

$tipos = array();

if ($result->num_rows > 0) {
    // Almacena los datos en un array
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row["TIPO"];
    }
}

// Cierra la conexión a la base de datos
$conn->close();

// Cuenta la frecuencia de cada tipo
$tiposFrecuencia = array_count_values($tipos);

// Obtiene los valores y etiquetas para el gráfico de torta
$valores = array_values($tiposFrecuencia);
$etiquetas = array_keys($tiposFrecuencia);
  ?>
  <canvas id="graficoTorta"></canvas>
  </div>
  <div class="child">
    <div id="cont_67f2fd20a969af38cc65f49ec7ee571a"><script type="text/javascript" async src="https://www.meteored.cl/wid_loader/67f2fd20a969af38cc65f49ec7ee571a"></script></div>
    <a class="twitter-timeline" href="https://twitter.com/UPFronterizos?ref_src=twsrc%5Etfw">Tweets by UPFronterizos</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  </div>
</div>
<script>
        // Crea el gráfico de torta utilizando Chart.js
        var ctx = document.getElementById('graficoTorta').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($etiquetas); ?>,
                datasets: [{
                    data: <?php echo json_encode($valores); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        // Puedes agregar más colores aquí si tienes más tipos de datos
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Distribución de tipos de parking'
                }
            }
        });
    </script>