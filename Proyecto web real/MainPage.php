<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPN</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.png" />
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<?php

    //Iniciamos la sesion del usuario y le asignamos un valor 0 para establecer que no ha iniciado sesión
    session_start();

    // Comprobar si la variable de sesión ya está definida
    if (!isset($_SESSION['sesionActiva'])) {

        // Si no está definida, asignarle el valor predeterminado de 1
        $_SESSION['sesionActiva'] = 0;
    }

    //En caso de que se pulse el boton de enviar se ejecutará este codigo
    if (isset($_REQUEST['Registrar'])){
        //Incluimos la función de conexión con la base de datos
        include("GestionBD/conexion.php");

        //Recojemos todos los datos de los campos
        $Registro_correo=$_REQUEST['correo'];
        $Registro_contraseña=$_REQUEST['contra'];

        //Creamos la consulta , en esta iteracion encapsulada para prevenir inyecciones SQL
        $consulta_login = "SELECT * FROM usuarios WHERE email = ?";
        $stmt=mysqli_prepare($conn,$consulta_login);
        mysqli_stmt_bind_param($stmt,"s",$Registro_correo);
        mysqli_stmt_execute($stmt);
        $Resultado_consulta_correo =mysqli_stmt_get_result($stmt);

        //comprobamos que existe una cuenta asociada a el correo introducido
        if(mysqli_num_rows($Resultado_consulta_correo) > 0) {    
            $row=mysqli_fetch_assoc($Resultado_consulta_correo);
            
            //Verificar el hash de contraseña mediante password_verify
            if(password_verify($Registro_contraseña,$row['password'])){
                //Si el usuario y contraseña son correctos, se creara la sesión y se mostrará el catálogo de productos.
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['clase'] = $row['id_rol'];
                //Asignamos que la sesión está activa
                $_SESSION['sesionActiva'] = 1;
                //Refresca la pagina
                header("Location: MainPage.php");
            }else{
                //Si la contraseña introducida no es la correcta, se informará de esto al usuario.
                ?>
                    <script>
                        alert('Error: Las credenciales no son correctas');
                    </script>
                <?php
            }
        } else{
            //caso de que no exista el usuario, se mostrará un mensaje indicado que el usuario no existe.
            ?>
            <script>
                alert('Error: Las credenciales no son correctas');
            </script>
            <?php

        }
    }

    //Función que elimina la sesión activa de la pagina 
    if (isset($_REQUEST['desinvocador'])){
        session_destroy();
        header("Location: Mainpage.php");
        $_SESSION['sesionActiva'] = 0;
    }

    //Incluimos la función de conexión con la base de datos
    include("GestionBD/conexion.php");

    //Función que elimina un articulo
    if (isset($_REQUEST['id_elimart'])){
        //Recojemos el dato que envia el boton para conocer el id del articulo a eliminar
        $id_art = $_REQUEST['id_elimart'];
        //Creamos la sentencia SQL con encapsulacion
        $elimArticulo= "DELETE FROM articulos WHERE id_articulo = ? ;";
        $stmt = mysqli_prepare($conn, $elimArticulo);
        mysqli_stmt_bind_param($stmt, "i", $id_art);

        // Condicional mejorado para comprobar tambien si la consulta fue exitosa en la db
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('El artículo ha sido eliminado');</script>";
        } else {
            echo "<script>alert('Error: No se pudo eliminar el artículo o no existe');</script>";
        }        
    }

