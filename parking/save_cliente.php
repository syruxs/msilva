<?php
include_once("../conn/conexion.php");

// Obtener los datos del formulario y sanitizarlos
$nombre = isset($_POST['nombre']) ? sanitizeInput($_POST['nombre']) : '';
$rut = isset($_POST['rut']) ? sanitizeInput($_POST['rut']) : '';
$telefono = isset($_POST['telefono']) ? sanitizeInput($_POST['telefono']) : '';
$correo = isset($_POST['correo']) ? sanitizeInput($_POST['correo']) : '';

// Validar los datos ingresados
$errors = array();

if (empty($nombre)) {
  $errors[] = 'Debes ingresar un nombre.';
}

// Agrega más validaciones según tus requisitos

// Verificar si existen errores
if (!empty($errors)) {
  http_response_code(400); // Código de respuesta de error
  echo 'Error: ' . implode(' ', $errors);
  exit; // Terminar la ejecución del script
}

// Realizar las operaciones necesarias con los datos

try {
  $stmt = $conn->prepare("INSERT INTO clientes (nombre, rut, telefono, correo) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $nombre, $rut, $telefono, $correo);
  $stmt->execute();
  
  echo 'Los datos se han guardado correctamente.';
} catch(mysqli_sql_exception $e) {
  http_response_code(500); // Código de respuesta de error del servidor
  echo 'Error en el servidor: ' . $e->getMessage();
  exit;
}

// Función para sanitizar los datos ingresados por el usuario
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    // Aplica más sanitizaciones según tus necesidades
    
    return $input;
  }
?>
