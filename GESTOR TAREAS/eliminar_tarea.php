<?php
include('db.php');

// Verificar que el id_tarea se ha pasado correctamente
if (isset($_GET['id_tarea'])) {
    $id_tarea = $_GET['id_tarea'];

    // Eliminar documentos relacionados primero
    $sql_delete_documents = "DELETE FROM documentos WHERE id_tarea = ?";
    $stmt = mysqli_prepare($conn, $sql_delete_documents);
    mysqli_stmt_bind_param($stmt, "i", $id_tarea);
    mysqli_stmt_execute($stmt);

    // Luego eliminar la tarea
    $sql_delete_task = "DELETE FROM tareas WHERE id_tarea = ?";
    $stmt = mysqli_prepare($conn, $sql_delete_task);
    mysqli_stmt_bind_param($stmt, "i", $id_tarea);
    mysqli_stmt_execute($stmt);

    // Verificar si la eliminación fue exitosa
    if (mysqli_affected_rows($conn) > 0) {
        echo "Tarea eliminada correctamente.";
    } else {
        echo "Error al eliminar la tarea.";
    }
} else {
    echo "ID de tarea no válido.";
}
?>

<!-- Botón de regreso -->
<div>
    <a href="dashboard.php" class="regresar-btn">Regresar al Dashboard</a>
</div>

<style>
    .regresar-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .regresar-btn:hover {
        background-color: #45a049;
    }
</style>
