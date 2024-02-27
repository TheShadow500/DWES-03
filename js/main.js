// Esperemos a que se cargue la web para actualizar los datos a mostrar de la BD
document.addEventListener("DOMContentLoaded", function(){
    actualizarConsulta("inicio");

    let botonvaciarcesta = document.getElementById("vaciarcesta");
    botonvaciarcesta.addEventListener("click", function(){
        articulos = [];
        cestaSesion();
        pasarelaCarrito();
    });

    let botonpagar = document.getElementById("pagarcesta");
    botonpagar.addEventListener("click", function(){
        if(articulos.length === 0){
            alert("Debes agregar artículos a la cesta");
        }
        else{
            articulos = [];
            cestaSesion();
            document.getElementById("menucarrito").style.display = "none";
            document.getElementById("menupagado").style.display = "flex";
        }
    });
})

// Variables necesarias para la web
let menuNavegacion = [
    "inicio",
    "hardware",
    "software",
    "suscripcion",
    "carrito",
    "login"
];

let datosCesta = [];

// Creamos los eventos de los botones del menu de navegacion
for(let i = 0; i < menuNavegacion.length; i++){
    let opcionesnav = document.getElementById(menuNavegacion[i]);
    opcionesnav.addEventListener("click", mostrarmenu);
}

// Función para mostrar las activaciones de los botones
function mostrarmenu(e){
    activarMenu(e.target.id);
    activarCategoria(e.target.id);
}

// Función para iluminar los botones del navegador en uso
function activarMenu(opcion){
    for(let i = 0; i < menuNavegacion.length; i++){
        if(menuNavegacion[i] === opcion){
            if(opcion === "carrito"){
                document.getElementById(menuNavegacion[i]).classList.remove("carrito");
                document.getElementById(menuNavegacion[i]).classList.add("menuactivocarrito");
                pasarelaCarrito();
            }
            else if(opcion === "login"){
                document.getElementById(menuNavegacion[i]).classList.remove("login");
                document.getElementById(menuNavegacion[i]).classList.add("menuactivologin");
            }
            else{
                document.getElementById(menuNavegacion[i]).classList.add("menuactivo");
                actualizarConsulta(opcion);
            }
        }
        else{
            if(menuNavegacion[i] === "carrito"){
                document.getElementById(menuNavegacion[i]).classList.remove("menuactivocarrito");
                document.getElementById(menuNavegacion[i]).classList.add("carrito");
            }
            else if(menuNavegacion[i] === "login"){
                document.getElementById(menuNavegacion[i]).classList.remove("menuactivologin");
                document.getElementById(menuNavegacion[i]).classList.add("login");
            }
            else{
                document.getElementById(menuNavegacion[i]).classList.remove("menuactivo");
            }

        }
    }
}

// Funcion para mostrar el módulo de la categoría
function activarCategoria(opcion){
    if(document.getElementById("menudetallearticulo").style.display === "flex"){
        document.getElementById("menudetallearticulo").style.display = "none";
    }

    if(document.getElementById("menupagado").style.display === "flex"){
        document.getElementById("menupagado").style.display = "none";
    }

    for(let i = 0; i < menuNavegacion.length; i++){
        if(menuNavegacion[i] === opcion){
            document.getElementById("menu" + menuNavegacion[i]).style.display = "flex";
        }
        else{
            document.getElementById("menu" + menuNavegacion[i]).style.display = "none";
        }
    }
}

// Función AJAX para solicitar la información de un artículo pasándole la ID a la BD
function consultarArticulo(id){
    $.ajax({
        url: "./ajax/articulo.php",
        method: "POST",
        data: {codigo: id},
        success: function(datos){
            let contenedor = $("#menudetallearticulo");
            contenedor.html(datos);
            generarBotonCompra(id);
        },
        error: function(error){
            console.error("Error en la solicitud AJAX: " + error);
        }
    });
}

// Función AJAX para solicitar el listado de artículos pasándole la categoría
function actualizarConsulta(opcion){
    $.ajax({
        url: "./ajax/consulta.php",
        method: "POST",
        data: {tipoConsulta: opcion},
        success: function(datos){
            let contenedor = $("#menu" + opcion);
            contenedor.html(datos);
            generarEnlaces(opcion);
        },
        error: function(error){
            console.error("Error en la solicitud AJAX: " + error);
        }
    });
}

