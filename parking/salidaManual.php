<?php include('../template/header.php'); ob_start(); session_start(); error_reporting(0);

include_once("../conn/conexion.php");
?>
<div class="containerIngreso">
<div class="salida">
			<h3 class="animate__animated animate__backInLeft">Registro Salida</h3>
			<hr>
			<p>Ingrese la patente del Tracto o Semi para realizar la busqueda.</p>
			<form action="salidaManual.php" method="post">
			<div class="input-group">
				<input type="text" name="buscar_por" id="buscar_por" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="20" autocomplete="off" required>
				<select name="seach_semi" id="seach_semi" Class="form-control">
					<option value="tracto">TRACTO</option>
					<option value="semi">SEMI</option>
				</select>
                   <button type="submit" class="btn btn-primary" title="BUSCAR POR EXPEDIENTE"><i class="fa fa-search" aria-hidden="true"></i> BUSCAR</button>
            </div>
			</form>
            <form id="salida" method="post">
			<hr>
			<?php
				$patente = $_POST['buscar_por'];
				$metodo = $_POST['seach_semi'];

				if ($patente != "") {
					if ($metodo == "tracto") {
							$filtro = "TRACTO";
					} else {
							$filtro = "SEMI";
					}
						
						$encontrar = mysqli_query($conn, "SELECT * FROM `parking` WHERE $filtro = '$patente' AND ESTADO = 'ACTIVO'");
						
						if (mysqli_num_rows($encontrar) > 0) {
							while ($row_enc = mysqli_fetch_array($encontrar)) {
								date_default_timezone_set('America/Santiago');

								$Tracto = $row_enc['TRACTO'];
								$Semi = $row_enc['SEMI'];
								$Ducha = $row_enc['DUCHA'];
								$Numero = $row_enc['NUMERO'];
								$Tipo = $row_enc['TIPO'];
                                $cli = $row_enc['CLI'];
								$Entrada = $row_enc['FECHA_INGRESO'];
								$enter = date("d-m-Y", strtotime($Entrada));
								$Hora = date("H:i:s", strtotime($Entrada));
								$Entrada = $row_enc['FECHA_INGRESO'];
								$tiempoTranscurrido = time() - strtotime($Entrada);
								
								// Formatear el tiempo transcurrido
								$dias = floor($tiempoTranscurrido / (60 * 60 * 24));
								$horas = floor(($tiempoTranscurrido % (60 * 60 * 24)) / (60 * 60));
								$minutos = floor(($tiempoTranscurrido % (60 * 60)) / 60);
								$segundos = $tiempoTranscurrido % 60;

								// Crear una variable para mostrar el tiempo transcurrido
								$tiempoTranscurridoFormateado = "$dias días, $horas horas y $minutos minutos.";
								
								// Calcular el valor de $total en función del tiempo transcurrido
								$total = ($dias * 5000) + (($horas >= 24 || $minutos >= 1) ? ceil(($horas + $minutos / 60) / 24) * 5000 : 0);
								
								$Abono = $row_enc['ABONO'];
								$Moroso = $row_enc['MOROSO'];
								
								$Total = $total - $Abono;

								if($Ducha == "si"){
									$TodaPersona = $Numero * 1000;
									$TotalDucha = $TodaPersona + 1000;
									?>
									<div class="alert alert-warning" role="alert">
									Servicio de Duchas.
									</div>
									<table width="100%" border="1">
										<tr>
											<td>
												<p>Tracto : <?php echo $Tracto; ?></p>
											</td>
											<td>
												<p>Semi : <?php echo $Semi; ?></p>
											</td>
											<td>
												<p>Personas : <?php echo $Numero; ?></p>
											</td>
										</tr>
									</table>
									<hr>
									<p>Tiempo en Parqueadero : <?php echo $tiempoTranscurridoFormateado;?></p>
									<table width="100%" border="1">
										<tr>
											<td>
												<input type="hidden" name="showerId" id="showerId" value="<?php echo $row_enc['id_parking'];?>">
												<div class="input-group mb-3">
													<span class="input-group-text" id="basic-addon1">Total a Pagar:</span>
													<input type="text" name="shower" id="shower" value="<?php echo number_format($TotalDucha, 0, ',', '.');?>" class="form-control" maxlength="6" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
													<button class="btn btn-primary" type="button" onclick="pagar()"><i class="fa fa-usd" aria-hidden="true"></i> PAGAR</button>	
												</div>
											</td>
										</tr>
									</table>
									<hr>
									<button class="btn btn-primary" type="button" onclick="ingresar()">INGRESAR A PARQUEADERO <i class="fa fa-reply-all" aria-hidden="true"></i> <i class="fa fa-truck" aria-hidden="true"></i></button>
								<?php 
								}else {
							?>
							<table width="100%" border="1">
								<tr>
									<td>
										<p>Tracto : <?php echo $Tracto; ?></p>
									</td>
									<td>
										<p>Semi : <?php echo $Semi; ?></p>
									</td>
									<td>
										<p>Tipo : <?php echo $Tipo; ?></p>
									</td>
								</tr>
								<tr>
									<td>
										<p>Nombre : <?php echo ucwords(strtolower($row_enc['CHOFER'])); ?></p>
									</td>
									<td>
										<p>Telefono : <?php echo "+".$row_enc['TELEFONO']; ?></p>
									</td>
									<td>
										<p>Carga : <?php echo $row_enc['CARGA'];?></p>
									</td>
								</tr>
                                <tr>
                                    <td colspan="3">
                                        <?php
                                            if($cli == "NO"){

                                            ?>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Cliente :</span>
                                                        <select name="selectCliente" id="selectCliente" class="form-control">
                                                            <option value="0">SELECCIONAR CLIENTE</option>
                                                            <?php 
                                                                $buscarClientes = mysqli_query($conn, "SELECT * FROM `clientes` ORDER BY `clientes`.`nombre` ASC");
                                                                    while($resultadoBusqueda = mysqli_fetch_array($buscarClientes)){
                                                                        echo '<option value="'.$resultadoBusqueda['nombre'].'">'.$resultadoBusqueda['nombre'].'</option>';
                                                                    }
                                                            ?>
                                                        </select>
                                                </div>
                                        <?php
                                            }else {
                                        ?>
                                        <p>Cliente : <?php echo $row_enc['CLIENTE'];?></p>
										<input type="hidden" name="selectCliente" id="selectCliente" value="<?php echo $row_enc['CLIENTE'];?>">
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
								<tr>
									<td colspan="3">
										<p>Observaciones : 
										<input type="text" value="<?php echo $row_enc['OBSER']; ?>" id="ob" name="ob" class="form-control" style="width: 100%; text-transform:capitalize;">
										</p>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<p>Fecha Entrada : <?php echo $enter. " | " .$Hora;?></p>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<p>Tiempo en Parqueadero : <?php echo $tiempoTranscurridoFormateado; ?></p>
									</td>
								</tr>
							</table>
                            <hr>
                            <table width="100%" border="0">
                                <tr>
                                    <td><label>Fecha y Hora de Salida</label></td>
                                    <td><input type="datetime-local" name="dateSalida" id="dateSalida" class="form-control" required></td>
                                </tr>
                            </table>
							<hr>
							<table width="100%" border="0">
								<tr>
									<td width="50%">
										<div class="input-group mb-3">
											<span class="input-group-text" id="basic-addon1">Abono :</span>
											<input type="text" name="abono" id="abono" value="<?php echo number_format($Abono, 0, ',', '.');?>" class="form-control" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
										</div>
									</td>
									<td>
										<div class="input-group mb-3">
											<span class="input-group-text" id="basic-addon1">Total :</span>
											<input type="text" name="total" id="total" value="<?php echo number_format($Total, 0, ',', '.');?>" class="form-control" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
										</div>
									</td>
								</tr>
							</table>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="input-group mb-3">
											<span class="input-group-text" id="basic-addon1">Moroso :</span>
												<select name="moroso" id="moroso" class="form-control" style="width: 50px;">
													<option value="NO" <?php if ($Moroso == "NO") { echo "selected"; } ?>>NO</option>
													<option value="SI" <?php if ($Moroso == "SI") { echo "selected"; } ?>>SI</option>
												</select>
										</div>
									</td>
									<td>
										<div class="input-group mb-3">
											<span class="input-group-text" id="basic-addon1">Cliente :</span>
                                            <select name="cli" id="cli" class="form-control" style="width: 50px;">
												<option value="NO" <?php if ($cli == "NO") { echo "selected"; } ?>>NO</option>
												<option value="SI" <?php if ($cli == "SI") { echo "selected"; } ?>>SI</option>
											</select>
                                        </div>
									</td>
                                </tr>
                                <tr>
									<td>
                                    <input type="hidden" name="Id" id="Id" value="<?php echo $row_enc['id_parking']; ?>">
                                    <input type="hidden" name="sistema"  id="sistema" value="<?php echo $Total; ?>">
									<div class="input-group mb-3">
                                        <button class="btn btn-warning" type="button" onclick="guardarModificar()">MODIFICAR <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                        <button class="btn btn-primary" type="button" onclick="guardarSalida()">DAR SALIDA <i class="fa fa-sign-out" aria-hidden="true"></i></button>
                                    </div>
                                    </td>
								</tr>
							</table>
							<?php
							}
						}
						} else {
							echo '<div id="mensaje" class="alert alert-warning" role="alert">
								No se han encontrado resultados de la búsqueda.
							</div>';
						}
				}
			?>
            </form>
		</div>
</div>
<script>
/*************************************/

// funciones para modificar expediente

    // Obtener referencia a los elementos select
    var selectCliente = document.getElementById("selectCliente");
    var selectCli = document.getElementById("cli");

    // Escuchar el evento de cambio en el select del cliente
    selectCliente.addEventListener("change", function() {
    // Obtener el valor seleccionado del select del cliente
    var clienteValue = selectCliente.value;

    // Establecer el valor correspondiente en el select de cli
    selectCli.value = (clienteValue !== "0") ? "SI" : "NO";
    });

	function guardarModificar() {

		// Obtener el formulario por su ID
		var fromSalida = document.getElementById('salida');

		// Obtener los valores de los campos del formulario
		var formData = new FormData(fromSalida);

		// Agregar un parámetro adicional para identificar la acción
		formData.append('accion', 'modificar');

			// Obtener el valor seleccionado en el select del cliente
			var selectCliente = document.getElementById("selectCliente");
			var clienteValue = selectCliente.value;

			// Obtener el valor seleccionado en el select de cli
			var selectCli = document.getElementById("cli");
			var cliValue = selectCli.value;

			// Verificar si el cliente ha sido seleccionado antes de enviar el formulario
			if (cliValue === "SI" && clienteValue === "0") {
				
				swal({
					title: "Problemas!",
					text: "Debes seleccionar un Cliente para poder Modificar.!",
					icon: "info",
					button: "Aceptar",
					});
				selectCliente.focus();
				return; // Detener la ejecución de la función
			}

			// Realizar la solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_out_Manual.php', true);
            xhr.onload = function() {
                // Procesar la respuesta del servidor
                if (xhr.status === 200) {
                    // La solicitud se completó con éxito
                    var response = JSON.parse(xhr.responseText);
                    console.log(response.message); // Mostrar el mensaje de respuesta del servidor en la consola

                    swal({
                        title: response.status === 'success' ? '¡Bien hecho!' : 'Problemas!',
                        text: response.message,
                        icon: response.status === 'success' ? 'success' : 'error',
                        buttons: "Aceptar",
                    });
                    
                    setTimeout(function() {
                        swal.close();
                        localStorage.clear();
                        location.reload();
                    }, 4000);
                } else {
                    console.error('Error al enviar la solicitud AJAX');
                }
            };

			xhr.send(formData);
	}

// fin codigo para modificar expediente

/*************************************/

// funciones para el registro de salida
	function guardarSalida() {

	// Obtener los valores de los campos del formulario
	var abono = document.getElementById("abono").value;
	var ob = document.getElementById("ob").value;
	var moroso = document.getElementById("moroso").value;
	var Id = document.getElementById("Id").value;
	var total = document.getElementById("total").value;
	var sistema = document.getElementById("sistema").value;
    var dateSalida = document.getElementById("dateSalida").value;

	// Crear un objeto FormData y agregar los valores de los campos
	var formData = new FormData();
	formData.append('abono', abono);
	formData.append('ob', ob);
	formData.append('moroso', moroso);
	formData.append('Id', Id);
	formData.append('total', total);
	formData.append('sistema', sistema);
    formData.append('dateSalida', dateSalida);

	// Agregar un parámetro adicional para identificar la acción
	formData.append('accion', 'salida');

	// Realizar la solicitud AJAX
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'save_out_Manual.php', true);
	xhr.onload = function() {
			// Procesar la respuesta del servidor
			if (xhr.status === 200) {
			// La solicitud se completó con éxito
			var response = JSON.parse(xhr.responseText);
			console.log(response.message); // Mostrar el mensaje de respuesta del servidor en la consola

                    swal({
                        title: response.status === 'success' ? '¡Bien hecho!' : 'Problemas!',
                        text: response.message,
                        icon: response.status === 'success' ? 'success' : 'error',
                        buttons: {
                            imprimir: {
                                text: "Imprimir boleta",
                                value: "imprimir",
                            },
                            cancel: "Cancelar",
                        },
                    }).then(function(value) {
                        if (value === "imprimir") {
                            var link = response.link; // Obtener el enlace de la respuesta AJAX
                            window.open(link, '_blank'); // Abrir la nueva ventana en una pestaña o ventana nueva
                        }
                    });
			
                    setTimeout(function() {
                        localStorage.clear();
                        location.reload();
                    }, 4000);

		} else {
			console.error('Error al enviar la solicitud AJAX');
		}
	};
	xhr.send(formData);
}
// fin de codigo para registro salida

/*************************************/
</script>
<script>
    	// valores a los input de numeros
	$("#total").on({
		"focus": function (event) {
			$(event.target).select();
		},
		"keyup": function (event) {
			$(event.target).val(function (index, value ) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{0})$/, '$1')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
				});
			}
	});
	$("#abono").on({
		"focus": function (event) {
			$(event.target).select();
		},
		"keyup": function (event) {
			$(event.target).val(function (index, value ) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{0})$/, '$1')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
				});
			}
	});
	$("#abono").on({
		"focus": function (event) {
			$(event.target).select();
		},
		"keyup": function (event) {
			$(event.target).val(function (index, value ) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{0})$/, '$1')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
				});
			}
	});
</script>
<?php include('../template/footer.php'); ?>
