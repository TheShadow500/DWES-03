<?php
    // Recibimos la ID a buscar en la BD
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    require "../bd/conn.php";
    $conn->exec("use $db;");

    // Una vez conectado a la base de datos realizamos la consulta
    $sql = $conn->prepare("SELECT * FROM $table WHERE codigo = :id");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);

    try{
        $sql->execute();
        $contenido = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        die();
    }

    // Devolvemos los datos con JSON a traves de AJAX
    foreach($contenido as $fila){
        echo json_encode($fila);
    }
?>