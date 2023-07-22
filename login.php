<?php
error_reporting(0);
// Recibir los valores del formulario y realizar saneamiento
$username = trim($_POST['usuario']);
$password = trim($_POST['pass']);
// Obtener la dirección IP del cliente
// Función para obtener la dirección IP del cliente
function getClientIPAddress() {
    $ipAddress = '';

    // Verificar si la dirección IP está presente en la variable REMOTE_ADDR
    if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    // Verificar si el cliente está detrás de un proxy y la dirección IP real se encuentra en la cabecera HTTP_X_FORWARDED_FOR
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // Verificar si el cliente está detrás de un proxy y la dirección IP real se encuentra en la cabecera HTTP_CLIENT_IP
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    }

    return $ipAddress;
}

// Obtener la dirección IP del cliente
$ip = getClientIPAddress();

// Verificar que los campos no estén vacíos
if (empty($username) || empty($password)) {
    mostrarError("Por favor, ingresa un usuario y contraseña válidos.");
}

// Conectar a la base de datos
include_once("conn/conexion.php");
include_once("index.php");

// Consulta preparada para validar el usuario y la contraseña
$sql = "SELECT * FROM user WHERE user = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró un registro que coincida
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['pass'];
    
    // Verificar la contraseña utilizando la función password_verify()
    if (password_verify($password, $storedPassword)) {
        // Iniciar sesión
        session_start();
        
        // Almacenar el nombre de usuario en la sesión
        $_SESSION['username'] = $username;
        
        // Almacenar la dirección IP del cliente en la sesión
        $sql = "UPDATE user SET ip = ? WHERE user = ?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $ip, $username);
        $stmt->execute();

        // Redirigir al usuario a la página indicada
        header("Location: parking/");
        exit();
    } else {
        mostrarError("El usuario o la contraseña son incorrectos.");
    }
} else {
    mostrarError("El usuario o la contraseña son incorrectos.");
}

// Función para mostrar mensajes de error y redirigir
function mostrarError($mensaje) {
    echo '
    <script>
    swal({
        title: "Control de Usuario!",
        text: "'. $mensaje .'",
        icon: "info",
        button: "Aceptar!",
      }).then(function() {
        window.location.href = "index.php";
      });
    </script>
    ';
    exit();
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();
?>
