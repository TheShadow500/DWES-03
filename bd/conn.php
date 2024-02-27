<?php
    $servername = "localhost";      // Nombre del Servidor
    $username = "root";             // Nombre de usuario
    $password = "";                 // Contraseña
    $db = "techavendac";           // Nombre de la base de datos
    $table = "productos";           // Listado de articulos

    // Crear conexión
    try{
        $conn = new PDO("mysql:host=$servername", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
        die();
    }
?>