<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="bodyLogin">

<?php
session_start();
include("GestionBD/conexion.php");

// Inicializar errores si no están definidos
if (!isset($_SESSION['error_cambiar_contrasena'])) {
    $_SESSION['error_cambiar_contrasena'] = null;
}

// Verificar POST y validar acceso
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['correo'])) {
    header("Location: MainPage.php");
    exit;
}

$email = $_POST['correo'];

if (isset($_POST['actualizar'])) {
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    if ($nueva_contraseña === $confirmar_contraseña) {
        $hash_contraseña = password_hash($nueva_contraseña, PASSWORD_ARGON2ID);

        $consulta = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $consulta);
        mysqli_stmt_bind_param($stmt, "ss", $hash_contraseña, $email);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['error_cambiar_contrasena'] = "success";
            header("Location: MainPage.php");
            exit;
        } else {
            $_SESSION['error_cambiar_contrasena'] = "Error al actualizar la contraseña. Inténtalo nuevamente.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_cambiar_contrasena'] = "Las contraseñas no coinciden.";
    }
}else{
    unset($_SESSION['error_cambiar_contrasena']);
}
?>

<div class="principal">
    <form method="post" action="">
        <h1 class="titulo">Nueva Contraseña</h1>
        <br>
        <!-- Campo oculto para pasar el correo al formulario -->
        <input type="hidden" name="correo" value="<?php echo htmlspecialchars($email); ?>">
        
        <div class="wave-group">
            <input required="true" type="password" id="password" name="nueva_contraseña" class="input" placeholder="Nueva Contraseña">
            <input type="checkbox" id="togglePassword"> Mostrar
            <span class="bar"></span>
        </div>
        <script>
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            togglePassword.addEventListener('change', () => {
                passwordField.type = togglePassword.checked ? 'text' : 'password';
            });
        </script>
        <br>
        <div class="wave-group">
            <input required="true" type="password" name="confirmar_contraseña" class="input" placeholder="Confirmar Contraseña">
            <span class="bar"></span>
        </div>
        <br>
        <input type="submit" name="actualizar" class="registro" value="Actualizar Contraseña">
        <br><br>
        <a href="MainPage.php" class="volver">Volver</a>
    </form>
</div>

<div class="errores">
    <?php
    if (!empty($_SESSION['error_cambiar_contrasena'])) {
        $tipo = $_SESSION['error_cambiar_contrasena'] === "success" ? "verde" : "rojo";
        echo "<h3 class='{$tipo}'>" . htmlspecialchars($_SESSION['error_cambiar_contrasena']) . "</h3>";
        unset($_SESSION['error_cambiar_contrasena']);
    }
    ?>
</div>

</body>
</html>