?>

    <div class="cajaInvocador">

        <?php
                // Mostrar información de la sesión cuando hay sesion
                echo "ID_Usuario: " . $_SESSION['id_usuario'] . "<br>";
                echo "tipo de usuario: " . $_SESSION['clase'] . "<br>";
        ?>

    </div>

    <!-- Cabecera -->
    <header class="headermain">

        <!-- Logout -->
        <form action="">
            <input type="submit" class="btn-back" name="desinvocador" value="Logout">
        </form>

        <!-- Logo IPN -->
        <div class="logo">
            <a href="MainPage.php" class="logo">
                <img class="logoimg" src="img/Logo_IPN.png">
            </a>
        </div>

        <div class="cajaColores">

        <!-- Icono -->
        <?php

        //Dependiendo de si la sesión está activa o la funcio e imagen del botón cambia
        if($_SESSION['sesionActiva'] == 0){
            echo"<a class='invocador btn-login2'>";
            echo"<img src='img/Perfil.png'>";
            echo"</a>";
        }else {
            
            //Dependiendo de los privilegios del usuario pinta el logo de un color 1=Admin 2=Usuario 3=Autor
            if($_SESSION['clase'] == 2){
                echo"<a class='btn-login' href='perfil.php'>";
                echo"<img src='img/PerfilRojo.png'>";
                echo"</a>";
            }else if($_SESSION['clase'] == 1){
                echo"<a class='btn-login' href='perfil.php'>";
                echo"<img src='img/Perfil.png'>";
                echo"</a>";
            }else if($_SESSION['clase'] == 3){
                echo"<a class='btn-login' href='perfil.php'>";
                echo"<img src='img/PerfilMorado.png'>";
                echo"</a>";
            }
        }
        
        ?>

        </div>

    </header>

    <main>
        
        <!-- Header -->
        <div class="contenedorMasServicios">
            <div class="masServicios">
                <a href="herramientas.html" class="masServicios">Herramientas</a>
            </div>
        </div>
            
        <div class="linea"></div>

        <h1 class="tituloPagina">Informática para novatos</h1>

        <!-- Caja de articulo principal -->

        <?php
        $id_articulo = 0; // ID del artículo principal

        //Consulta encapsulada para obtener el articulo principal
        $Consulta_ArticuloPrincipal = "SELECT * FROM articulos WHERE id_articulo = ?";
        $stmt_articulo_principal = mysqli_prepare($conn, $Consulta_ArticuloPrincipal);
        mysqli_stmt_bind_param($stmt_articulo_principal, "i", $id_articulo);
        mysqli_stmt_execute($stmt_articulo_principal);
        $Resultado_ArticuloPrincipal = mysqli_stmt_get_result($stmt_articulo_principal);
        $Array_ArticuloPrincipal = mysqli_fetch_assoc($Resultado_ArticuloPrincipal);

        echo"<article class='articuloPrincipal'>";
            echo"<div class='contenidoPrincipal'>";
                echo"<img class='imgArticuloPrincipal' src='img/articulo.jpg' alt=''/>";
                echo"<div class='textoArticuloPrincipal'>";
                    echo"<h1 class='tituloArticuloPrincipal'> {$Array_ArticuloPrincipal['titulo']} </h1>";
                    echo"<h5 class='descripcion'> {$Array_ArticuloPrincipal['descripcion']} </h5>";
                    if($_SESSION['sesionActiva'] == 0){
                        echo "<button name='id_art' class='registro invocador' value=''>Leer más</button>";
                    }else{
                        echo "<form method='POST' action='articulo.php'>";
                        echo "<button type='submit' name='id_art' class='registro' value='{$Array_ArticuloPrincipal['id_articulo']}'>Leer más</button>";
                        echo "</form> ";
                    }
                echo"</div>";
            echo"</div>";
        echo"</article>";

        ?>


        <!-- CATALOGO DINAMICO PADRE-->
        <div class="agonia">
    
            <!-- Botones de Categoria -->
            <div class="categories">
            
                <?php
            
                //Request a la base de datos para sacar los datos de las categorias
                $Sentencia_categorias = "SELECT * FROM categorias";
                $Resultado_Sentencia_categorias = mysqli_query($conn, $Sentencia_categorias);

                //Función para limpiar la categoria activa 
                if (isset($_POST['CategoriaL'])) {

                    // Limpiar categoría
                    $_SESSION['categoriaActiva'] = null;  

                }elseif (isset($_POST['categoria'])) {

                    // Actualizar categoría activa desde el POST
                    $_SESSION['categoriaActiva'] = $_POST['categoria'];

                }else {

                    // Actualizar categoría activa desde el POST
                    $_SESSION['categoriaActiva'] = null;
                }

                // Obtener la categoría activa, si está configurada en la sesión
                $categoriaActiva = isset($_SESSION['categoriaActiva']) ? $_SESSION['categoriaActiva'] : null;
            
                // Mostrar botón de "Limpiar Categorías"
                echo "<form action='' method='post' class='form_categorias'>";
                if ($categoriaActiva === null) {
                    echo "<input type='submit' class='Botones_categorias2' name='CategoriaL' value='Limpiar Categorias'>";
                } else {
                    echo "<input type='submit' class='Botones_categorias' name='CategoriaL' value='Limpiar Categorias'>";
                }
                echo "</form>";

                if (mysqli_num_rows($Resultado_Sentencia_categorias) > 0) {

                    //Crea los botones de categorias de forma dinamica
                    while ($Array_Sentencia_categorias = mysqli_fetch_assoc($Resultado_Sentencia_categorias)) {
                        $categoriaID = $Array_Sentencia_categorias['id_categoria'];
                        $categoriaNombre = $Array_Sentencia_categorias['nombre_categoria'];
                        
                        // Mostrar los botones de categorías
                        echo "<form action='' method='post' class='form_categorias'>";
                        if ($categoriaActiva == $categoriaNombre) {
                            echo "<input type='submit' class='Botones_categorias2' name='categoria' value='$categoriaNombre'>";
                        } else {
                            echo "<input type='submit' class='Botones_categorias' name='categoria' value='$categoriaNombre'>";
                        }
                        echo "</form>";
                    }
                }
            
            ?>
            
            </div>

            <div class="algo">

                
            <article class="articulosSecundarios">

                <?php

                //Incluimos la pagina de conexión con la base de datos.
                include("GestionBD/conexion.php");
                
                //Creamos la sentencia encapsulada
                if($_SESSION['categoriaActiva'] === null){

                    $Consulta_Articulos="SELECT * FROM articulos ;";

                }else{

                    //Creamos una consulta que saca el id de la categoria a partir de su nombre para sacar los articulos que la tengan
                    $consulta_id_nombre = " SELECT * FROM categorias WHERE nombre_categoria = '$categoriaActiva' ;";
                    $Resultado_consulta_id_nombre = mysqli_query($conn, $consulta_id_nombre);
                    $Array_consulta_id_nombre = mysqli_fetch_assoc($Resultado_consulta_id_nombre);

                    $Consulta_Articulos="SELECT * FROM articulos WHERE id_categoria = {$Array_consulta_id_nombre['id_categoria']} ;";
                    
                }

                $stmt_articulos=mysqli_prepare($conn, $Consulta_Articulos);
                mysqli_stmt_execute($stmt_articulos);
                $Resultado_Articulos= mysqli_stmt_get_result($stmt_articulos);

                //Condicional que realiza la función mientras la consulta debuelva un resultado.
                if(mysqli_num_rows($Resultado_Articulos)>0){

                    while($Array_Articulos=mysqli_fetch_assoc($Resultado_Articulos)){

                        echo "<div class='contenedorImagenTexto'>";

                        if(!file_exists($Array_Articulos['foto'])){
                            echo "<img class='imgArticulo' src='img/articulo.jpg' alt=''/>";
                        }else{
                            echo "<img class='imgArticulo' src='{$Array_Articulos['foto']}' alt=''/>";
                        }
                        echo "<div class='contenedorTexto'>";
                        echo "<h1> {$Array_Articulos['titulo']} </h1>";
                        echo "<a> {$Array_Articulos['descripcion']} </a>";
                        echo "</div>";
                        echo "<div class='abrazoBotones'>";

                        if($_SESSION['sesionActiva'] == 0){
                            echo "<button name='id_art' class='registro invocador' value=''>Leer más</button>";
                        }else{
                            echo "<form method='POST' action='articulo.php'>";
                            echo "<button type='submit' name='id_art' class='registro' value='{$Array_Articulos['id_articulo']}'>Leer más</button>";
                            echo "</form> ";
                        }

                        if(($_SESSION['sesionActiva'] == 1)){

                            if($_SESSION['clase'] == 2){
                                echo "<form method='POST' action='MainPage.php'>";
                                echo "<button type='submit' name='id_elimart' class='registro2' value='{$Array_Articulos['id_articulo']}'>Eliminar</button>";
                                echo "</form> ";
                            }
                        }

                        echo "</div>";
                        echo "</div>";
                    }
                }

                ?>

            </article>
            </div>
            
        </div>

        <!-- Para cuando se invoque el login esta función permite que se vea la contraseña al marcar la casilla -->
        <script>
              const checkbox = document.getElementById('checkboxMostrarContrasena');
              const passwordInput = document.getElementById('password');
          
              checkbox.addEventListener('change', () => {
                  passwordInput.type = checkbox.checked ? 'text' : 'password';
              });
        </script>

    </main>

    <div class="anuncios"></div>
    <footer>
        <p class="copyright">© 2024 Informática para novatos. Todos los derechos reservados.</p>
    </footer>

    <!-- Link al documento js -->
    <script src="./Js/index.js"></script>

</body>
</html>