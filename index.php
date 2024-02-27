<?php
    // Iniciamos la sesion
    session_start();

    // Verificamos si la sesion ya existia para recuperar los articulos de la cesta
    // en caso contrario lo crea vacio
    $articulos = isset($_SESSION['articulos']) ? $_SESSION['articulos'] : [];

    // Codificamos el array por JSON para pasarselo a JS
    $articulos_json = json_encode($articulos);
?>

<!-- Cargamos los datos de la sesion en JS -->
<script>
    let articulos = <?php echo $articulos_json; ?>;
</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Cargamos la libreria para los JQueries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Cargamos el icono de la web -->
    <link rel="icon" type="image/x-icon" href="./img/icono.ico">
    <!-- Cargamos la hoja de estilos -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- Cargamos el archivo con la configuracion del servidor -->
    <?php require "./bd/conn.php"; ?>

    <title>Techaven - Tu tienda de informática</title>
</head>
<body>
    <div class="container">
        <header class="cabecera">
            <div class="logotipo"><img class="logotipoimg" src="./img/logo.png"></div>
            <nav>
                <div class="opciones">
                    <ul>
                        <li><span class="menuactivo" id="inicio">Inicio</span></li>
                        <li><span id="hardware">Hardware</span></li>
                        <li><span id="software">Software</span></li>
                        <li><span id="suscripcion">Suscripción</span></li>
                    </ul>
                </div>
                <div class="carrito" id="carrito"></div>
                <div class="login" id="login"></div>
            </nav>
        </header>

        <!-- MENU INICIO -->
        <main id="menuinicio"></main>

        <!-- MENU HARDWARE -->
        <main style="display: none" id="menuhardware"></main>

        <!-- MENU SOFTWARE -->
        <main style="display: none" id="menusoftware"></main>

        <!-- MENU SUSCRIPCIÓN -->
        <main style="display: none" id="menususcripcion"></main>

        <!-- DETALLE ARTÍCULO -->
        <main style="display: none" id="menudetallearticulo"></main>

        <!-- MENU CARRITO -->
        <main style="display: none" id="menucarrito">
            <div class="informacion">CESTA</div>
            <div class="productoscesta" id="productoscesta"></div>
            <div class="productoscesta" id="precioscesta"></div>
            <div class="productoscesta">
                <div class="cestafilabotones">
                    <div class="cestaceldabotones">
                        <button class="botoneliminar" id="vaciarcesta">VACIAR</button>
                    </div>
                    <div class="cestaceldabotones">
                        <button class="botoneliminar" id="pagarcesta">PAGAR</button>
                    </div>
                </div>
            </div>
        </main>

        <!-- MENU LOGIN -->
        <main style="display: none" id="menulogin">
            <div class="informacion">LOGIN</div>
            <div class="productos">
                <div class="fila">
                    <div class="logininfo">Función Login NO disponible en esta versión de simulación</div>
                </div>
            </div>
        </main>

        <!-- CESTA PAGADA -->
        <main style="display: none" id="menupagado">
            <div class="informacion">PEDIDO REALIZADO</div>
                <div class="productos">
                    <div class="fila">
                        <div class="logininfo">PEDIDO REALIZADO<BR>PREPÁRATE PARA RECIBIRLO EN LAS PRÓXIMAS 24-48 horas</div>
                    </div>
                </div>
            </main>

        <!-- PIE DE PAGINA -->
        <footer>
            <div class="informacionfooter">
                <div class="piei">
                    Dirección<br>
                    c/Primavera, 10 (IES Zaidín-Vergeles)<br>
                    18007 Granada (GRANADA)
                </div>
                <div class="pied">
                    Contacto<br>
                    Tlf: 612 678 626<br>
                    Email: admin@techaven.es
                </div>
            </div>
            <div>©2024 DWES - Tarea 3 y 4. Daniel Amores Corzo</div>
        </footer>
    </div>
</body>
</html>

<!-- Cargamos el archivo JS -->
<script src="./js/main.js"></script>