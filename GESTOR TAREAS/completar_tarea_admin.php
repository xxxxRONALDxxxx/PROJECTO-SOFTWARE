<?php
session_start();
include('db.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

// Verificar si el usuario es administrador
$sql_admin = "SELECT id_rol_usuario FROM usuarios WHERE id_usuario = '{$_SESSION['usuario']}'";
$result_admin = mysqli_query($conn, $sql_admin);
$user = mysqli_fetch_assoc($result_admin);
if ($user['id_rol_usuario'] != 1) {
    echo "Acceso denegado.";
    exit();
}

// Verificar que el parámetro id_tarea esté presente
if (isset($_GET['id_tarea']) && is_numeric($_GET['id_tarea'])) {
    $id_tarea = $_GET['id_tarea'];

    // Actualizar el estado de la tarea a 'Completada por admin'
    $sql_update = "UPDATE tareas SET estado_tarea = 'Completada por admin' WHERE id_tarea = $id_tarea";
    if (mysqli_query($conn, $sql_update)) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error al completar la tarea.";
    }
} else {
    echo "ID de tarea no válido.";
}
?>
