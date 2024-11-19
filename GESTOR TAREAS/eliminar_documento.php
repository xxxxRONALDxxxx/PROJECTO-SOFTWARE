<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'];

    // Obtener la ruta del documento
    $sql = "SELECT documento FROM tareas WHERE id_tarea = $id_tarea";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $tarea = mysqli_fetch_assoc($result);
        $ruta_documento = $tarea['documento'];

        // Eliminar archivo del servidor
        if (file_exists($ruta_documento)) {
            unlink($ruta_documento);
        }

        // Eliminar la referencia en la base de datos
        $sql_delete = "UPDATE tareas SET documento = NULL WHERE id_tarea = $id_tarea";
        if (mysqli_query($conn, $sql_delete)) {
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error al eliminar el documento de la base de datos.";
        }
    } else {
        echo "Documento no encontrado.";
    }
}
?>
