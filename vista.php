<?php
function vtoPaypal($precio)
  {
    $cadena = file_get_contents("topaypal.html");
    $cadena = str_replace("##valor##", $precio, $cadena);
    echo $cadena;
  }
function vpedirimagen($formulario)
  {
    $cadena = file_get_contents("dropzone.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-  Anadir Producto", $cadena);
    $cadena = str_replace("##nombre##", $formulario["nombre"], $cadena);
    $cadena = str_replace("##precio##", $formulario["precio"], $cadena);
    $cadena = str_replace("##descripcion##", $formulario["descripcion"], $cadena);
    $cadena = str_replace("##stock##", $formulario["stock"], $cadena);
    echo $cadena;
  }
function vanadirproducto()
  {
    $cadena = file_get_contents("anadirproducto.html");
    $cadena = str_replace("##titulo##", "Rufocube-  Anadir Producto", $cadena);
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    echo $cadena;
  }
function cabecera($cadena)
  {
    header("Content-Type: text/html; charset=iso-8859-1");
    $cabecera = file_get_contents("cabecera.html");
    $cabecera = str_replace("##carrito##", "<a href='controlador.php?accion=pedido'>Carrito</a>", $cabecera);
    if (cislogged() and cisadmin())
      {
        $cabecera = str_replace("##principal##", "<a href='controlador.php?accion=anadirproducto'>A&ntildeadir producto</a>", $cabecera);
        $cabecera = str_replace("##mispedidos##", "<a href='controlador.php?accion=mispedidos'>Ver pedidos</a>", $cabecera);
        $cabecera = str_replace("##misvaloraciones##", "<a href='controlador.php?accion=misvaloraciones'>Ver valoraciones</a>", $cabecera);
        $cabecera = str_replace("##login##", "<a href='controlador.php?accion=cuenta'>Cuentas</a>", $cabecera);
      }
    else if (cislogged())
      {
        $cabecera = str_replace("##principal##", "<a href='controlador.php'>Inicio</a>", $cabecera);
        $cabecera = str_replace("##mispedidos##", "<a href='controlador.php?accion=mispedidos'>Mis pedidos</a>", $cabecera);
        $cabecera = str_replace("##misvaloraciones##", "<a href='controlador.php?accion=misvaloraciones'>Mis valoraciones</a>", $cabecera);
        $cabecera = str_replace("##login##", "<a href='controlador.php?accion=cuenta'>Mi cuenta</a>", $cabecera);
      }
    else
      {
        $cabecera = str_replace("##principal##", "<a href='controlador.php'>Inicio</a>", $cabecera);
        $cabecera = str_replace("##mispedidos##", "<a href='controlador.php?accion=mispedidos'>Mis pedidos</a>", $cabecera);
        $cabecera = str_replace("##misvaloraciones##", "<a href='controlador.php?accion=misvaloraciones'>Mis valoraciones</a>", $cabecera);
        $cabecera = str_replace("##login##", "<a href='controlador.php?accion=login'>Iniciar Sesi&oacuten</a>", $cabecera);
      }
    $cadena = str_replace("##cabecera##", $cabecera, $cadena);
    $cadena = footer($cadena);
    return $cadena;
  }
function footer($cadena)
  {
    $footer = file_get_contents("footer.html");
    $cadena = str_replace("##footer##", $footer, $cadena);
    return $cadena;
  }
function vmostrarcuenta($formulario, $error = "")
  {
    $cadena = file_get_contents("micuenta.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-Tu cuenta", $cadena);
    $cadena = str_replace("##Error##", $error, $cadena);
    $cadena = str_replace("##email##", $formulario['email'], $cadena);
    $cadena = str_replace("##contrasena##", $formulario['contrasena'], $cadena);
    $cadena = str_replace("##nombre##", $formulario['nombre'], $cadena);
    $cadena = str_replace("##apellido1##", $formulario['apellido1'], $cadena);
    $cadena = str_replace("##apellido2##", $formulario['apellido2'], $cadena);
    $cadena = str_replace("##sexo##", $formulario['sexo'], $cadena);
    $cadena = str_replace("##direccion##", $formulario['direccion'], $cadena);
    $cadena = str_replace("##codpos##", $formulario['codpos'], $cadena);

    echo $cadena;
  }
