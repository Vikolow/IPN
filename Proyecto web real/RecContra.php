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

        //Conectamos con la base de datos
        include("GestionBD/conexion.php");

        //Recojemos el 
        if (isset($_REQUEST['enviar'])){
            $usuario_correo = $_REQUEST['correo'];
            $Consulta_existe_correo = "SELECT * FROM usuarios WHERE email = '$usuario_correo'";
            $Resultado_usuario_correo=mysqli_query($conn,$Consulta_existe_correo);
            
            //en caso de que el usuario exista crea una sentencia para cambiar la contraseña
            if(mysqli_num_rows($Resultado_usuario_correo) > 0){

                //Recojemos y encriptamos la variable
                $nueva_contrasena= $_REQUEST['newpassword'];
                $newPasswordHash = password_hash($nueva_contrasena, PASSWORD_ARGON2ID);

                //sentencia que cambia la contraseña
                $sentencia_actualizar_contrasena = "UPDATE usuarios SET password = '$newPasswordHash' WHERE email = '$usuario_correo'";
                $Resultado_usuario_correo=mysqli_query($conn,$sentencia_actualizar_contrasena);

            }else{

                //En caso de que el usuario no exista se envia un mensaje
                ?>
                    <script> alert('Error: No existe este usuario')</script>
                <?php
            }

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
              <input required="true" type="password" id="password" name="newpassword" class="input">
              <span class="bar"></span>
              <label class="label">
                <span class="label-char" style="--index: 0">N</span>
                <span class="label-char" style="--index: 1">u</span>
                <span class="label-char" style="--index: 2">e</span>
                <span class="label-char" style="--index: 3">v</span>
                <span class="label-char" style="--index: 4">a</span>
                <span class="label-char" style="--index: 5">&nbsp</span>
                <span class="label-char" style="--index: 6">c</span>
                <span class="label-char" style="--index: 7">o</span>
                <span class="label-char" style="--index: 8">n</span>
                <span class="label-char" style="--index: 9">t</span>
                <span class="label-char" style="--index: 10">r</span>
                <span class="label-char" style="--index: 11">a</span>
                <span class="label-char" style="--index: 12">s</span>
                <span class="label-char" style="--index: 13">e</span>
                <span class="label-char" style="--index: 14">ñ</span>
                <span class="label-char" style="--index: 15">a</span>
              </label>
            </div>
            <br>

            <div class="mostrarContrasena">
              <input type="checkbox" id="checkboxMostrarContrasena"> Mostrar contraseña <!-- La función de este checkbox se hace en javascript -->
            </div>

            <!-- Script que permite ver el contenido de la contraseña al marcar el ver contraseña -->
            <script>
              const checkbox = document.getElementById('checkboxMostrarContrasena');
              const passwordInput = document.getElementById('password');
          
              checkbox.addEventListener('change', () => {
                  passwordInput.type = checkbox.checked ? 'text' : 'password';
              });
            </script>
            <br><br>
            <input type="submit" name="enviar" class="registro" value="Enviar">
            <br><br>
            <a href="MainPage.php" class="volver"> Volver</a>
        </form>
        </div>
</body>
</html>