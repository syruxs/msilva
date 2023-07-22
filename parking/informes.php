<?php include('../template/header.php'); ob_start(); session_start(); error_reporting(0);

include_once("../conn/conexion.php");
?>
    <div class="container">
        <table border="0" width="100%" cellspacing="6" cellpadding="6">
            <tr>
                <td width="20%">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">DESDE</span>
                        <input type="datetime-local" class="form-control" name="dateIn" id="dateIn">
                    </div>
                </td>
                <td width="20%">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">HASTA</span>
                        <input type="datetime-local" class="form-control" name="dateOut" id="dateOut">
                    </div>
                </td>               
                <td width="15%">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">TIPO</span>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="1">EN PARQUEADERO</option>
                            <option value="2">SALIDOS</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">USUARIO</span>
                        <select name="user" id="user" class="form-control">
                            <option value="1">TODOS</option>
                            <?php
                                $sql = mysqli_query($conn, "SELECT * FROM `user` ORDER BY `nombre` ASC");
                                while ($row = mysqli_fetch_array($sql)) {
                                    echo '<option value="'.$row['user'].'">'.$row['nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">CLIENTES</span>
                        <select name="cli" id="cli" class="form-control">
                            <option value="1">TODOS</option>
                            <?php
                                $sql = mysqli_query($conn, "SELECT * FROM `clientes` ORDER BY `nombre` ASC");
                                while ($row = mysqli_fetch_array($sql)) {
                                    echo '<option value="'.$row['nombre'].'">'.$row['nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </td>
                <td>
                    <input type="submit" class="btn btn-primary" value="BUSCAR">
                </td>            
            </tr>
        </table>
    </div>
    <hr>
    <div id="result"></div>
<script>
    // Obtener referencia al botón de búsqueda
    var searchBtn = document.querySelector('.container input[type="submit"]');
    var resultDiv = document.getElementById('result');

    // Escuchar el evento de clic en el botón de búsqueda
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Evitar el envío del formulario por defecto

        // Obtener los valores de los campos de entrada
        var dateIn = document.getElementById('dateIn').value;
        var dateOut = document.getElementById('dateOut').value;
        var tipo = document.getElementById('tipo').value;
        var user = document.getElementById('user').value;
        var cli = document.getElementById('cli').value;

        // Crear un objeto FormData y agregar los valores
        var formData = new FormData();
        formData.append('dateIn', dateIn);
        formData.append('dateOut', dateOut);
        formData.append('tipo', tipo);
        formData.append('user', user);
        formData.append('cli', cli);

        // Crear y configurar la solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'buscarInforme.php', true);

        // Escuchar el evento de carga de la solicitud AJAX
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Actualizar el contenido del elemento de resultado
                resultDiv.innerHTML = xhr.responseText;
            } else {
                // Crear el elemento div para el mensaje de error
                var errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger d-flex align-items-center';
                errorDiv.setAttribute('role', 'alert');
                errorDiv.innerHTML = '<svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>' +
                                    '<div>Error al procesar los datos!</div>';
                
                // Agregar el mensaje de error al resultDiv
                resultDiv.innerHTML = ''; // Limpiar el contenido anterior del resultDiv si es necesario
                resultDiv.appendChild(errorDiv);
            }
        };

        // Enviar la solicitud AJAX con los datos del formulario
        xhr.send(formData);
    });



</script>
<?php include('../template/footer.php'); ?>
