<?php
session_start(); // Activa las sesiones

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = ''; // Asegúrate de poner la contraseña correcta de MySQL aquí
$port = 3307; // Cambia el puerto si es necesario
$database = 'claus';

// Verifica si se ha enviado el archivo para restaurar
if (isset($_FILES['backup_db_file'])) {
    $backupDbFile = $_FILES['backup_db_file']['tmp_name']; // Ruta temporal del archivo
    $mysqldumpPath = 'D:/xampp/mysql/bin/mysqldump';
    $fileName = $_FILES['backup_db_file']['name']; // Nombre del archivo

    // Verifica si el archivo existe
    if (file_exists($backupDbFile)) {
        $command = "\"D:/xampp/mysql/bin/mysql\" -h $host -P $port -u $user" . (!empty($password) ? " -p$password" : "") . " $database < \"$backupDbFile\"";

        // Depuración: Verificar el comando que se va a ejecutar
        echo "Comando que se ejecutará: $command<br>";

        // Ejecuta el comando para restaurar la base de datos
        $outputDb = [];
        $returnVarDb = null;
        exec($command . ' 2>&1', $outputDb, $returnVarDb);

        // Depuración: Verifica el retorno del comando
        echo "Código de retorno: $returnVarDb<br>";
        echo "Salida del comando: " . implode("\n", $outputDb) . "<br>";

        if ($returnVarDb !== 0) {
            // Si ocurre un error
            $_SESSION['alert'] = 'error';
            $_SESSION['msj2'] = "Error al restaurar la base de datos. Detalles del error: " . implode("\n", $outputDb);
            header('Location: ../Vista/IndexAdmin.php');
            exit();
        } else {
            // Si la restauración fue exitosa
            $_SESSION['alert'] = 'success';
            $_SESSION['msj2'] = "Restauración de la base de datos '$database' realizada con éxito desde '$fileName'.";
            header('Location: ../Vista/IndexAdmin.php');
            exit();
        }
    } else {
        // Si el archivo no existe
        $_SESSION['alert'] = 'error';
        $_SESSION['msj2'] = "El archivo de respaldo de la base de datos no existe.";
        header('Location: ../Vista/IndexAdmin.php');
        exit();
    }
} else {
    // Si no se ha enviado un archivo
    $_SESSION['alert'] = 'error';
    $_SESSION['msj2'] = "No se seleccionaron archivos para restaurar.";
    header('Location: ../Vista/IndexAdmin.php');
    exit();
}
?>
