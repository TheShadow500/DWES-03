<?php
    require "./bd/conn.php";

    $sql = $conn->prepare("DROP DATABASE IF EXISTS $db;");
    $sql->execute();
    echo "Eliminando base de datos anterior " . $db . " en caso de existir.<br>";

    // Crear base de datos
    $sql = $conn->prepare("CREATE DATABASE $db");
    $sql->execute();
    echo 'Base de datos ' . $db . ' creada correctamente.<br>';

    // Accedemos a la BD recien creada
    $conn->exec("USE $db;");
    echo 'Acceso correcto a ' . $db . '<br>';

    // Creamos la tabla
    $sql = $conn->prepare("CREATE TABLE $table (
        codigo INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        tipo INT(1) NOT NULL,
        precio FLOAT(7) NOT NULL,
        nombre VARCHAR(50) NOT NULL,
        descripcion VARCHAR(500) NOT NULL,
        peso INT(5),
        largo INT(3),
        ancho INT(3),
        alto INT(3),
        sistemaoperativo VARCHAR(30),
        duracion INT(2),
        periodicidad BOOLEAN
    );");
    $sql->execute();
    echo 'Tabla creada con éxito.<br>';

    // Comprobamos si existe el archivo .csv
    $archivocsv = "./csv/datos.csv";
    if(!file_exists($archivocsv)){
        echo "No existe el archivo CSV para cargar la información";
    }
    else{
        // En caso de existir se abre en modo lectura
        $archivo = fopen($archivocsv, 'r');
        echo "Archivo CSV cargado para volcar información";

        //Se extraen linea a linea del csv
        while(($fila = fgetcsv($archivo)) !== FALSE){
            // Creamos la sentencia SQL con valores ? que se aclara en el siguiente punto
            $sql = $conn->prepare("INSERT INTO $table (tipo, precio, nombre, descripcion, peso, largo, ancho, alto, sistemaoperativo, duracion, periodicidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");

            // Pasamos a parametrizar los valores espceficando el tipo de dato a insertar STR, INT, etc
            $sql->bindParam(1, $fila[0], PDO::PARAM_STR);
            $sql->bindParam(2, $fila[1], PDO::PARAM_STR);
            $sql->bindParam(3, $fila[2], PDO::PARAM_STR);
            $sql->bindParam(4, $fila[3], PDO::PARAM_STR);
            $sql->bindParam(5, $fila[4], PDO::PARAM_INT);
            $sql->bindParam(6, $fila[5], PDO::PARAM_INT);
            $sql->bindParam(7, $fila[6], PDO::PARAM_INT);
            $sql->bindParam(8, $fila[7], PDO::PARAM_INT);
            $sql->bindParam(9, $fila[8], PDO::PARAM_STR);
            $sql->bindParam(10, $fila[9], PDO::PARAM_STR);
            $sql->bindParam(11, $fila[10], PDO::PARAM_STR);
            $sql->execute();

            // Una vez parametrizado se informa al usuario
            echo '<br>Añadido con éxito el producto ' . $fila[2];
        }
    }

    $conn = null;
?>