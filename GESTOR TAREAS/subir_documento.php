<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'];
    $archivo = $_FILES['documento'];

    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $nombre_tmp = $archivo['tmp_name'];
        $nombre_destino = 'documentos/' . basename($archivo['name']);
        if (move_uploaded_file($nombre_tmp, $nombre_destino)) {
            // Actualizar la tarea con la ruta del documento
            $sql = "UPDATE tareas SET documento = '$nombre_destino' WHERE id_tarea = $id_tarea";
            if (mysqli_query($conn, $sql)) {
                header('Location: dashboard.php');
                exit();
            } else {
                echo "Error al actualizar la base de datos.";
            }
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
