<?php include('../template/header.php'); ob_start(); session_start(); error_reporting(0);

include_once("../conn/conexion.php");
?>
<div class="container">
    <div class="clientes">
        <form name="clie" id="clie">
            <h3 class="animate__animated animate__backInLeft">Registro Entrada</h3>
			<hr>
            <label for="nameCliente">Nombre del Cliente</label>
            <input type="text" name="nameCliente" id="nameCliente" class="form-control" placeholder="Nombre del Cliente" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" oninput="validarNombre(this)" autofocus autocomplete="off" required>            
            <label for="rutCliente">RUT Cliente</label>
            <input type="text" name="rutCliente" id="rutCliente" class="form-control" placeholder="RUT Cliente" maxlength="12" oninput="validarRut(this)" onblur="formatearRut(this)" required>            
            <p id="mensajeError" class="error">El formato de correo no es correcto</p>
            <label for="telefonoCliente">Telefono</label>
            <input type="text" name="telefonoCliente" id="telefonoCliente" class="form-control" placeholder="Teléfono" maxlength="11" oninput="validarNumeros(this)" required>            
            <label for="mailCliente">Correo</label>
            <input type="text" name="mailCliente" id="mailCliente" class="form-control" placeholder="Correo" required>
            <p id="errorMensaje" class="error">El formato de correo no es correcto</p>
            <hr>     
            <button type="button" class="btn btn-primary" id="btnGuardarCliente">GUARDAR CLIENTE</button>
        </form>
    </div>
</div>
<script type="text/javascript">
  document.getElementById('btnGuardarCliente').addEventListener('click', function() {
    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById('nameCliente').value;
    var rut = document.getElementById('rutCliente').value;
    var telefono = document.getElementById('telefonoCliente').value;
    var correo = document.getElementById('mailCliente').value;

     // Crear un objeto FormData y agregar los valores del formulario
    var formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('rut', rut);
    formData.append('telefono', telefono);
    formData.append('correo', correo);

    // Crear una nueva solicitud Ajax
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_cliente.php', true);

    // Configurar el evento onload para manejar la respuesta del servidor
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Si la solicitud se ha completado correctamente, mostrar una alerta de éxito
        swal({
          title: 'Éxito',
          text: 'Los datos se han guardado correctamente.',
          icon: 'success',
          confirmButtonText: 'Aceptar'
        }).then(function() {
        // Limpiar los campos de entrada después de cerrar la alerta de éxito
        document.getElementById('nameCliente').value = '';
        document.getElementById('rutCliente').value = '';
        document.getElementById('telefonoCliente').value = '';
        document.getElementById('mailCliente').value = '';
      });
      } else {
        // Si ocurrió un error en la solicitud, mostrar una alerta de error
        swal({
          title: 'Error',
          text: 'No se ha podido guardar los datos.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
      }
    };

    // Enviar la solicitud Ajax con los datos del formulario
    xhr.send(formData);
  });

    function validarNombre(input) {
        var regex = /^[A-Za-z\s]+$/;
        var valor = input.value;
        if (!regex.test(valor)) {
        input.value = valor.replace(/[^A-Za-z\s]+/g, '');
        }
        return regex.test(valor);
    } 




    function validarNumeros(input) {
        input.value = input.value.replace(/\D/g, '');
        return /^\d+$/.test(input.value);
    }

    const inputCorreo = document.getElementById('mailCliente');
    const errorMensaje = document.getElementById('errorMensaje');

    inputCorreo.addEventListener('input', validarCorreo);

    function validarCorreo() {
        const correo = inputCorreo.value;
        const formatoValido = /^[^\s@]+@[^\s@]+\.(cl|com)$/.test(correo);

        if (formatoValido) {
        inputCorreo.style.borderColor = ''; // Restablecer el color del borde
        errorMensaje.style.display = 'none'; // Ocultar el mensaje de error
        } else {
        inputCorreo.style.borderColor = 'red'; // Cambiar el color del borde a rojo
        errorMensaje.style.display = 'block'; // Mostrar el mensaje de error
        }

        return formatoValido;
    }

    function validarRut(input) {
        var rutRegex = /^[0-9]+[0-9kK]?$/;
        var rut = input.value.replace(/\./g, "").replace(/\-/g, "");

        if (!rutRegex.test(input.value) || rut.length < 8) {
        // Si el valor no coincide con el formato permitido o la cantidad de números es menor a 8, mostrar el mensaje de error
        document.getElementById("mensajeError").style.display = "block";
        return false;
        } else {
        // Si el valor cumple con el formato, ocultar el mensaje de error
        document.getElementById("mensajeError").style.display = "none";
        input.value = input.value.replace(/k/gi, "K");
        return true;
        }
    }

    function formatearRut(input) {
        var rut = input.value.replace(/\./g, "").replace(/\-/g, "");

        if (rut.length === 9) {
        var formateado = rut.substring(0, 2) + "." + rut.substring(2, 5) + "." + rut.substring(5, 8) + "-" + rut.charAt(8);
        input.value = formateado;
        }
    }
</script>

<?php include('../template/footer.php'); ?>
