function mas(id){
  var cookie=document.cookie.split("Carrito=")[1];
  alert(cookie);
  var carrito=JSON.parse(cookie);
}
function menos(){
  if(max>6){
    max=max-6;
  }
}
