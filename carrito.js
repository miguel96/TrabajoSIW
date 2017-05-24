function mas(id){
  var cookie=document.cookie.split("Carrito=");
  var carrito=JSON.parse((decodeURIComponent(cookie[1])));  
  carrito[id]=carrito[id]+1;
    document.cookie = "Carrito=" + encodeURIComponent( JSON.stringify(carrito) );
  $("#Cantidad").html(carrito[id]);
}
function menos(id){
  var cookie=document.cookie.split("Carrito=");
  var carrito=JSON.parse((decodeURIComponent(cookie[1])));
  if(carrito[id]>1){
    carrito[id]=carrito[id]-1;
    $("#Cantidad").html(carrito[id]);
  }
  else{
    delete carrito[id];
    $("#Cantidad").html(0);
  }
  document.cookie = "Carrito=" + encodeURIComponent( JSON.stringify(carrito) );

}
