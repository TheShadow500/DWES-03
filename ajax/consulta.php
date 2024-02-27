<?php
    // Recibimos el tipo de busqueda a buscar en la BD
    $tipoConsulta = isset($_POST['tipoConsulta']) ? $_POST['tipoConsulta'] : '';

    require "../bd/conn.php";
    $conn->exec("use $db;");

    $cabecera;

    // Una vez conectado a la base de datos realizamos la consulta segun el tipo de artículo
    switch($tipoConsulta){
        case 'inicio':
            $cabecera = "ALGUNOS DE NUESTROS PRODUCTOS";
            $sql = $conn->prepare("SELECT * FROM $table ORDER BY RAND() LIMIT 15;");
            break;
        case 'hardware':
            $cabecera = "TODO HARDWARE";
            $sql = $conn->prepare("SELECT * FROM $table WHERE tipo = 1;");
            break;
        case 'software':
            $cabecera = "TODO SOFTWARE";
            $sql = $conn->prepare("SELECT * FROM $table WHERE tipo = 2;");
            break;
        case 'suscripcion':
            $cabecera = "TODO SUSCRIPCIONES";
            $sql = $conn->prepare("SELECT * FROM $table WHERE tipo = 3;");
            break;
        default:
            echo "Consulta NO Válida";
            break;
    }

    try{
        $sql->execute();
        $contenido = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        die();
    }

    // Preparamos la respuesta para devolverla con AJAX
    $respuestahttp = '';
    $respuestahttp .= "<div class='informacion'>" . $cabecera . "</div>";
    $respuestahttp .= "<div class='productos'>";
    $respuestahttp .= "<div class='fila'>";
    $respuestahttp .= "<div class='columnanombre titulocentrado'>ARTÍCULO</div>";
    $respuestahttp .= "<div class='columnadescripcion titulocentrado'>DESCRIPCIÓN</div>";
    $respuestahttp .= "<div class='columnacategoria titulocentrado'>CATEGORÍA</div>";
    $respuestahttp .= "<div class='columnaprecio titulocentrado'>PRECIO</div>";
    $respuestahttp .= "<div class='columnacesta titulocentrado'>CESTA</div>";
    $respuestahttp .= "</div>";
    
    foreach($contenido as $fila){
        $respuestahttp .= "<div class='fila'>";
        $respuestahttp .= "<div class='columnanombre'><a class='enlacearticulo' id=" . $fila["codigo"] . ">" . $fila["nombre"] . "</a></div>";
        $respuestahttp .= "<div class='columnadescripcion'>" . $fila["descripcion"] . "</div>";
        
        $categorias = [
            1 => "Hardware",
            2 => "Software",
            3 => "Suscripción"
        ];
        $tipo = $fila["tipo"];
        $respuestahttp .= "<div class='columnacategoria'>" . $categorias[$tipo] . "</div>";
        $respuestahttp .= "<div class='columnaprecio'>" . $fila["precio"] . "€</div>";
        $respuestahttp .= "<div class='columnacesta anadircesta enlacecesta' id=" . $fila["codigo"] . "></div>";
        $respuestahttp .= "</div>";
    }
    $respuestahttp .= "<br>";

    // Devolvemos los datos
    echo $respuestahttp;
?>