// Función AJAX con PROMESAS que solicita al servidor la información de los artículos
// insertados en la cesta pasándole la ID a la BD
function cargarCesta(id){
    return new Promise(function(resolve, reject){
        $.ajax({
            url: "./ajax/carrito.php",
            method: "POST",
            data: {id: id},
            success: function(datos){
                datosCesta.push(JSON.parse(datos));
                resolve();
            },
            error: function(error){
                
                reject("Error en la solicitud AJAX: " + error);
            }
        });
    })
}

// Función AJAX con JSON para insertar los datos de la cesta en la Sesion
function cestaSesion(){
    $.ajax({
        url: "./ajax/procesar.php",
        method: "POST",
        contentType: 'application/json',
        data: JSON.stringify({
            articulos:articulos
        }),
        success: function(response){},
        error: function(error){
            console.error("Error en la solicitud AJAX: ", error);
        }
    });
}

// Funcion que genera los enlaces de cada articulo de manera dinámica, eliminando
// el anterior en caso de que ya existiera
function generarEnlaces(opcion){
    // Creamos los enlaces para los articulos
    let descripcionArticulos = document.getElementById("menu" + opcion);

    descripcionArticulos.removeEventListener("click", crearEnlaces);
    descripcionArticulos.addEventListener("click", crearEnlaces);

    // Creamos los enlaces para la cesta
    let botonesCesta = document.getElementById("menu" + opcion);

    botonesCesta.removeEventListener("click", crearEnlacesCesta);
    botonesCesta.addEventListener("click", crearEnlacesCesta);
}

// Función que muestra el módulo de detalle de artículo
function crearEnlaces(event){
    if(event.target.classList.contains("enlacearticulo")){
        let idEnlace = event.target.id;
        let menus = ['menuinicio', 'menuhardware', 'menusoftware', 'menususcripcion'];
        menus.forEach(menu => {
            document.getElementById(menu).style.display = "none";    
        });
        consultarArticulo(idEnlace);
        document.getElementById("menudetallearticulo").style.display = "flex";
    }
}

// Función que crea los botones de los artículos de añadir a la cesta de la compra
function crearEnlacesCesta(event){
    if(event.target.classList.contains("enlacecesta")){
        let idEnlace = event.target.id;
        let comprado = false;
        articulos.forEach(function(articulo){
            if(idEnlace == articulo){
                comprado = true;
            }
        });

        if(comprado){
            alert("El artículo YA está en la cesta");
        }
        else{
            articulos.push(idEnlace);
            cestaSesion();
            alert("Artículo añadido a la cesta");
        }
    }
}

// Función que genera el evento del boton de añadir a la cesta en el Detalle de Artículo
function generarBotonCompra(id){
    let botoncomprar = document.getElementById("boton" + id);
    botoncomprar.removeEventListener("click", crearBotonCompra);
    botoncomprar.addEventListener("click", crearBotonCompra);
}

// Función que crea el botón de añadir a la cesta en el Detalle de Artículo
function crearBotonCompra(event){
    if(event.target.classList.contains("botoncomprar")){
        let botoncompra = event.target.id;
        let comprado = false;
        articulos.forEach(function(articulo){
            if(botoncompra.substring(5, botoncompra.length) == articulo){
                comprado = true;
            }
        });

        if(comprado){
            alert("El artículo YA está en la cesta");
        }
        else{
            articulos.push(botoncompra.substring(5, botoncompra.length));
            cestaSesion();
            alert("Artículo añadido a la cesta");
        }
    }
}

// Función que muestra con PROMESAS la información de la cesta para realizar el pago
function pasarelaCarrito(){
    datosCesta = [];

    // Creamos un array de promesas para todas las llamadas a AJAX
    let promesas = articulos.map(function(articulo){
        return cargarCesta(articulo);
    })

    // Obligamos a esperar a que todas las promesas se completen antes de continuar
    Promise.all(promesas)
        .then(function(){
            document.getElementById("productoscesta").innerHTML = "";
            mostrarCestaPantalla();
        })
        .catch(function(error){
            console.error(error);
        });
}

