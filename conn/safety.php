<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo 'Bienvenido, ' . $username;
} else {
    header("Location: ../index.php");
}

// Configura la clave secreta única para firmar los datos de sesión
define('SESSION_SECRET_KEY', '5#ZrP@1*o!9E7%m6q2&s');

// Verifica la dirección IP del cliente
if (isset($_SESSION['client_ip']) && $_SESSION['client_ip'] !== $_SERVER['REMOTE_ADDR']) {
    // La dirección IP ha cambiado, posible suplantación de sesión
    session_unset();
    session_destroy();
    mostrarError("Sesión inválida. Por favor, inicia sesión nuevamente.");
}

// Verifica la validez de la sesión
if (!isset($_SESSION['username']) || !isset($_SESSION['token'])) {
    mostrarError("Sesión inválida. Por favor, inicia sesión.");
}

// Verifica el token de sesión para proteger contra ataques CSRF
if ($_SESSION['token'] !== generarToken()) {
    session_unset();
    session_destroy();
    mostrarError("Sesión inválida. Por favor, inicia sesión nuevamente.");
}

// Genera un nuevo token de sesión y regenera la ID de sesión
function regenerarSesion() {
    session_regenerate_id(true);
    $_SESSION['token'] = generarToken();
}

// Genera un token único basado en la clave secreta y otros datos de la sesión
function generarToken() {
    $tokenData = $_SESSION['username'] . $_SESSION['client_ip'] . SESSION_SECRET_KEY;
    return hash('sha256', $tokenData);
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
?>
