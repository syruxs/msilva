<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel= stylesheet href="css/style.css">
    <link rel= stylesheet href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel= stylesheet href="node_modules/animate.css/animate.min.css">
    <link rel= stylesheet href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>M SILVA | LOGIN</title>
</head>
<body style="background-color: #ebfafce4;">
    <div class="containe-div">
        <div class="left">
            <div class="globo">

            </div>    
            <br> 
            <h2>M. SILVA</h2>  
            <label for="">Parqueadero</label> 
            <p>©2023. Todos los derechos reservados.</p>
        </div>
        <div class="right animate__animated animate__backInLeft">
            <form method="post" action="login.php">
                <div class="inputbox">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="usuario" id="usuario" autocomplete="off" required maxlength="12" oninput="validarSoloLetras(this)">
                    <label for="">Usuario</label>
                </div>
                <div class="inputbox">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" name="pass" id="pass" autocomplete="off" required maxlength="12">
                    <label for="">Contraseña</label>
                </div>
                <input type="submit" value="Ingresar" id="btn-login">
            </form>
        </div>
    </div>
<script>
    function validarSoloLetras(input) {
        var regex = /^[a-z]+$/;
        var valor = input.value;
    
        if (!regex.test(valor)) {
        input.value = valor.slice(0, -1);
        }
    }
</script>
</body>
</html>