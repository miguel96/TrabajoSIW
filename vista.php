<?php
//TODO Hay que cambiar los titulos en casi todas las vistas
function cabecera($cadena){
  header("Content-Type: text/html; charset=iso-8859-1");
  $cabecera=file_get_contents("cabecera.html");
  $cabecera=str_replace("##principal##","<a href='controlador.php'>Inicio</a>",$cabecera);
  $cabecera=str_replace("##mispedidos##","<a href='controlador.php?accion=mispedidos'>Mis pedidos</a>",$cabecera);
  $cabecera=str_replace("##misvaloraciones##","<a href='controlador.php?accion=misvaloraciones'>Mis valoraciones</a>",$cabecera);
  $cabecera=str_replace("##carrito##","<a href='controlador.php?accion=pedido'>Carrito</a>",$cabecera);
  if (cislogged()){
    $cabecera=str_replace("##login##","<a href='controlador.php?accion=logout'>Cerrar Sesi&oacuten</a>",$cabecera);
  }
  else{
    $cabecera=str_replace("##login##","<a href='controlador.php?accion=login'>Iniciar Sesi&oacuten</a>",$cabecera);
  }
  $cadena=str_replace("##cabecera##",$cabecera,$cadena);
  $cadena=footer($cadena);
  return $cadena;
}
function footer($cadena){
	$footer=file_get_contents("footer.html");
	$cadena=str_replace("##footer##",$footer,$cadena);
	return $cadena;
}
function vmostrarproducto($listadoproducto,$listadoreviews){
  //Vamos a montar el html
  $cadena = file_get_contents("producto.html");
  $cadena = cabecera($cadena);
  $cadena = str_replace("##titulo##", $listadoproducto["Nombre"], $cadena);
  $cadena = str_replace("##nombre##", $listadoproducto["Nombre"], $cadena);
  $cadena = str_replace("##precio##", $listadoproducto["Precio"], $cadena);
  $cadena = str_replace("##descripcion##", $listadoproducto["Descripcion"], $cadena);
  $cadena = str_replace("##cantidad##", $listadoproducto["Stock"], $cadena);
  //Bucle para montar los comentarios
  $comentarios="";
  $trozoscomentarios = explode("##comentario##", $cadena);
  $comentarios="";
  if($listadoreviews!="")
    foreach($listadoreviews as $datos){
      $aux = $trozoscomentarios[1];
      $aux = str_replace("##nombreUsuario##", $datos["Nombre"], $aux);
      $aux = str_replace("##apellido1Usuario##", $datos["apellido1"], $aux);
      $aux = str_replace("##apellido2Usuario##", $datos["apellido2"], $aux);
      $aux = str_replace("##texto##", $datos["Comentario"], $aux);
      $aux = str_replace("##valoracion##", $datos["Valoracion"], $aux);
      $comentarios .= $aux;
    }
  //}
  //Imprimimos el html
  $cadena = $trozoscomentarios[0] . $comentarios . $trozoscomentarios[2];
  $cadena=str_replace("##addCarrito##", "<a href=controlador.php?accion=addCarrito&id=" .$listadoproducto["IdProducto"]  ."> A&ntildeadir al carrito</a>", $cadena);
  echo $cadena;
}

function vmostrarmisvaloraciones($listadoreviews){
  $cadena = file_get_contents("misvaloraciones.html");
  $cadena = cabecera($cadena);
  $cadena = str_replace("##titulo##", "Rufocube-Listado de valoraciones", $cadena);
  //Bucle para montar los comentarios
  $comentarios="";
  $trozoscomentarios = explode("##comentario##", $cadena);
  if($listadoreviews!="")
    foreach($listadoreviews as $datos){
      $aux = $trozoscomentarios[1];
      $aux = str_replace("##nombreProducto##", $datos["Nombre"], $aux);
      $aux = str_replace("##texto##", $datos["Comentario"], $aux);
      $aux = str_replace("##valoracion##", $datos["Valoracion"], $aux);
      $comentarios .= $aux;
    }
  echo $trozoscomentarios[0] . $comentarios  . $trozoscomentarios[2];
}

