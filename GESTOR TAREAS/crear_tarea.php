<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Si no está logueado, redirigir a login
    exit();
}

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['id_rol_usuario'] != 1) {
    header('Location: dashboard.php'); // Si no es admin, redirigir al dashboard
    exit();
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $usuario_id = $_POST['id_usuario'];  // El id del usuario al que se le asignará la tarea
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $fecha_creacion = date('Y-m-d H:i:s');  // Fecha y hora actual
    $estado = $_POST['estado'];  // Estado de la tarea (Pendiente o Completada)

    // Insertar la tarea en la base de datos
    $sql = "INSERT INTO tareas (id_usuario, titulo_tarea, descripcion_tarea, fecha_creacion, estado_tarea) 
            VALUES ('$usuario_id', '$titulo', '$descripcion', '$fecha_creacion', '$estado')";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Tarea creada con éxito.";
    } else {
        $error_message = "Hubo un error al crear la tarea: " . mysqli_error($conn);
    }
}

// Obtener todos los usuarios (excepto el administrador)
$sql_usuarios = "SELECT id_usuario, nombre_usuario FROM usuarios WHERE id_rol_usuario = 2";  // Solo empleados
$result_usuarios = mysqli_query($conn, $sql_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <h2>Crear Nueva Tarea</h2>

        <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

        <form method="POST">
            <div class="input-group">
                <label for="id_usuario">Seleccionar Empleado</label>
                <select name="id_usuario" id="id_usuario" required>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_usuarios)) {
                        echo "<option value='" . $row['id_usuario'] . "'>" . $row['nombre_usuario'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label for="titulo">Título de la tarea</label>
                <input type="text" id="titulo" name="titulo" placeholder="Título de la tarea" required>
            </div>

            <div class="input-group">
                <label for="descripcion">Descripción de la tarea</label>
                <textarea id="descripcion" name="descripcion" placeholder="Descripción de la tarea" required></textarea>
            </div>

            <div class="input-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Completada">Completada</option>
                </select>
            </div>

            <button type="submit">Crear Tarea</button>
        </form>

        <a href="dashboard.php">Volver al Dashboard</a>
    </div>
</body>
</html>
