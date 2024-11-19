<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);
    $nombre_usuario = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
    $email_usuario = mysqli_real_escape_string($conn, $_POST['email_usuario']);
    $celular_usuario = mysqli_real_escape_string($conn, $_POST['celular_usuario']);
    $direccion_usuario = mysqli_real_escape_string($conn, $_POST['direccion_usuario']);
    $clave_usuario = mysqli_real_escape_string($conn, $_POST['clave_usuario']);
    $clave_encriptada = md5($clave_usuario);
    
    $sql = "INSERT INTO usuarios (id_usuario, nombre_usuario, email_usuario, celular_usuario, direccion_usuario, clave_usuario, id_rol_usuario) 
            VALUES ('$id_usuario', '$nombre_usuario', '$email_usuario', '$celular_usuario', '$direccion_usuario', '$clave_encriptada', 2)"; // 2 para empleado
    
    if (mysqli_query($conn, $sql)) {
        $success_message = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        header('Location: index.php'); // Redirigir a la página de inicio después del registro
        exit();
    } else {
        $error = "Error al registrar el usuario: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%); /* Centrado vertical */
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="left-container">
        <img src="logo.png" alt="Logo" class="logo">
    </div>
    <div class="right-container">
        <div class="login-container">
            <h2>Registrar Usuario</h2>

            <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>

            <form method="POST">
                <div class="input-group">
                    <label for="id_usuario">Número de Identificación</label>
                    <input type="text" id="id_usuario" name="id_usuario" placeholder="Número de identificación" required>
                </div>
                <div class="input-group">
                    <label for="nombre_usuario">Nombre Completo</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre completo" required>
                </div>
                <div class="input-group">
                    <label for="email_usuario">Correo Electrónico</label>
                    <input type="email" id="email_usuario" name="email_usuario" placeholder="Correo electrónico" required>
                </div>
                <div class="input-group">
                    <label for="celular_usuario">Número de Celular</label>
                    <input type="text" id="celular_usuario" name="celular_usuario" placeholder="Número de celular" required>
                </div>
                <div class="input-group">
                    <label for="direccion_usuario">Dirección</label>
                    <input type="text" id="direccion_usuario" name="direccion_usuario" placeholder="Dirección" required>
                </div>
                <div class="input-group">
                    <label for="clave_usuario">Contraseña</label>
                    <input type="password" id="clave_usuario" name="clave_usuario" placeholder="Contraseña" required>
                    <span id="togglePassword" class="eye-icon">👁️</span>
                </div>
                <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
                <button type="submit" class="register-btn">Registrar</button>
            </form>
        </div>
    </div>

    <!-- Script para alternar la visibilidad de la contraseña -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('clave_usuario');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
