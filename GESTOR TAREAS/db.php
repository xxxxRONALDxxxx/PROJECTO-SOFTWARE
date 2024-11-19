<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestortareas";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
