<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="bodyLogin">

<?php
    // Inicia la sesión
    session_start();

    // Conectamos con la base de datos
    include("GestionBD/conexion.php");

    // Inicializar variable de error si no está definida
    if (!isset($_SESSION['error_recuperar'])) {
        $_SESSION['error_recuperar'] = null;
    }
    // Procesar formulario si se ha enviado
    if (isset($_POST['enviar'])) {
        //Recoge los datos ingresados por el usuario
        $usuario_correo = $_POST['correo'];
        $pregunta_seguridad = $_POST['pregunta'];
        $respuesta_seguridad = $_POST['respuesta'];

        // Consulta para verificar el correo , pregunta y respuesta de seguridad
        $consulta_usuario = "SELECT respuesta_seguridad FROM usuarios WHERE email = ? AND pregunta_seguridad = ?";
        $stmt = mysqli_prepare($conn, $consulta_usuario);
        mysqli_stmt_bind_param($stmt, "ss", $usuario_correo, $pregunta_seguridad);
        mysqli_stmt_execute($stmt);
        $resultado_usuario = mysqli_stmt_get_result($stmt);
        
        //Verificar si se encontro un usuario valido
        if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
            $fila = mysqli_fetch_assoc($resultado_usuario);
            $hash_respuesta = $fila['respuesta_seguridad'];

            // Verificar la respuesta de seguridad coincide con el hash almacenado
            if (password_verify($respuesta_seguridad, $hash_respuesta)) {
                $_SESSION['error_recuperar'] = 2; 
                // Redirigir a la página para cambiar contraseña
                echo '<form id="redireccion_form" method="POST" action="procesaCambioContra.php" style="display:none;">
                        <input type="hidden" name="correo" value="' . htmlspecialchars($usuario_correo) . '">
                      </form>';
                echo '<script>document.getElementById("redireccion_form").submit();</script>';
                exit;
            }else{
                //Establece un error en error_recuperar si la respuesta no coincide
                $_SESSION['error_recuperar'] = 1;  
            }
        }else{
            //Establece un error en error_recuperar si no se encuentra usuario o la pregunta no conicide 
            $_SESSION['error_recuperar'] = 1;  
        }
        
        mysqli_stmt_close($stmt);
    }else{
        //Limpia el contenido de la variable si no se envio el formulario
        unset($_SESSION['error_recuperar']);
    }
?>
<!-- Contenedor principal del formulario -->
<div class="principal">
    <form method="post" action="">
        <h1 class="titulo">Recuperar<br>contraseña</h1>
        <br>
         <!-- Campo para ingresar el correo electrónico -->
        <div class="wave-group">
            <input required="true" type="email" name="correo" class="input">
            <span class="bar"></span>
            <label class="label">
                <span class="label-char" style="--index: 0">C</span>
                <span class="label-char" style="--index: 1">o</span>
                <span class="label-char" style="--index: 2">r</span>
                <span class="label-char" style="--index: 3">r</span>
                <span class="label-char" style="--index: 4">e</span>
                <span class="label-char" style="--index: 5">o</span>
            </label>
        </div>
        <br>
         <!-- Menú desplegable para seleccionar la pregunta de seguridad -->
        <div class="wave-group">
            <select required="true" name="pregunta" class="input">
                <option value="" disabled selected>Selecciona tu pregunta de seguridad</option>
                <option value="¿Cuál es tu apodo?">¿Cuál es tu apodo?</option>
                <option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
                <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
            </select>
            <span class="bar"></span>
        </div>
        <br>
          <!-- Campo para ingresar la respuesta de seguridad -->
        <div class="wave-group">
            <input required="true" type="text" name="respuesta" class="input">
            <span class="bar"></span>
            <label class="label">
                <span class="label-char" style="--index: 0">R</span>
                <span class="label-char" style="--index: 1">e</span>
                <span class="label-char" style="--index: 2">s</span>
                <span class="label-char" style="--index: 3">p</span>
                <span class="label-char" style="--index: 4">u</span>
                <span class="label-char" style="--index: 5">e</span>
                <span class="label-char" style="--index: 6">s</span>
                <span class="label-char" style="--index: 7">t</span>
                <span class="label-char" style="--index: 8">a</span>
            </label>
        </div>
        <br>
        <!-- Botón para enviar el formulario -->
        <input type="submit" name="enviar" class="registro" value="Enviar">
        <br><br>
        <a href="MainPage.php" class="volver">Volver</a>
    </form>
</div>
<!-- Muestra errores o mensajes según el estado -->
<div class="errores">
    <?php
    if (isset($_SESSION['error_recuperar'])) {
        switch ($_SESSION['error_recuperar']) {
            // Muestra error si las credenciales son incorrectas
            case 1:
                echo "<h3 class='rojo'> Error: Credenciales incorrectas.</h3>";
                break;
            // Muestra mensaje de redirección exitosa
            case 2:
                echo "<h3 class='verde'> Redirigiendo...</h3>";
                break;
        }
        // Limpia el error después de mostrarlo
        unset($_SESSION['error_recuperar']);
        // Limpia la URL para evitar reenvío del formulario
        echo '<script>history.replaceState({}, "", "' . $_SERVER['PHP_SELF'] . '");</script>';
    }
    
    ?>
</div>
</body>
</html>
