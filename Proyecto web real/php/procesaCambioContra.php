<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="icon" type="image/x-icon" href="../img/Logo_IPN.png" />
</head>
<body class="bodyLogin">

<?php
//Inicia la sesion
session_start();
//Incluye el archivo de conexion a BD
include("../GestionBD/conexion.php");

// Inicializar errores si no están definidos
if (!isset($_SESSION['error_cambiar_contrasena'])) {
    $_SESSION['error_cambiar_contrasena'] = null;
}

// Verificar que los datos se han enviado por POST y validar acceso
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['correo'])) {
    $_SESSION['error_cambiar_contrasena'] = "Acceso no autorizado.";
    header("Location: MainPage.php");
    exit;
}
//Sanitiza el correo electronico recibido
$email = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL); 

//Procesa el formulario si se pulsa el boton
if (isset($_POST['actualizar'])) {
    //Recoge las contraseñas introducidas por el usuario
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];
    
    //Verifica que las contraseñas coincidan
    if ($nueva_contraseña === $confirmar_contraseña) {
        $hash_contraseña = password_hash($nueva_contraseña, PASSWORD_ARGON2ID);

        //Consulta para actualizar la contraeña en la BD
        $consulta = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $consulta);
        mysqli_stmt_bind_param($stmt, "ss", $hash_contraseña, $email);
        
        //Ejecuta la consulta y verifica el resultado
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['error_cambiar_contrasena'] = "success"; //Indica exito en el cambio
            header("Location: MainPage.php"); //Redirige a la pagina principal
            exit;
        } else {
            //Indica un fallo si la actualizacion falla 
            $_SESSION['error_cambiar_contrasena'] = "Error al actualizar la contraseña. Inténtalo nuevamente.";
        }
        mysqli_stmt_close($stmt);
    } else {
        //Si las contraseñas no coinciden muestra un mensaje de error
        $_SESSION['error_cambiar_contrasena'] = "Las contraseñas no coinciden.";
    }
}else{
    //Limpia la variable de error si no esta procesando la accion del formulario
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
            //Logica js  para mostrar/ocultar contraseña
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
<!-- Contenedor para mostrar errores o mensajes -->
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
