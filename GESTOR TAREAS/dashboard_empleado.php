// dashboard_empleado.php (modificado para incluir opción de subir archivo)

<?php
session_start();
include('db.php');

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header('Location: index.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$sql = "SELECT * FROM tareas WHERE id_usuario = '{$_SESSION['id_usuario']}' ORDER BY fecha_creacion DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empleado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Bienvenido al Dashboard de Empleado</h2>
    <h3>Lista de Tareas Asignadas</h3>
    <table>
        <thead>
            <tr>
                <th>ID Tarea</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id_tarea']; ?></td>
                        <td><?php echo $row['titulo_tarea']; ?></td>
                        <td><?php echo $row['descripcion_tarea']; ?></td>
                        <td><?php echo $row['fecha_creacion']; ?></td>
                        <td><?php echo $row['estado_tarea']; ?></td>
                        <td>
                            <?php if ($row['estado_tarea'] != 'Completada'): ?>
                                <form action="marcar_completada.php" method="POST">
                                    <input type="hidden" name="id_tarea" value="<?php echo $row['id_tarea']; ?>">
                                    <button type="submit">Marcar como Completada</button>
                                </form>
                            <?php endif; ?>

                            <form action="subir_documento.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_tarea" value="<?php echo $row['id_tarea']; ?>">
                                <input type="file" name="documento" required>
                                <button type="submit">Subir Documento</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No tienes tareas asignadas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
