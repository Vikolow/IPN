<?php

$servername = "localhost";
$username = "root";
$password = "";

// Creamos la conexion y seleccionamos la base de datos
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Conexion fallida: " . mysqli_connect_error());
}

// Crear la base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS IPN";
if (mysqli_query($conn, $sql)) {
    echo "Base de datos creada con exito \n";
} else {
    die("Error creando la base de datos: " . mysqli_error($conn));
}

//Llama la función que establece la conexión con la base de datos.
include ("conexion.php");
   //Creamos la sentencia para crear la tabla de roles
   $sql_roles = "CREATE TABLE IF NOT EXISTS Roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    nombre_rol VARCHAR(50) NOT NULL
    );";
    $resultado_roles=mysqli_query($conn,$sql_roles);
    if ($resultado_roles)
    {
        echo "Tabla Roles creada con exito \n";
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }

    //Creamos la sentencia para crear la tabla de usuarios
    $sql_usuarios = "CREATE TABLE IF NOT EXISTS Usuarios (
        id_usuario INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(50) NOT NULL,
        apellidos VARCHAR(50),
        email VARCHAR(80) NOT NULL,
        password VARCHAR(255) NOT NULL,
        id_rol INT DEFAULT 1,
        peticion_activa INT DEFAULT 0,
        numero_peticiones_restante INT DEFAULT 3,
        fecha_nacimiento DATE,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        biografia TEXT,
        foto varchar(255), 
        pregunta_seguridad ENUM('¿Cuál es tu apodo?', 
                            '¿Cuál es tu comida favorita?', 
                            '¿En qué ciudad naciste?') NOT NULL,
        respuesta_seguridad VARCHAR(255) NOT NULL,
        FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
        );";

        $Resultado_usuarios= mysqli_query($conn, $sql_usuarios);
    
        if ($Resultado_usuarios)
        {
            //echo "$sql_Articulos <br>";
            echo "Tabla Usuarios creada con exito \n";
        } else 
        {
            echo "Error creando la tabla: " . mysqli_error($conn);
        }


    //Creamos la sentencia para crear la tabla de categorias
    $sql_categorias="CREATE TABLE IF NOT EXISTS Categorias (
        id_categoria INT PRIMARY KEY AUTO_INCREMENT,
        nombre_categoria VARCHAR(100) NOT NULL
    );";
    $resultado_categorias=mysqli_query($conn,$sql_categorias);
    if ($resultado_categorias)
    {
        echo "Tabla Categorias creada con exito \n";
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }

    //Creamos la sentencia para crear la tabla de Articulos
    $sql_Articulos = "CREATE TABLE IF NOT EXISTS Articulos (
        id_articulo INT AUTO_INCREMENT PRIMARY KEY, 
        foto BLOB,
        titulo VARCHAR(50) NOT NULL,
        descripcion VARCHAR(200),
        contenido TEXT NOT NULL,
        fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        id_usuario int,
        id_categoria int,
        FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria) ON DELETE cascade,
        FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE cascade
        );";

        $Resultado_articulos= mysqli_query($conn, $sql_Articulos);
    
        if ($Resultado_articulos)
        {
            //echo "$sql_Articulos <br>";
            echo "Tabla Articulos creada con exito \n";
        } else 
        {
            echo "Error creando la tabla: " . mysqli_error($conn);
        }
    
 

    //Creamos la sentencia para crear la tabla de etiquetas
    $sql_etiquetas="CREATE TABLE IF NOT EXISTS Etiquetas (
        id_etiqueta INT PRIMARY KEY AUTO_INCREMENT,
        nombre_etiqueta VARCHAR(100) 
    );";
    $resultado_etiquetas=mysqli_query($conn,$sql_etiquetas);
    if ($resultado_etiquetas)
    {
        echo "Tabla Etiquetas creada con exito \n";
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }


    //Creamos la sentencia para crear la tabla de articulos-etiquetas
    $sql_articulos_etiquetas="CREATE TABLE IF NOT EXISTS Articulo_Etiqueta (
        id_articulo INT,
        id_etiqueta INT,
        PRIMARY KEY (id_articulo, id_etiqueta),
        FOREIGN KEY (id_articulo) REFERENCES Articulos(id_articulo) ON DELETE cascade,
        FOREIGN KEY (id_etiqueta) REFERENCES Etiquetas(id_etiqueta) ON DELETE cascade
    );";
    $resultado_articulos_etiquetas=mysqli_query($conn,$sql_articulos_etiquetas);
    if ($resultado_articulos_etiquetas)
    {
        echo "Tabla Articulos-Etiquetas creada con exito \n"; 
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }


    //Creamos la sentencia para crear la tabla de Comentarios
    $sql_comentarios="CREATE TABLE IF NOT EXISTS Comentarios (
        id_comentario INT PRIMARY KEY AUTO_INCREMENT,
        id_articulo INT,
        id_usuario INT,
        comentario TEXT NOT NULL,
        fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_articulo) REFERENCES Articulos(id_articulo) ON DELETE cascade,
        FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE cascade
    );";
    $resultado_comentarios=mysqli_query($conn,$sql_comentarios);
    if ($resultado_comentarios)
    {
        echo "Tabla Comentarios creada con exito \n"; 
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }

    //Creamos la sentencia para crear la tabla de Favoritos
    $sql_favoritos="CREATE TABLE IF NOT EXISTS Favoritos (
        id_usuario INT,
        id_articulo INT,
        PRIMARY KEY (id_usuario, id_articulo),
        FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE cascade,
        FOREIGN KEY (id_articulo) REFERENCES Articulos(id_articulo) ON DELETE cascade
    );";
    $resultado_favoritos=mysqli_query($conn,$sql_favoritos);
    if ($resultado_favoritos)
    {
        echo "Tabla Favoritos creada con exito \n"; 
    } else {
        echo "Error creando la tabla: " . mysqli_error($conn);
    }

        mysqli_close($conn);
?>
