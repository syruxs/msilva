<?php include('../template/header.php'); ob_start(); session_start(); error_reporting(0);

include_once("../conn/conexion.php");
?>
<div class="containerIngreso">
    <form id="entrada" method="post">
		<div class="entrada">
			<h3 class="animate__animated animate__backInLeft">Registro Entrada</h3>
			<hr>

				<p>
					Camiones en patio: <span id="camionesEnPatio"></span>
					Disponibilidad: <span id="disponibilidad"></span>
				</p>

				<div class="graficoDisponibilidad">
					<canvas id="graficoTorta"></canvas>
				</div>
			
			<label for="tracto">Tracto</label>
			<div class="input-group mb-3">
				<input type="text" name="tracto" id="tracto" class="form-control" placeholder="Ingrese patente del Tracto" maxlength="10" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" oninput="validarInput(this)" autocomplete="off" required>
				<select name="ducha" id="ducha" class="form-control" onchange="toggleDuchasField()">
					<option value="no">PARQUEADERO</option>
					<option value="si">DUCHA</option>
				</select>
				<input type="text" name="duchas" id="duchas" class="form-control" placeholder="N°" maxlength="2" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" style="display: none; width: 60px;" value="1" autocomplete="off" required>
			</div>

			<label for="semi">Semi</label>
			<div class="input-group mb-3">
			<input type="text" name="semi" id="semi" class="form-control" placeholder="Ingrese patente del Semi" maxlength="10" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" oninput="validarInput(this)" autocomplete="off">
			<select name="tipo" id="tipo" class="form-control">
				<option value="ENCARPADO">ENCARPADO</option>
				<option value="SIDER">SIDER</option>
				<option value="CISTERNA">CISTERNA</option>
				<option value="CONTENEDOR">CONTENEDOR</option>
				<option value="REFRIGERADO">REFRIGERADO</option>
				<option value="SIN SEMI">SIN SEMI</option>
			</select>
			</div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="vacio" value="Vacio" name="radio_select">
                <label class="form-check-label" for="vacio">Vacio</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="cargado" value="Cargado" name="radio_select" checked>
                <label class="form-check-label" for="cargado">Cargado</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="cliente" name="cliente" value="Cliente" onclick="mostrarOcultarSelect()">
                <label class="form-check-label" for="cliente">Cliente</label>
            </div>

            <div style="display: flex; align-items: center;">
                <select class="form-select" id="opcionesCliente" name="opcionesCliente" style="display: none;">
					<option value="0">SELECCIONAR CLIENTE</option>
                    <?php 
                        $cliente = mysqli_query($conn, "SELECT * FROM `clientes` ORDER BY `clientes`.`nombre` ASC");
                        while($rows = mysqli_fetch_array($cliente)){
                            echo '<option value="'.$rows['nombre'].'">'.$rows['nombre'].'</option>';
                        }
                    ?>
                </select>
            </div>

			<hr>
			<label for="fecha">Fecha Entrada</label>
			<input type="datetime-local" name="fechaEntrada" id="fechaEntrada" class="form-control" required>
			<hr>
			<div class="othe">
				<label>Nombre :&nbsp;</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Ingresar Nombre" title="Ingresar Nombre" style="text-transform:capitalize;" autocomplete="off">
				
				<label>Telefono:&nbsp;</label>
				<input type="tel" class="form-control" id="fone" name="fone" placeholder="569-12345678" title="Ingresar un numero telefonico" maxlength="11" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" autocomplete="off">
				
				<label>Observaciones:&nbsp;</label>
				<input type="text" class="form-control" id="obs" name="obs" autocomplete="off">
			</div>

			<hr>
            <button type="submit" class="btn btn-primary" title="GUARDAR EXPEDIENTE"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
            <button type="submit" class="btn btn-success" title="LIMPIAR EXPEDIENTE" onclick="clearForm();"><i class="fa fa-eraser" aria-hidden="true"></i> LIMPIAR</button>
		</div>
	</form>
</div>
<script>
	function toggleDuchasField() {
        var duchaSelect = document.getElementById("ducha");
        var duchasInput = document.getElementById("duchas");
        
        if (duchaSelect.value === "si") {
            duchasInput.style.display = "block";
        } else {
            duchasInput.style.display = "none";
        }
    }
	function mostrarOcultarSelect() {
        var checkbox = document.getElementById("cliente");
        var select = document.getElementById("opcionesCliente");

        if (checkbox.checked) {
            select.style.display = "block"; // Mostrar el select
        } else {
            select.style.display = "none"; // Ocultar el select
        }
    }
	function clearForm() {
		// Limpiar los valores de los campos del formulario
		form.reset();
        document.getElementById("opcionesCliente").style.display = "none";
	}
	// Obtener el formulario por su ID
	var form = document.getElementById('entrada');
  
	// Agregar un evento 'submit' al formulario
	form.addEventListener('submit', function(event) {
		event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

		// Obtener los datos del formulario
		var formData = new FormData(form);

		// Crear una nueva instancia de XMLHttpRequest
		var xhr = new XMLHttpRequest();

		// Configurar la solicitud AJAX
		xhr.open('POST', 'save_enter_manual.php', true);

		// Definir la función de callback cuando la solicitud se complete
		xhr.onload = function() {
			if (xhr.status === 200) {
				// La solicitud se completó con éxito
				var response = JSON.parse(xhr.responseText);
				console.log(response.message); // Mostrar el mensaje de respuesta del servidor en la consola

				swal({
					title: response.status === 'success' ? '¡Bien hecho!' : 'Problemas!',
					text: response.message,
					icon: response.status === 'success' ? 'success' : 'error',
					button: 'Aceptar'
				});

				if (response.resetForm) {
					document.getElementById('entrada').reset();
				}

				setTimeout(function() {
					swal.close();
				}, 4000);

				// Llamar a la función de actualización inicialmente
				actualizarValores();
			} else {
				// Hubo un error en la solicitud
				console.error('Error al enviar la solicitud.');

				swal({
					title: 'Error',
					text: 'Hubo un error al enviar la solicitud.',
					icon: 'error',
					button: 'Aceptar'
				});

				setTimeout(function() {
					swal.close();
				}, 4000);
			}
		};

		// Enviar la solicitud AJAX
		xhr.send(formData);
	});
</script>
<?php include('../template/footer.php'); ?>