function vmostrarcomentario($id)
  {
    $cadena = file_get_contents("comentario.html");
    $cadena = cabecera($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-Comentario", $cadena);
    $cadena = str_replace("##id##", $id, $cadena);
    echo $cadena;

  }
function vgaleria($imagenes, $id)
  {
    $cadena = file_get_contents("galeria.html");
    $cadena = cabecera($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-galer&iacutea", $cadena);
    $cadena = str_replace("##id##", $id, $cadena);
    $cadena = str_replace("##imagen1##", str_replace("_peq", "_med", $imagenes[0]), $cadena);
    $cadena = str_replace("##imagen3##", str_replace("_peq", "_grande", $imagenes[0]), $cadena);
    $trozos = explode("##imgPeq##", $cadena);
    $cuerpo = "";
    $i      = 0;
    foreach ($imagenes as $imagen)
      {
        $aux = $trozos[1];
        $aux = str_replace("##imagen2##", $imagen, $aux);
        $aux = str_replace("##nImg##", $i, $aux);
        $cuerpo .= $aux;
        $i += 1;
      }
    $trozos[0] = str_replace("##nImgs##", $i, $trozos[0]);

    echo $trozos[0] . $cuerpo . $trozos[2];
  }
function vmostrarbusqueda($busqueda)
  {
    print_r($busqueda);
    $cadena = file_get_contents("principal.html");
    $cadena = str_replace("##titulo##", "Rufocube", $cadena);
    $cadena = str_replace("Productos Destacados", "Resultado busqueda", $cadena);
    $cadena = cabecera($cadena);
    $trozos = explode("##productos##", $cadena);
    $cuerpo = "";

    if ($busqueda = "")
        echo "Lo siento, no se han encontrado datos para su busqueda.";
    else
      {
        foreach ((array) $busqueda as $datos)
          {
            $aux = $trozos[1];
            $aux = str_replace("##linkProducto##", "controlador.php?accion=producto&id=" . $datos["IdProducto"], $aux);
            $aux = str_replace("##foto##", $datos["Imagen"], $aux);
            $aux = str_replace("##nombre##", $datos["Nombre"], $aux);
            $aux = str_replace("##precio##", $datos["Precio"], $aux);
            $cuerpo .= $aux;
          }
        echo $trozos[0] . $cuerpo . $trozos[2];
      }
  }
function vmostrarproducto($listadoproducto, $listadoreviews)
  {
    //Vamos a montar el html
    $cadena            = file_get_contents("producto.html");
    $cadena            = cabecera($cadena);
    $cadena            = str_replace("##titulo##", $listadoproducto["Nombre"], $cadena);
    $cadena            = str_replace("##nombre##", $listadoproducto["Nombre"], $cadena);
    $cadena            = str_replace("##precio##", $listadoproducto["Precio"], $cadena);
    $cadena            = str_replace("##descripcion##", $listadoproducto["Descripcion"], $cadena);
    $cadena            = str_replace("##cantidad##", $listadoproducto["Stock"], $cadena);
    $cadena            = str_replace("##imagen##", "<a href=controlador.php?accion=galeria&id=" . $listadoproducto["id"] . "><img src=" . $listadoproducto["Imagen"] . " class=\"foto\"></a>", $cadena);
    //TODO hacer funcion comentarios y reemplazar link
    $cadena            = str_replace("##addComentario##", "<a href=controlador.php?accion=comentario&id=" . $listadoproducto["id"] . ">Deja tu comentario</a>", $cadena);
    //Bucle para montar los comentarios
    $comentarios       = "";
    $trozoscomentarios = explode("##comentario##", $cadena);
    $comentarios       = "";
    if ($listadoreviews != "")
        foreach ($listadoreviews as $datos)
          {
            $aux = $trozoscomentarios[1];
            $aux = str_replace("##nombreUsuario##", $datos["Nombre"], $aux);
            $aux = str_replace("##apellido1Usuario##", $datos["apellido1"], $aux);
            $aux = str_replace("##apellido2Usuario##", $datos["apellido2"], $aux);
            $aux = str_replace("##texto##", $datos["Comentario"], $aux);
            $aux = str_replace("##valoracion##", $datos["Valoracion"], $aux);
            $aux = str_replace("##imagenCom##", $datos["Imagen"], $aux);
            $comentarios .= $aux;
          }
    $cadena = $trozoscomentarios[0] . $comentarios . $trozoscomentarios[2];
    if ($listadoproducto["Stock"] > 0)
      {
        $cadena = str_replace("##addCarrito##", "<a href=controlador.php?accion=addCarrito&id=" . $listadoproducto["IdProducto"] . "> <img src=\"imagenes/carroTxiki\" ></a>", $cadena);
      }
    else
      {
        $cadena = str_replace("##addCarrito##", "", $cadena);
      }
    echo $cadena;
  }

function vmostrarmisvaloraciones($listadoreviews)
  {
    $cadena            = file_get_contents("misvaloraciones.html");
    $cadena            = cabecera($cadena);
    $cadena            = str_replace("##titulo##", "Rufocube-Listado de valoraciones", $cadena);
    //Bucle para montar los comentarios
    $comentarios       = "";
    $trozoscomentarios = explode("##comentario##", $cadena);
    if ($listadoreviews != "")
        foreach ($listadoreviews as $datos)
          {
            $aux = $trozoscomentarios[1];
            $aux = str_replace("##nombreProducto##", $datos["Nombre"], $aux);
            $aux = str_replace("##texto##", $datos["Comentario"], $aux);
            $aux = str_replace("##valoracion##", $datos["Valoracion"], $aux);
            $aux = str_replace("##imagen##", $datos["Imagen"], $aux);
            $comentarios .= $aux;
          }
    echo $trozoscomentarios[0] . $comentarios . $trozoscomentarios[2];
  }
function vmostrarmisvaloracionesadmin($listadoreviews)
  {
    $cadena            = file_get_contents("misvaloracionesadmin.html");
    $cadena            = cabecera($cadena);
    $cadena            = str_replace("##titulo##", "Rufocube-Listado de valoraciones", $cadena);
    //Bucle para montar los comentarios
    $comentarios       = "";
    $trozoscomentarios = explode("##comentario##", $cadena);
    if ($listadoreviews != "")
        foreach ($listadoreviews as $datos)
          {
            $aux = $trozoscomentarios[1];
            $aux = str_replace("##nombreUsuario##", $datos["nombreUsuario"], $aux);
            $aux = str_replace("##nombreProducto##", $datos["Nombre"], $aux);
            $aux = str_replace("##texto##", $datos["Comentario"], $aux);
            $aux = str_replace("##valoracion##", $datos["Valoracion"], $aux);
            $aux = str_replace("##imagen##", $datos["Imagen"], $aux);
            $comentarios .= $aux;
          }
    echo $trozoscomentarios[0] . $comentarios . $trozoscomentarios[2];
  }

function vmostrarmispedidos($listadopedidos)
  {
    $cadena        = file_get_contents("mispedidos.html");
    $cadena        = cabecera($cadena);
    $cadena        = str_replace("##titulo##", "Rufocube-Listado de pedidos", $cadena);
    //Bucle para montar los comentarios
    $pedidos       = "";
    $trozospedidos = explode("##pedido##", $cadena);
    $pedidos       = "";
    if ($listadopedidos != "")
        foreach ($listadopedidos as $datos)
          {
            $aux = $trozospedidos[1];
            $aux = str_replace("##fecha##", $datos["fecha"], $aux);
            $aux = str_replace("##precio##", round($datos["precio"], 2), $aux);
            $aux = str_replace("##mas##", "<a href='controlador.php?accion=pedidoapdf&id=" . $datos["idPedido"] . "'>Exportar</a>", $aux);
            switch ($datos["estado"])
            {
                case 1:
                    $aux = str_replace("##estado##", "Procesado", $aux);
                    break;
                case 2:
                    $aux = str_replace("##estado##", "Enviado", $aux);
                default:
                    $aux = str_replace("##estado##", "Error", $aux);
                    break;
            }
            $pedidos .= $aux;
          }
    echo $trozospedidos[0] . $pedidos . $trozospedidos[2];
  }

function vmostrarmispedidosadmin($listadopedidos)
  {
    $cadena        = file_get_contents("mispedidosadmin.html");
    $cadena        = cabecera($cadena);
    $cadena        = str_replace("##titulo##", "Rufocube-Listado de pedidos", $cadena);
    //Bucle para montar los comentarios
    $pedidos       = "";
    $trozospedidos = explode("##pedido##", $cadena);
    $pedidos       = "";
    if ($listadopedidos != "")
        foreach ($listadopedidos as $datos)
          {
            $aux = $trozospedidos[1];
            $aux = str_replace("##nombre##", $datos["nombre"], $aux);
            $aux = str_replace("##fecha##", $datos["fecha"], $aux);
            $aux = str_replace("##precio##", round($datos["precio"], 2), $aux);
            $aux = str_replace("##mas##", "<a href='controlador.php?accion=pedidoapdf&id=" . $datos["idPedido"] . "'>Exportar</a>", $aux);
            switch ($datos["estado"])
            {
                case 1:
                    $aux = str_replace("##estado##", "Procesado", $aux);
                    break;
                case 2:
                    $aux = str_replace("##estado##", "Enviado", $aux);
                default:
                    $aux = str_replace("##estado##", "Error", $aux);
                    break;
            }
            $pedidos .= $aux;
          }
    echo $trozospedidos[0] . $pedidos . $trozospedidos[2];
  }

function vmostrarpedido($listadoproductos)
  {
    $cadena          = file_get_contents("pedido.html");
    $cadena          = cabecera($cadena);
    $cadena          = str_replace("##titulo##", "Rufocube-  Tu carrito", $cadena);
    //Bucle para montar los comentarios
    $productos       = "";
    $trozosproductos = explode("##producto##", $cadena);
    $precio          = 0;
    $productos       = "";
    $cantidadTotal   = 0;
    if ($listadoproductos != "")
      {
        foreach ($listadoproductos as $producto)
          {
            $aux    = $trozosproductos[1];
            $aux    = str_replace("##nombre##", $producto["Nombre"], $aux);
            $aux    = str_replace("##precio##", round($producto["Precio"], 2), $aux);
            $aux    = str_replace("##imagen##", $producto["Imagen"], $aux);
            $aux    = str_replace("##cantidad##", $producto["Cantidad"], $aux);
            $aux    = str_replace("##id##", $producto["idProducto"], $aux);
            $aux    = str_replace("##stock##", $producto["Stock"], $aux);
            $precio = $precio + $producto["Precio"];
            $productos .= $aux;
            $cantidadTotal += $producto["Cantidad"];
          }
        $trozosproductos[2] = str_replace("##nart##", sizeof($listadoproductos), $trozosproductos[2]);
        $trozosproductos[2] = str_replace("##cantTot##", $cantidadTotal, $trozosproductos[2]);
      }
    else
      {
        $trozosproductos[2] = str_replace("##nart##", "No hay ", $trozosproductos[2]);
        $trozosproductos[2] = str_replace("##cantTot##", "0 ", $trozosproductos[2]);
      }
    $cadena = $trozosproductos[0] . $productos . $trozosproductos[2];
    $cadena = str_replace("##precioTotal##", $precio . "&#8364", $cadena);

    echo $cadena;
  }
function vpedidotoPDF($listadoproductos)
  {
    require('pdfFac.php');
    if ($listadoproductos != "")
      {
        $precioTotal = 0;
        $articulos   = 0;
        $pdf         = new pdfFac();
        $pdf->startPage($listadoproductos['infoPedido']["fecha"]);
        foreach ($listadoproductos['productos'] as $producto)
          {
            $precioTotal += $producto["Precio"];
            $articulos++;
            $pdf->insertaTupla($producto["Nombre"], $producto["Precio"]);
          }
        $pdf->insertaTotal($articulos, $precioTotal);
      }
    $pdf->Output();
  }

function vmostrarlistadoproductos($consulta)
  {
    $cadena = file_get_contents("principal.html");
    $cadena = str_replace("##Titulo##", "Rufocube", $cadena);
    $cadena = cabecera($cadena);
    echo $cadena;
  }

function vmostrarlogin()
  {
    $cadena = file_get_contents("login.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-Login", $cadena);
    echo str_replace("##cabecera##", file_get_contents("cabecera.html"), $cadena);
  }

function vmostrarregistro()
  {
    $cadena = file_get_contents("registro.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("##titulo##", "Rufocube-Registro", $cadena);
    $cadena = str_replace("<div class=\"campoFormulario\"><p>##Error##</p></div>", "", $cadena);
    $cadena = str_replace("##email##", "", $cadena);
    $cadena = str_replace("##contrasena##", "", $cadena);
    $cadena = str_replace("##contrasena1##", "", $cadena);
    $cadena = str_replace("##nombre##", "", $cadena);
    $cadena = str_replace("##apellido1##", "", $cadena);
    $cadena = str_replace("##apellido2##", "", $cadena);
    $cadena = str_replace("##sexo##", "", $cadena);
    $cadena = str_replace("##direccion##", "", $cadena);
    $cadena = str_replace("##codpos##", "", $cadena);
    echo $cadena;
  }
function vmostrarregistro2($resultado, $formulario)
  {
    $cadena = file_get_contents("registro.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("##Error##", $resultado, $cadena);
    $cadena = str_replace("##titulo##", "Rufocube-Registro", $cadena);
    if ($resultado == "El email ya existe." || $resultado == "Esta direcci&oacuten de correo no es v&aacutelida.")
      {
        $cadena = str_replace("##email##", "", $cadena);
      }
    else
      {
        $cadena = str_replace("##email##", $formulario["email"], $cadena);
      }
    if ($resultado == "Las contrase&ntildeas no coinciden.")
      {
        $cadena = str_replace("##contrasena##", "", $cadena);
        $cadena = str_replace("##contrasena1##", "", $cadena);
      }
    else
      {
        $cadena = str_replace("##contrasena##", $formulario["contrasena"], $cadena);
        $cadena = str_replace("##contrasena1##", $formulario["contrasena1"], $cadena);
      }
    $cadena = str_replace("##nombre##", $formulario["nombre"], $cadena);
    $cadena = str_replace("##apellido1##", $formulario["apellido1"], $cadena);
    $cadena = str_replace("##apellido2##", $formulario["apellido2"], $cadena);
    $cadena = str_replace("##sexo##", $formulario["sexo"], $cadena);
    $cadena = str_replace("##direccion##", $formulario["direccion"], $cadena);
    $cadena = str_replace("##codpos##", $formulario["codpos"], $cadena);
    echo $cadena;
  }

function vmicuenta($formulario)
  {
    $cadena = file_get_contents("registro.html");
    $cadena = cabecera($cadena);
    $cadena = footer($cadena);
    $cadena = str_replace("accion=compruebaregistro", "accion=actualizacuenta", $cadena);
    $cadena = str_replace("##Error##", "", $cadena);
    $cadena = str_replace("##titulo##", "Rufocube-MiCuenta", $cadena);
    $cadena = str_replace("##email##", $formulario["email"], $cadena);
    $cadena = str_replace("##contrasena##", $formulario["contrasena"], $cadena);
    $cadena = str_replace("##contrasena1##", "", $cadena);
    $cadena = str_replace("##nombre##", $formulario["nombre"], $cadena);
    $cadena = str_replace("##apellido1##", $formulario["apellido1"], $cadena);
    $cadena = str_replace("##apellido2##", $formulario["apellido2"], $cadena);
    $cadena = str_replace("##sexo##", $formulario["sexo"], $cadena);
    $cadena = str_replace("##direccion##", $formulario["direccion"], $cadena);
    $cadena = str_replace("##codpos##", $formulario["codpos"], $cadena);
    echo $cadena;
  }
?>
