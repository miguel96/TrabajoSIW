var selected=0;
var max;
function setMediana(clickada,id){
  document.getElementById("imagenMediana").src=clickada.replace("_peq","_grande");
  selected=id;
}
function setGrande(){
  document.getElementById("telon").style.visibility="visible";
  document.getElementById("contenedorImagenGrande").style.visibility="visible";
  document.getElementById("footer").style.visibility="hidden";
}
function cerrarGrande(){
  document.getElementById("telon").style.visibility="hidden";
  document.getElementById("contenedorImagenGrande").style.visibility="hidden";
  document.getElementById("footer").style.visibility="visible";
}
function clickedDerecha(){
  if(selected<max-1){
    selected=selected+1;
  }
  else{
    selected=0;
  }
  document.getElementById("imagenGrande").src=document.getElementById(selected.toString()).src.replace("_peq","_grande")
}
function clickedIzquierda(){
  if(selected>0){
    selected=selected-1;
  }
  else {
      selected=max-1;
  }
  document.getElementById("imagenGrande").src=document.getElementById(selected.toString()).src.replace("_peq","_grande")
}
function start(i) {
  max=i;
}
