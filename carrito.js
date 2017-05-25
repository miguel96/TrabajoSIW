function mas(id,stock){
  var cookie=document.cookie.split("Carrito=");
  var carrito=JSON.parse((decodeURIComponent(cookie[1])));
  if(carrito[id]<stock)
    carrito[id]=carrito[id]+1;
  document.cookie = "Carrito=" + encodeURIComponent( JSON.stringify(carrito) );
  $("#Cantidad"+id).html(carrito[id]);
}
function menos(id){
  var cookie=document.cookie.split("Carrito=");
  var carrito=JSON.parse((decodeURIComponent(cookie[1])));
  if(carrito[id]>1){
    carrito[id]=carrito[id]-1;
    $("#Cantidad"+id).html(carrito[id]);
  }
  else{
    delete carrito[id];
    $("#Cantidad"+id).html(0);
  }
  document.cookie = "Carrito=" + encodeURIComponent( JSON.stringify(carrito) );

}