function vmostrarmispedidos($listadopedidos){
  $cadena = file_get_contents("mispedidos.html");
  $cadena = cabecera($cadena);
  $cadena = str_replace("##titulo##", "Rufocube-Listado de pedidos", $cadena);
  //Bucle para montar los comentarios
  $pedidos="";
  $trozospedidos = explode("##pedido##", $cadena);
  $pedidos="";
  if($listadopedidos!="")
    foreach($listadopedidos as $datos){
      $aux = $trozospedidos[1];
      $aux = str_replace("##fecha##", $datos["fecha"], $aux);
      $aux = str_replace("##precio##", $datos["precio"], $aux);
      $aux = str_replace("##mas##","<a href='controlador.php?accion=pedidoapdf&id=".$datos["idPedido"]."'>Exportar</a>",$aux);
      switch ($datos["estado"]) {
        case 1:
          $aux = str_replace("##estado##", "Procesado", $aux);
          break;
        case 2:
          $aux= str_replace("##estado##", "Enviado", $aux);
        default:
            $aux= str_replace("##estado##", "Error", $aux);
          break;
      }
      $pedidos .= $aux;
    }
  echo $trozospedidos[0] . $pedidos  . $trozospedidos[2];
}

function vmostrarpedido($listadoproductos){
  $cadena = file_get_contents("pedido.html");
  $cadena = cabecera($cadena);
  $cadena = str_replace("##titulo##", "Rufocube-  Tu carrito", $cadena);
  //Bucle para montar los comentarios
  $productos="";
  $trozosproductos = explode("##producto##", $cadena);
  $precio=0;
  $productos="";
  if($listadoproductos!="")
      foreach ($listadoproductos as $producto) {
        $aux = $trozosproductos[1];
        $aux = str_replace("##nombre##", $producto["Nombre"], $aux);
        $aux = str_replace("##precio##", $producto["Precio"], $aux);
        $precio=$precio+$producto["Precio"];
        $productos .= $aux;
      }
  $cadena=$trozosproductos[0] . $productos  . $trozosproductos[2];
  $cadena=str_replace("##precioTotal##",$precio."&#8364",$cadena);
  $cadena=str_replace("##pagarAhora##",vpagarpaypal($precio),$cadena);
  echo $cadena;
}
function vpedidotoPDF($listadoproductos){
  require('pdfFac.php');
  if($listadoproductos!=""){
      $precioTotal=0;
      $articulos=0;
      $pdf=new pdfFac();
      $pdf->startPage($listadoproductos['infoPedido']["fecha"]);
      foreach ($listadoproductos['productos'] as $producto) {
        $precioTotal+=$producto["Precio"];
        $articulos++;
        $pdf->insertaTupla($producto["Nombre"],$producto["Precio"]);
      }
      $pdf->insertaTotal($articulos,$precioTotal);
    }
  $pdf->Output();
}
function vpagarpaypal($precio){
  $cadena =file_get_contents("paypal.html");
  $cadena = str_replace("##valor##",$precio,$cadena);
  return $cadena;
}
//Codigo de ruben
  function vmostrarlistadoproductos($consulta){
		$cadena = file_get_contents("principal.html");
    $cadena = str_replace("##Titulo##","Rufocube",$cadena);
    $cadena = cabecera($cadena);
		$trozos = explode("##productos##", $cadena);
		$cuerpo = "";
		while($fila = mysqli_fetch_assoc($consulta)){
			$aux = $trozos[1];
      $nombre = $fila['nombre'];
      $precio = $fila['precio'];
      $idProducto=$fila['idProducto'];
      $imagen=$fila['Imagen'];
      $aux = str_replace("##linkProducto##","controlador.php?accion=producto&id=".$idProducto,$aux);
			$aux = str_replace("##foto##", "$imagen", $aux);
			$aux = str_replace("##nombre##", "$nombre", $aux);
			$aux = str_replace("##precio##", "$precio &#8364", $aux);
			$cuerpo .= $aux;
		}
	echo $trozos[0] . $cuerpo . $trozos[2];
	}

	function vmostrarlogin(){
		$cadena = file_get_contents("login.html");
    $cadena = cabecera($cadena);
    $cadena=str_replace("##titulo##","Rufocube-Login",$cadena);
		echo str_replace("##cabecera##", file_get_contents("cabecera.html"),$cadena);
	}

	function vmostrarregistro(){
		$cadena = file_get_contents("registro.html");
		$cadena = cabecera($cadena);
    $cadena=str_replace("##titulo##","Rufocube-Registro",$cadena);
		echo $cadena;
	}
?>
