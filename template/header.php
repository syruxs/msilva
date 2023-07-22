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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel= stylesheet href="../css/style.css">
    <link rel= stylesheet href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel= stylesheet href="../node_modules/animate.css/animate.min.css">
    <link rel= stylesheet href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel= stylesheet href="../node_modules/aos/dist/aos.css" />
    <script src="../node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
	<script src="../node_modules/aos/dist/aos.js"></script>
    <script src="../js/out.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../node_modules/chart.js/dist/chart.js"></script>
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="info-fill" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
    </svg>
        <!-- Otros elementos del encabezado -->
        <script>
        function actualizarValores() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('camionesEnPatio').innerHTML = response.camionesEnPatio;
                    document.getElementById('disponibilidad').innerHTML = response.disponibilidad;

                    // Crear el gráfico de torta
                    crearGraficoTorta(response.camionesEnPatio, response.disponibilidad);
                }
            };
            xhr.open('GET', 'obtener_datos.php', true);
            xhr.send();
        }
        function crearGraficoTorta(camionesEnPatio, disponibilidad) {
            var datos = {
                datasets: [{
                    data: [camionesEnPatio, disponibilidad],
                    backgroundColor: ['#ff6384', '#36a2eb']
                }]
            };

            var opciones = {
                responsive: true
            };

            var ctx = document.getElementById('graficoTorta').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: datos,
                options: opciones
            });
        }

        // Llamar a la función de actualización inicialmente
		actualizarValores();
    </script>
    <title>M Silva | Home</title>
</head>
<body style="background-color: #ebfafce4;">
<header>
		<div class="menu_bar">
			<a class="bt-menu" href="#"><i class="fa fa-bars" aria-hidden="true"></i>Menu</a>
		</div>
		<nav>
		<div class="logo">
			<?php echo "Bienvenido ".$nombre; ?> 
			<a href="#" onclick="logoutConfirmation()" title="SALIR DE APLICACIÓN">
				<i class="fa fa-sign-out fa-2x" aria-hidden="true"></i>
			</a>
		</div>
			<ul>
				<li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>Inicio</a></li>
				<li><a href="informes.php"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>Informe</a></li>
                <li><a href="informacion.php"><i class="fa fa-info" aria-hidden="true"></i>Información</a></li>
                <li><a href="ingresoManual.php"><i class="fa fa-truck" aria-hidden="true"></i>Ingreso Manual</a></li>
                <li><a href="salidaManual.php"><i class="fa fa-arrow-right" aria-hidden="true"></i><i class="fa fa-truck" aria-hidden="true"></i>Salida Manual</a></li>
                <li><a href="clientes.php"><i class="fa fa-address-card-o" aria-hidden="true"></i>Clientes</a></li>
		</nav>
	</header>