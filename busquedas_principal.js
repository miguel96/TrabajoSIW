max=6;
function buscar(){
  $.ajax({
    url: 'controlador.php?accion=buscar&texto='+($("#buscar").val()),
    success: function (response) {
      var infoProductos=JSON.parse(response);
      const cadena="<li id=\"principalproducto\"><a class=\"foto\" href=\"##linkProducto##\"><img class=\"foto\" src=\"##foto##\" /></a><div id=\"nombreprecio\"><a id=\"nombre\" href=\"##linkProducto##\"><p>##nombre##</p></a><p>##precio##</p></div></li>"
      cuerpo="";
      fila=0;
      while(fila<infoProductos.length&&fila<max){
        aux = cadena;
        nombre = infoProductos[fila]["Nombre"];
        precio = infoProductos[fila]["Precio"];
        idProducto=infoProductos[fila]["IdProducto"];
        imagen=infoProductos[fila]["Imagen"];
        aux = aux.replace("##linkProducto##","controlador.php?accion=producto&id="+idProducto);
  			aux = aux.replace("##foto##", imagen);
  			aux = aux.replace("##nombre##",nombre);
  			aux = aux.replace("##precio##",precio+"&#8364");
  			cuerpo += aux;
        fila++;
      }
      $("#listaProducto").html(cuerpo);
    },
    error: function(response){
      alert(3);
    }
  });
}
function mas(){
  max=max+6;
  buscar();
}
function menos(){
  if(max>6){
    max=max-6;
  }
}
