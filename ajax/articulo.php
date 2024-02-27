<?php
    // Recibimos el codigo a buscar en la BD
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';

    $cabecera;

    require "../bd/conn.php";
    $conn->exec("use $db;");

    // Una vez conectado a la base de datos realizamos la consulta
    $sql = $conn->prepare("SELECT * FROM $table WHERE codigo = :codigo;");
    $sql->bindParam(':codigo', $codigo, PDO::PARAM_INT);

    try{
        $sql->execute();
        $contenido = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        die();
    }

    // Preparamos la respuesta para devolverla con AJAX
    foreach($contenido as $fila){
        $cabecera = $fila['nombre'];
        $respuestahttp = '';
        $respuestahttp = "<br>";
        $respuestahttp .= "<div class='informacionarticulo'>" . $cabecera . "</div>";
        $respuestahttp .= "<div class='productosarticulos'>";
        $respuestahttp .= "<div class='filaarticuloinicial'><br>Descripción<br>" . $fila['descripcion'] . "</div>";
        switch($fila['tipo']){
            case 1:
                $respuestahttp .= "<div class='filaarticulo'>Peso<br><span class='caracteristicasarticulo'>" . $fila['peso'] . "g</span></div>";
                $respuestahttp .= "<div class='filaarticulo'>Largo<br><span class='caracteristicasarticulo'>" . $fila['largo'] . " cm</span></div>";
                $respuestahttp .= "<div class='filaarticulo'>Ancho<br><span class='caracteristicasarticulo'>" . $fila['ancho'] . " cm</span></div>";
                $respuestahttp .= "<div class='filaarticulo'>Alto<br><span class='caracteristicasarticulo'>" . $fila['alto'] . " cm</span></div>";
                break;
            case 2:
                $respuestahttp .= "<div class='filaarticulo'>Peso<br><span class='caracteristicasarticulo'>" . $fila['peso'] . "g.</div>";
                $respuestahttp .= "<div class='filaarticulo'>Sistema Operativo<br><span class='caracteristicasarticulo'>" . $fila['sistemaoperativo'] . "</div>";
                break;
            case 3:
                if($fila['duracion'] > 1){
                    $respuestahttp .= "<div class='filaarticulo'>Duración<br>" . $fila['duracion'] . " meses</div>";
                }
                else{
                    $respuestahttp .= "<div class='filaarticulo'>Duración<br>" . $fila['duracion'] . " mes</div>";
                }
                
                if($fila['periodicidad'] == 1){
                    $respuestahttp .= "<div class='filaarticulo'>Periodicidad<br>SI</div>";
                }
                else{
                    $respuestahttp .= "<div class='filaarticulo'>Periodicidad<br>NO</div>";
                }
                break;
            default:
                break;
        }
        $respuestahttp .= "<div class='filaarticulo'><br><span class=' precioarticulo'>" . $fila['precio'] . "€</span></div>";
        $respuestahttp .= "<div class='filaarticulofinal'><button class='botoncomprar' id='boton" . $fila['codigo'] . "'>AÑADIR</button>";
        $respuestahttp .= "</div><br>";
    }

    // Devolvemos los datos
    echo $respuestahttp;
?>