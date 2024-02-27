<?php
    session_start();
    
    // Recibe el JSON en caso de existir
    $json_data = file_get_contents('php://input');

    // Decodifica el JSON a un array de PHP
    $data = json_decode($json_data, true);

    // Accede al valor utilizando la clave "clave1"
    $_SESSION['articulos'] = $data['articulos'];

    print_r($_SESSION['articulos']);
?>