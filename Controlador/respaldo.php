<?php
session_start(); // Activa las sesiones, asegurando que puedas almacenar información en $_SESSION

// Configuración de la base de datos
$host = 'localhost'; // Dirección del servidor MySQL
$user = 'root'; // Nombre de usuario para la base de datos
$password = ''; // Contraseña del usuario MySQL, asegúrate de colocar la correcta aquí
$port = 3307; // Puerto de conexión al servidor MySQL (en XAMPP, el puerto por defecto es 3307)
$database = 'claus'; // Nombre de la base de datos que se va a respaldar

// Ruta del archivo de respaldo
$backupFile = '../respaldos/claus_' . date('Y-m-d_H-i-s') . '.sql'; // Nombre del archivo de respaldo con la fecha y hora actual

// Ruta al ejecutable mysqldump en XAMPP (Asegúrate de que la ruta sea correcta en tu máquina)
$mysqldumpPath = 'D:/xampp/mysql/bin/mysqldump'; // Ruta del ejecutable mysqldump de MySQL

// Comando para realizar el respaldo utilizando mysqldump
$command = "\"$mysqldumpPath\" --opt -h $host -P $port -u $user" . (!empty($password) ? " -p$password" : "") . " $database > \"$backupFile\"";

// Ejecuta el comando para crear el respaldo
$output = []; // Array para almacenar la salida del comando
$returnVar = null; // Variable para almacenar el código de retorno del comando
exec($command . ' 2>&1', $output, $returnVar); // Ejecuta el comando y captura la salida

// Depuración: Verifica si el archivo se ha creado correctamente
echo "Ruta del archivo de respaldo: $backupFile<br>"; // Muestra la ruta del archivo generado
echo "Output del comando: " . implode("\n", $output) . "<br>"; // Muestra cualquier salida o error del comando
echo "Código de retorno del comando: $returnVar<br>"; // Muestra el código de retorno del comando

// Verifica si el archivo fue creado exitosamente y tiene contenido
if (file_exists($backupFile) && filesize($backupFile) > 0) {
    $_SESSION['alert'] = 'success'; // Establece una sesión de éxito
    $_SESSION['msj2'] = "Respaldo de la base de datos '$database' realizado con éxito en '$backupFile'."; // Mensaje de éxito
} else {
    $_SESSION['alert'] = 'error'; // Establece una sesión de error
    $_SESSION['msj2'] = "Error al realizar el respaldo de la base de datos. Detalles del error: " . implode("\n", $output); // Mensaje de error con los detalles
}

// Redirige al administrador de la vista de inicio
header('Location: ../Vista/IndexAdmin.php'); // Redirige a la página principal del administrador
exit(); // Termina el script
?>
