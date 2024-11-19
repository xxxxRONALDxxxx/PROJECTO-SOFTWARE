<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Si no está logueado, redirigir a login
    exit();
}

include('db.php');

// Obtener el ID de la tarea a editar
if (isset($_GET['id_tarea'])) {
    $id_tarea = $_GET['id_tarea'];

    // Obtener los detalles de la tarea
    $sql_tarea = "SELECT * FROM tareas WHERE id_tarea = '$id_tarea'";
    $result_tarea = mysqli_query($conn, $sql_tarea);
    
    if (mysqli_num_rows($result_tarea) == 1) {
        $tarea = mysqli_fetch_assoc($result_tarea);
    } else {
        // Si no se encuentra la tarea, redirigir al dashboard
        header('Location: dashboard.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $estado = $_POST['estado'];  // Estado de la tarea (Pendiente o Completada)

    // Actualizar la tarea en la base de datos
    $sql_update = "UPDATE tareas SET titulo_tarea = '$titulo', descripcion_tarea = '$descripcion', estado_tarea = '$estado' WHERE id_tarea = '$id_tarea'";

    if (mysqli_query($conn, $sql_update)) {
        $success_message = "Tarea actualizada con éxito.";
    } else {
        $error_message = "Hubo un error al actualizar la tarea: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <h2>Editar Tarea</h2>

        <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

        <form method="POST">
            <div class="input-group">
                <label for="titulo">Título de la tarea</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo $tarea['titulo_tarea']; ?>" required>
            </div>

            <div class="input-group">
                <label for="descripcion">Descripción de la tarea</label>
                <textarea id="descripcion" name="descripcion" required><?php echo $tarea['descripcion_tarea']; ?></textarea>
            </div>

            <div class="input-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required>
                    <option value="Pendiente" <?php if ($tarea['estado_tarea'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="Completada" <?php if ($tarea['estado_tarea'] == 'Completada') echo 'selected'; ?>>Completada</option>
                </select>
            </div>

            <button type="submit">Actualizar Tarea</button>
        </form>

        <!-- Botón para regresar al Dashboard -->
        <a href="dashboard.php" class="regresar-btn">Regresar al Dashboard</a>
    </div>
</body>
</html>
