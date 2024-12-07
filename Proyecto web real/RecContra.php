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
        // Conectamos con la base de datos
        include("GestionBD/conexion.php");

        // Recojemos los datos del usuario
        if (isset($_REQUEST['enviar'])) {
            $usuario_correo = $_REQUEST['correo'];
            $pregunta_seguridad = $_REQUEST['pregunta'];
            $respuesta_seguridad = $_REQUEST['respuesta'];

            // Consultamos si el correo y la pregunta de seguridad coinciden en la base de datos
            $consulta_usuario = "SELECT respuesta_seguridad FROM usuarios WHERE email = ? AND pregunta_seguridad = ?";
            $stmt = mysqli_prepare($conn, $consulta_usuario);
            mysqli_stmt_bind_param($stmt, "ss", $usuario_correo, $pregunta_seguridad);
            mysqli_stmt_execute($stmt);
            $resultado_usuario = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado_usuario) > 0) {
                $fila = mysqli_fetch_assoc($resultado_usuario);
                $hash_respuesta = $fila['respuesta_seguridad'];

                // Verificamos si la respuesta ingresada coincide con el hash
                if (password_verify($respuesta_seguridad, $hash_respuesta)) {
                       // Crear formulario oculto para redirección por POST
                        echo '<form id="redireccion_form" method="POST" action="procesaCambioContra.php" style="display:none;">
                        <input type="hidden" name="correo" value="' . htmlspecialchars($usuario_correo) . '">
                      </form>';
                    echo '<script>document.getElementById("redireccion_form").submit();</script>';
                    exit;
                } else {
                    ?>
                    <script> alert('Error: La respuesta de seguridad no es correcta.');</script>
                    <?php
                }
            } else {
                ?>
                <script> alert('Error: No existe el usuario o la pregunta de seguridad no coincide.');</script>
                <?php
            }

            mysqli_stmt_close($stmt);
        }
    ?>

    <div class="principal">
        <form method="post" action="">
            <h1 class="titulo">Recuperar<br>contraseña</h1>
            <br>
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
            <input type="submit" name="enviar" class="registro" value="Enviar">
            <br><br>
            <a href="MainPage.php" class="volver"> Volver</a>
        </form>
    </div>
</body>
</html>
