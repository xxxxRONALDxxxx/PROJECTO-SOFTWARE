<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php'); // Si no está logueado, redirigir a login
    exit();
}

include('db.php');

// Verificar si el usuario es un administrador
$is_admin = false;
$sql_admin = "SELECT id_rol_usuario FROM usuarios WHERE id_usuario = '{$_SESSION['usuario']}'";
$result_admin = mysqli_query($conn, $sql_admin);
if ($result_admin && mysqli_num_rows($result_admin) == 1) {
    $user = mysqli_fetch_assoc($result_admin);
    if ($user['id_rol_usuario'] == 1) {
        $is_admin = true; // Es un administrador
    }
}

// Obtener las tareas (solo si el usuario es admin o sus propias tareas si es empleado)
if ($is_admin) {
    $sql_tareas = "SELECT t.id_tarea, t.titulo_tarea, t.descripcion_tarea, t.fecha_creacion, t.estado_tarea, t.documento, u.nombre_usuario 
                   FROM tareas t
                   INNER JOIN usuarios u ON t.id_usuario = u.id_usuario";
} else {
    $sql_tareas = "SELECT t.id_tarea, t.titulo_tarea, t.descripcion_tarea, t.fecha_creacion, t.estado_tarea, t.documento
                   FROM tareas t WHERE t.id_usuario = '{$_SESSION['usuario']}'";
}

$result_tareas = mysqli_query($conn, $sql_tareas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard">
        <h2>Bienvenido al Dashboard</h2>

        <a href="logout.php">Cerrar sesión</a>

        <!-- Botón para crear tarea (solo si el usuario es admin) -->
        <?php if ($is_admin): ?>
            <a href="crear_tarea.php" class="crear-tarea-btn">Crear Tarea</a>
        <?php endif; ?>

        <h3>Lista de Tareas</h3>
        <?php if (mysqli_num_rows($result_tareas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Tarea</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha de Creación</th>
                        <th>Estado</th>
                        <?php if ($is_admin) { echo "<th>Empleado</th>"; } ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($tarea = mysqli_fetch_assoc($result_tareas)): ?>
                        <tr>
                            <td><?php echo $tarea['id_tarea']; ?></td>
                            <td><?php echo $tarea['titulo_tarea']; ?></td>
                            <td><?php echo $tarea['descripcion_tarea']; ?></td>
                            <td><?php echo $tarea['fecha_creacion']; ?></td>
                            <td>
                                <?php if ($tarea['estado_tarea'] == 'Completada por admin'): ?>
                                    <span>✓✓</span> <!-- Dos chulitos si completada por admin -->
                                <?php elseif ($tarea['estado_tarea'] == 'Completada por empleado'): ?>
                                    <span>✓</span> <!-- Un chulito si completada por el empleado -->
                                <?php else: ?>
                                    <?php echo $tarea['estado_tarea']; ?> <!-- Mostrar estado si no está completada -->
                                <?php endif; ?>
                            </td>
                            <?php if ($is_admin): ?>
                                <td><?php echo $tarea['nombre_usuario']; ?></td>
                            <?php endif; ?>
                            <td>
                                <!-- Solo el administrador puede ver el enlace para editar -->
                                <?php if ($is_admin): ?>
                                    <a href="editar_tarea.php?id_tarea=<?php echo $tarea['id_tarea']; ?>">Editar</a>
                                    <a href="eliminar_tarea.php?id_tarea=<?php echo $tarea['id_tarea']; ?>">Eliminar</a>
                                    <!-- El admin puede marcar como completada la tarea -->
                                    <?php if ($tarea['estado_tarea'] == 'Completada por empleado'): ?>
                                        <a href="completar_tarea_admin.php?id_tarea=<?php echo $tarea['id_tarea']; ?>">Marcar completada</a>
                                    <?php endif; ?>

                                    <!-- Ver documento subido por empleado -->
                                    <?php if ($tarea['documento']): ?>
                                        <p>Documento: <a href="<?php echo $tarea['documento']; ?>" target="_blank">Ver documento</a></p>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- Acciones específicas para empleados -->
                                <?php if (!$is_admin): ?>
                                    <?php if ($tarea['estado_tarea'] == 'Pendiente'): ?>
                                        <a href="completar_tarea.php?id_tarea=<?php echo $tarea['id_tarea']; ?>">Marcar como completada</a>
                                    <?php else: ?>
                                        <span>Completada</span>
                                    <?php endif; ?>

                                    <!-- Manejo de documentos -->
                                    <?php if ($tarea['documento']): ?>
                                        <!-- Mostrar documento subido -->
                                        <p>Documento: <a href="<?php echo $tarea['documento']; ?>" target="_blank">Ver documento</a></p>
                                        <form action="eliminar_documento.php" method="POST">
                                            <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este documento?')">Cancelar envío</button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Formulario para subir documento -->
                                        <form action="subir_documento.php" method="POST" enctype="multipart/form-data">
                                            <label for="documento">Subir documento:</label>
                                            <input type="file" name="documento" id="documento" required>
                                            <input type="hidden" name="id_tarea" value="<?php echo $tarea['id_tarea']; ?>">
                                            <button type="submit">Subir</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay tareas asignadas.</p>
        <?php endif; ?>
    </div>
</body>
</html>
