<?php
session_start();
include('db.php');

// Verificar si el usuario está autenticado y si es un empleado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Redirigir si no está logueado
    exit();
}

if (isset($_GET['id_tarea'])) {
    $id_tarea = $_GET['id_tarea'];

    // Actualizar el estado de la tarea a 'Completada por empleado'
    $sql_actualizar_estado = "UPDATE tareas SET estado_tarea = 'Completada por empleado' WHERE id_tarea = '$id_tarea' AND id_usuario = '{$_SESSION['usuario']}'";
    
    if (mysqli_query($conn, $sql_actualizar_estado)) {
        header("Location: dashboard.php"); // Redirigir al dashboard después de completar la tarea
    } else {
        echo "Error al actualizar el estado de la tarea.";
    }
}
?>
