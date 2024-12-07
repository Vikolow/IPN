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
// Incluye la conexión con la base de datos
include("GestionBD/conexion.php");

// Verifica que el correo se haya recibido por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['correo'])) {
    $email = $_POST['correo'];
} else {
    echo "<script>alert('Acceso no autorizado.');</script>";
    echo "<script>window.location.href = 'MainPage.php';</script>";
    exit;
}

// Procesa el formulario de actualización de contraseña
if (isset($_POST['actualizar'])) {
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Verifica que ambas contraseñas coincidan
    if ($nueva_contraseña === $confirmar_contraseña) {
        // Genera el hash de la nueva contraseña
        $hash_contraseña = password_hash($nueva_contraseña, PASSWORD_ARGON2ID);

        // Actualiza la contraseña en la base de datos
        $consulta = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $consulta);
        mysqli_stmt_bind_param($stmt, "ss", $hash_contraseña, $email);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Contraseña actualizada correctamente.');</script>";
            echo "<script>window.location.href = 'MainPage.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la contraseña. Intentalo nuevamente.');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    }
}
?>

<div class="principal">
    <form method="post" action="">
        <h1 class="titulo">Nueva Contraseña</h1>
        <br>
        <!-- Campo oculto para pasar el correo al formulario -->
        <input type="hidden" name="correo" value="<?php echo htmlspecialchars($email); ?>">
        
        <div class="wave-group">
            <input required="true" type="password" id="password" name="nueva_contraseña" class="input" placeholder="Nueva Contraseña"><input type="checkbox" id="togglePassword"> Mostrar 
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

</body>
</html>
