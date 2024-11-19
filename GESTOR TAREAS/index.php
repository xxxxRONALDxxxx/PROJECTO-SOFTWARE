<?php
session_start();

// Verificar si el usuario ya está logueado
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');  // Redirigir a dashboard si está logueado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');
    
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $clave = mysqli_real_escape_string($conn, $_POST['clave']);
    $clave_encriptada = md5($clave);

    $sql = "SELECT * FROM usuarios WHERE id_usuario = '$usuario' AND clave_usuario = '$clave_encriptada'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        // Guardar los datos del usuario en la sesión
        $_SESSION['usuario'] = $usuario;
        // Obtener el rol del usuario para diferenciar acceso a diferentes áreas
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id_rol_usuario'] = $row['id_rol_usuario'];

        // Redirigir al dashboard dependiendo del rol
        if ($_SESSION['id_rol_usuario'] == 1) {
            header('Location: dashboard.php');  // Admin
        } else {
            header('Location: dashboard.php');  // Usuario normal
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
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
            <h2>Iniciar sesión</h2>
            <form method="POST">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
                </div>
                <div class="input-group">
                    <label for="clave">Contraseña</label>
                    <input type="password" id="clave" name="clave" placeholder="Ingresa tu contraseña" required>
                    <span id="togglePassword" class="eye-icon">👁️</span>
                </div>
                <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
                <button type="submit" class="login-btn">Ingresar</button>
                <a href="registro.php" class="register">¿No tienes cuenta? Regístrate</a>
            </form>
        </div>
    </div>

    <!-- Script para alternar la visibilidad de la contraseña -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('clave');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