// Función que muestra los artículos en la cesta
function mostrarCestaPantalla(){
    let suma = 0;
    let sumasiniva = 0;

    let tituloscesta = [
        ["cestaceldas cestatitulos cestatituloalign cestacabecera", "ARTÍCULO"],
        ["cestaceldas cestaprecios cestacabecera", "PRECIO SIN IVA"],
        ["cestaceldas cestaprecios cestacabecera", "PRECIO IVA"],
        ["cestaceldas cestaprecios cestacabecera", "ELIMINAR"]
    ];

    let contenido = document.createElement("div");
    contenido.className = "revisionceldas";
    contenido.id = "revisionceldas";
    document.getElementById("productoscesta").appendChild(contenido);

    for(let i = 0; i < tituloscesta.length; i++){
        let contenido = document.createElement("div");
        contenido.className = tituloscesta[i][0];
        contenido.innerHTML = tituloscesta[i][1];
        document.getElementById("revisionceldas").appendChild(contenido);
    }

    document.getElementById("productoscesta").appendChild(contenido);

    for(let i = 0; i < datosCesta.length; i++){
        let fila = document.createElement("div");
        fila.className = "revisionceldas";
        fila.id = "revisionceldas" + i;
        document.getElementById("productoscesta").appendChild(fila);

        let divNombre = document.createElement("div");
        let divPrecioSin = document.createElement("div");
        let divPrecio = document.createElement("div");
        let divEliminar = document.createElement("div");

        let posicionClass;

        if(i == 0){
            posicionClass = "cestaceldas cestaprimeralinea";
            divNombre.className = posicionClass + " cestaprimeralineai cestatitulos cestatituloalign";
            divPrecioSin.className = posicionClass + " cestaprecios";
            divPrecio.className = posicionClass + " cestaprecios";
            divEliminar.className = posicionClass + " cestaprimeralinead cestaprecios";
        }
        else if(i == datosCesta.length - 1){
            posicionClass = "cestaceldas cestaultimalinea";
            divNombre.className = posicionClass + " cestaultimalineai cestatitulos cestatituloalign";
            divPrecioSin.className = posicionClass + " cestaprecios";
            divPrecio.className = posicionClass + " cestaprecios";
            divEliminar.className = posicionClass + " cestaultimalinead cestaprecios";
        }
        else{
            posicionClass = "cestaceldas";
            divNombre.className = posicionClass + " cestatitulos cestatituloalign";
            divPrecioSin.className = posicionClass + " cestaprecios";
            divPrecio.className = posicionClass + " cestaprecios";
            divEliminar.className = posicionClass + " cestaprecios";
        }
        suma += datosCesta[i].precio;

        divNombre.innerHTML = datosCesta[i].nombre;
        divPrecioSin.innerHTML = ((datosCesta[i].precio) / (1 + 21 / 100)).toFixed(2) + "€";
        divPrecio.innerHTML = datosCesta[i].precio + "€";
        divEliminar.innerHTML = "<button class='botoneliminar' id='eliminararticulo" + datosCesta[i].codigo + "'>ELIMINAR</button>";

        document.getElementById("revisionceldas" + i).appendChild(divNombre);
        document.getElementById("revisionceldas" + i).appendChild(divPrecioSin);
        document.getElementById("revisionceldas" + i).appendChild(divPrecio);
        document.getElementById("revisionceldas" + i).appendChild(divEliminar);

        generarBotonesEliminar(datosCesta[i].codigo);
    }

    sumasiniva = (suma / (1 + 21 / 100)).toFixed(2);

    document.getElementById("precioscesta").innerHTML = "";
    contenido = document.createElement("div");
    contenido.className = "cestafilabotones";
    contenido.id = "cestafilatotales";
    document.getElementById("precioscesta").appendChild(contenido);

    document.getElementById("cestafilatotales").innerHTML = "";
    contenido = document.createElement("div");
    contenido.className = "cestaceldas";
    contenido.innerHTML = "Precio SIN IVA: " + sumasiniva + "€<br>Precio CON IVA: " + suma.toFixed(2) + "€";
    document.getElementById("cestafilatotales").appendChild(contenido);
}

// Función para generar los botones de eliminar artículos de la cesta
function generarBotonesEliminar(opcion){
    let botonEliminar = document.getElementById("eliminararticulo" + opcion);
    botonEliminar.removeEventListener("click", crearEliminarArticulo);
    botonEliminar.addEventListener("click", crearEliminarArticulo);
}

// Función que crea los botones de eliminar artículos de la cesta
function crearEliminarArticulo(event){
    if(event.target.classList.contains("botoneliminar")){
        let botoncompra = event.target.id;
        let articulo = articulos.indexOf(botoncompra.substring(16, event.target.id.length));
        articulos.splice(articulo, 1);
        cestaSesion();
        pasarelaCarrito();
    }
}