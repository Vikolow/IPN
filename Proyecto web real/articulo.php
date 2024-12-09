<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peligros WWWeb</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.png" />
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    
    <header class="headermain">
        <a href="MainPage.php" class="btn-back">Volver</a>
        <div class="logo">
            <a href="MainPage.php" class="logo">
                <img class="logoimg" src="img/Logo_IPN.png">
            </a>
        </div>
        <a href="perfil.php" class="btn-login">
            <img src="img/Perfil.png" alt="Login">
        </a>
    </header>
    <main class="mainCategorias">

    <?php

        // Iniciamos la sesión
        session_start();
        
        // Si no hay ninguna sesión activa redirije también a la pagina de error
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == "") {
            
            header("Location: error.html");
            exit;
        }


        // Esta función recoge el valor de la URL para ejecutar la consulta correctamente
        if (isset($_REQUEST['id_art'])) {
            $_SESSION['id_art'] = $_REQUEST['id_art'];
        }

        // Incluimos la página de conexión con la base de datos.
        include("GestionBD/conexion.php");

        // Consulta encapsulada para obtener los datos del artículo.
        $Consulta_Selecta = "SELECT * FROM articulos WHERE id_articulo = ?";
        $stmt_selecto = mysqli_prepare($conn, $Consulta_Selecta);
        mysqli_stmt_bind_param($stmt_selecto, "i", $_SESSION['id_art']);
        mysqli_stmt_execute($stmt_selecto);
        $Resultado_Selecto = mysqli_stmt_get_result($stmt_selecto);
        $Array_Selecto = mysqli_fetch_assoc($Resultado_Selecto);

        // Consulta encapsulada para comprobar si el artículo está en favoritos del usuario.
        $Consulta_fav = "SELECT * FROM favoritos WHERE id_usuario = ? AND id_articulo = ?";
        $stmt_fav = mysqli_prepare($conn, $Consulta_fav);
        mysqli_stmt_bind_param($stmt_fav, "ii", $_SESSION['id_usuario'], $_SESSION['id_art']);
        mysqli_stmt_execute($stmt_fav);
        $Resultado_fav = mysqli_stmt_get_result($stmt_fav);

        echo "<h1 class='tituloArticulos'>{$Array_Selecto['titulo']}</h1>";

        // Pinta el botón de favoritos dependiendo de si el usuario lo tiene marcado o no.
        if (mysqli_num_rows($Resultado_fav) > 0) {
            // Botón desActivarFavorito
            echo "<form method='POST' action=''>";
            echo    "<button type='submit' name='desActivarFavorito' class='desActivarFavorito'> ⭐ </button>";
            echo "</form>";
        } else {
            // Botón activarFavorito
            echo "<form method='POST' action=''>";
            echo    "<button type='submit' name='activarFavorito' class='desActivarFavorito'> ☆ </button>";
            echo "</form>";
        }

        echo "<h4 class='categoria'>Categoría: {$Array_Selecto['id_categoria']}</h4>";

        if (!file_exists($Array_Selecto['foto'])) {
            echo "<img class='imgArticuloPrincipalImpreso' src='img/articulo.jpg' alt=''/>";
        } else {
            echo "<img class='imgArticuloPrincipalImpreso' src='{$Array_Selecto['foto']}' alt=''/>";
        }

        echo "<div class='CajaContenido'>";
        echo "<a class='contenidoArticulo'>{$Array_Selecto['contenido']}</a>";
        echo "</div>";

        // Función que asigna el favorito.
        if (isset($_REQUEST['activarFavorito'])) {
            $anadir_favorito = "INSERT INTO favoritos (id_usuario, id_articulo) VALUES (?, ?)";
            $stmt_añadir = mysqli_prepare($conn, $anadir_favorito);
            mysqli_stmt_bind_param($stmt_añadir, "ii", $_SESSION['id_usuario'], $_SESSION['id_art']);
            mysqli_stmt_execute($stmt_añadir);
            header("Location: articulo.php");
        }

        // Función que elimina el favorito.
        if (isset($_REQUEST['desActivarFavorito'])) {
            $eliminar_favorito = "DELETE FROM favoritos WHERE id_usuario = ? AND id_articulo = ?";
            $stmt_eliminar = mysqli_prepare($conn, $eliminar_favorito);
            mysqli_stmt_bind_param($stmt_eliminar, "ii", $_SESSION['id_usuario'], $_SESSION['id_art']);
            mysqli_stmt_execute($stmt_eliminar);
            header("Location: articulo.php");
        }
    ?>

    </main>
    <footer>
        <p class="copyright">© 2024 Informática para novatos. Todos los derechos reservados.</p>
    </footer>
</body>
