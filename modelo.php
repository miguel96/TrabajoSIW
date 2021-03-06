<?php
function mpagar($idUsuario, $productos)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    //Creamos el pedido
    if (!($sentencia = $mysqli->prepare('INSERT INTO final_pedido (estado,idUsuario)VALUES (1,?)')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $idUsuario))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->close();
    //Obtenemos el id de pedido creado
    if (!($sentencia = $mysqli->prepare('SELECT idPedido FROM final_pedido where idUsuario=? order by fecha desc limit 0,1')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $idUsuario))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($idPedido))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    $sentencia->close();
    //Insertamos los productos
    if (!($sentencia = $mysqli->prepare('INSERT INTO productospedidos (idProducto,idPedido,cantidad) VALUES (?,?,?)')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("iii", $idProducto, $idPedido, $cantidad))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    foreach ($productos as $idProducto => $cantidad)
      {
        if (!$sentencia->execute())
          {
            echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
          }
      }
    $sentencia->close();
    //Calculamos el precio del pedido
    if (!($sentencia = $mysqli->prepare('SELECT (p.precio*pp.cantidad) FROM final_productospedidos pp,producto p where p.idProducto=pp.idProducto and pp.idPedido=?')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $idPedido))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($precio))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $precioTotal = 0;
    while ($sentencia->fetch())
      {
        $precioTotal += $precio;
      }
    $sentencia->close();
    //Restamos los productos comprados a nuestro stock
    if (!($sentencia = $mysqli->prepare('UPDATE producto SET stock=stock-? WHERE idProducto=?')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("ii", $cantidad, $idProducto))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    foreach ($productos as $idProducto => $cantidad)
      {
        if (!$sentencia->execute())
          {
            echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
          }
      }
    $sentencia->close();
    return $precioTotal;
  }
function msavecomentario($formulario)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    //Preparamos la consulta
    if (!($sentencia = $mysqli->prepare('INSERT INTO reviews (IdUsuario,IdProducto,Valoracion,Comentario) VALUES (?, ?, ?,?)')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("iiis", $formulario["idUsuario"], $formulario["idProducto"], $formulario["valoracion"], $formulario["comentario"]))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
  }
function mgaleria($id)
  {
    $id       = $_GET["id"];
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    //Preparamos la consulta
    if (!($sentencia = $mysqli->prepare('Select ImagenPeq FROM final_productosimagenes WHERE Idproducto=?')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i = 0;
    while ($sentencia->fetch())
      {
        $resultados[$i] = $resultado;
        $i += 1;
      }
    $sentencia->close();
    //Devolvemos el producto
    mysqli_close($mysqli);
    return $resultados;
  }
function mbuscar($busqueda)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    $busqueda = "%" . $busqueda . "%";
    //Preparamos la consulta
    $i        = 0;
    if (!($sentencia = $mysqli->prepare("SELECT p.idProducto,p.nombre,p.precio, pi.Imagen FROM final_producto p, final_productosimagenes pi
                                            WHERE p.idProducto=pi.idProducto AND (p.nombre LIKE ? OR p.descripcion LIKE ?) AND pi.principal=1 ")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("ss", $busqueda, $busqueda))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["IdProducto"], $resultado["Nombre"], $resultado["Precio"], $resultado["Imagen"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $resultados = "";
    while ($sentencia->fetch())
      {
        $resultados[$i]["IdProducto"] = $resultado["IdProducto"];
        $resultados[$i]["Nombre"]     = $resultado["Nombre"];
        $resultados[$i]["Precio"]     = $resultado["Precio"];
        $resultados[$i]["Imagen"]     = $resultado["Imagen"];
        $i                            = $i + 1;
      }
    $sentencia->close();
    //Devolvemos el producto
    mysqli_close($mysqli);
    echo json_encode($resultados);
    return $resultados;
  }
function mmostrarproducto()
  {
    $id       = $_GET["id"];
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    //Preparamos la consulta
    if (!($sentencia = $mysqli->prepare('Select p.IdProducto,p.Nombre,p.Precio,p.Descripcion,p.Stock,pi.Imagen FROM final_producto p, final_productosimagenes pi WHERE p.IdProducto=? and pi.Idproducto=p.idProducto limit 0,1')))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["IdProducto"], $resultado["Nombre"], $resultado["Precio"], $resultado["Descripcion"], $resultado["Stock"], $resultado["Imagen"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    $sentencia->close();
    $resultado["id"] = $id;
    //Devolvemos el producto
    mysqli_close($mysqli);
    return $resultado;
  }

function mmostrarreviewsproducto()
  {
    $id       = $_GET["id"];
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT Valoracion,Comentario,Imagen,Nombre,apellido1,apellido2 FROM final_reviews R, final_usuarios U WHERE IdProducto=? AND R.IdUsuario=U.IdUsuario ")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["Valoracion"], $resultado["Comentario"], $resultado["Imagen"], $resultado["Nombre"], $resultado["apellido1"], $resultado["apellido2"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        $resultados[$i]["Valoracion"] = $resultado["Valoracion"];
        $resultados[$i]["Comentario"] = $resultado["Comentario"];
        $resultados[$i]["Imagen"]     = $resultado["Imagen"];
        $resultados[$i]["Nombre"]     = $resultado["Nombre"];
        $resultados[$i]["apellido1"]  = $resultado["apellido1"];
        $resultados[$i]["apellido2"]  = $resultado["apellido2"];
        $i                            = $i + 1;
      }
    $sentencia->close();
    //Devolvemos el producto
    mysqli_close($mysqli);
    return $resultados;
  }
function mmostrarreviewsadmin()
  {
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT u.Nombre,Valoracion,Comentario,Imagen,P.Nombre FROM final_reviews R, final_producto P,final_usuarios u WHERE R.IdProducto=P.IdProducto and R.idUsuario=u.idUsuario ORDER BY R.Fecha")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["nombreUsuario"], $resultado["Valoracion"], $resultado["Comentario"], $resultado["Imagen"], $resultado["Nombre"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        $resultados[$i]["nombreUsuario"] = $resultado["nombreUsuario"];
        $resultados[$i]["Valoracion"]    = $resultado["Valoracion"];
        $resultados[$i]["Comentario"]    = $resultado["Comentario"];
        $resultados[$i]["Imagen"]        = $resultado["Imagen"];
        $resultados[$i]["Nombre"]        = $resultado["Nombre"];
        $i                               = $i + 1;
      }
    $sentencia->close();
    mysqli_close($mysqli);
    return $resultados;
  }

function mmostrarreviewsusuario($id)
  {
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT Valoracion,Comentario,Imagen,Nombre FROM final_reviews R, final_producto P WHERE IdUsuario=? AND R.IdProducto=P.IdProducto ORDER BY R.Fecha")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["Valoracion"], $resultado["Comentario"], $resultado["Imagen"], $resultado["Nombre"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        $resultados[$i]["Valoracion"] = $resultado["Valoracion"];
        $resultados[$i]["Comentario"] = $resultado["Comentario"];
        $resultados[$i]["Imagen"]     = $resultado["Imagen"];
        $resultados[$i]["Nombre"]     = $resultado["Nombre"];
        $i                            = $i + 1;
      }
    $sentencia->close();
    mysqli_close($mysqli);
    return $resultados;
  }
function mmostrarpedidoadmin()
  {
    //Buscamos la id en la sesion o en la url, si no no mostramos nada
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT u.Nombre, estado,fecha,SUM(pr.Precio) precio ,p.idPedido FROM final_pedido p,final_productospedidos r,final_producto pr, final_usuarios u WHERE p.idPedido=r.idPedido AND pr.idProducto=r.idProducto AND u.idUsuario=p.idUsuario GROUP BY fecha,estado,p.idPedido,u.Nombre     ORDER BY fecha")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["nombre"], $resultado["estado"], $resultado["fecha"], $resultado["precio"], $resultado["idPedido"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        //Si copiamos el array de golpe se pasa por referencia
        $resultados[$i]["nombre"]   = $resultado["nombre"];
        $resultados[$i]["estado"]   = $resultado["estado"];
        $resultados[$i]["fecha"]    = $resultado["fecha"];
        $resultados[$i]["precio"]   = $resultado["precio"];
        $resultados[$i]["idPedido"] = $resultado["idPedido"];
        $i                          = $i + 1;
      }

    mysqli_close($mysqli);
    return $resultados;
  }

function mmostrarpedidosusuario($id)
  {
    //Buscamos la id en la sesion o en la url, si no no mostramos nada
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT estado,fecha,SUM(pr.Precio) precio ,p.idPedido FROM final_pedido p,final_productospedidos r,final_producto pr WHERE p.idPedido=r.idPedido AND pr.idProducto=r.idProducto AND p.idUsuario=? GROUP BY fecha,estado,p.idPedido ORDER BY fecha")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    ;
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["estado"], $resultado["fecha"], $resultado["precio"], $resultado["idPedido"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        //Si copiamos el array de golpe se pasa por referencia
        $resultados[$i]["estado"]   = $resultado["estado"];
        $resultados[$i]["fecha"]    = $resultado["fecha"];
        $resultados[$i]["precio"]   = $resultado["precio"];
        $resultados[$i]["idPedido"] = $resultado["idPedido"];
        $i                          = $i + 1;
      }

    mysqli_close($mysqli);
    return $resultados;
  }

function mmostrarpedido()
  {
    if (isset($_COOKIE['Carrito']))
      {
        $productos = json_decode($_COOKIE['Carrito']); //LineaCambiada
      }
    else
      {
        $productos = array();
      }
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT p.idProducto, p.Nombre,p.Descripcion,p.Precio,p.Stock,pi.Imagen FROM final_producto p, final_productosimagenes pi WHERE p.idProducto=? and p.idProducto=pi.idProducto limit 0,1")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    $i          = 0;
    $resultados = "";
    foreach ($productos as $id => $cantidad)
      {
        $i = $i + 1;
        if (!$sentencia->bind_param("i", $id))
          {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        if (!$sentencia->execute())
          {
            echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        if (!$sentencia->bind_result($resultados[$i]["idProducto"], $resultados[$i]["Nombre"], $resultados[$i]["Descripcion"], $resultados[$i]["Precio"], $resultados[$i]["Stock"], $resultados[$i]["Imagen"]))
          {
            echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        $sentencia->fetch();
        $resultados[$i]["Cantidad"] = $cantidad;
      }
    mysqli_close($mysqli);
    return $resultados;
  }

function mgetUsuario($email)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT idUsuario FROM final_usuarios WHERE email=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    $resultados = "";
    if (!$sentencia->bind_param("s", $email))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    mysqli_close($mysqli);
    return $resultado;
  }
function mestalogin($id, $contrasena)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT count(idUsuario) FROM final_usuarios WHERE idUsuario=? and password=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    $resultado = "";
    if (!$sentencia->bind_param("is", $id, $contrasena))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    mysqli_close($mysqli);
    return $resultado == 1;
  }
//Codigo Ruben

function mpedidopertenece($idPedido, $idUsuario)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT count(idPedido) FROM final_pedido WHERE idPedido=? and idUsuario=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    $resultado = "";
    if (!$sentencia->bind_param("ii", $idPedido, $idUsuario))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    mysqli_close($mysqli);
    return $resultado > 0;
  }

function mmostrarproductospedidoid($id)
  {
    //Codigo para coger info de bbdd
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    //Conectamos
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if ($mysqli->connect_errno)
      {
        die("Conexion fallida:" . $mysqli->connect_error . ".\n");
      }
    if (!($sentencia = $mysqli->prepare("SELECT p.Nombre,p.Descripcion,p.Precio FROM final_producto p, final_productospedidos pe WHERE p.idProducto=pe.idProducto and pe.idPedido=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado["Nombre"], $resultado["Descripcion"], $resultado["Precio"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $i          = 0;
    $resultados = "";
    while ($sentencia->fetch())
      {
        //Si copiamos el array de golpe se pasa por referencia
        $resultados['productos'][$i]["Nombre"]      = $resultado["Nombre"];
        $resultados['productos'][$i]["Descripcion"] = $resultado["Descripcion"];
        $resultados['productos'][$i]["Precio"]      = $resultado["Precio"];
        $i                                          = $i + 1;
      }
    //Ahora sacamos la info del pedido
    if (!($sentencia1 = $mysqli->prepare("SELECT fecha,estado FROM final_pedido WHERE idPedido=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia1->bind_param("i", $id))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia1->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia1->bind_result($infoPedido["fecha"], $infoPedido["estado"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia1->fetch();
    $resultados['infoPedido']['estado'] = $infoPedido["estado"];
    $resultados['infoPedido']['fecha']  = $infoPedido["fecha"];
    mysqli_close($mysqli);
    return $resultados;
  }
function mlistadoproductos()
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if (!$mysqli)
      {
        die("Conexion fallida:" . mysqli_connect_error);
      }
    $resultado = mysqli_query($mysqli, "select p.nombre,p.precio,p.idProducto, pi.Imagen from final_producto p, final_productosimagenes pi where p.idProducto=pi.idProducto and principal=1");
    if (!$resultado)
      {
        echo "Error de BD, no se pudo consultar la base de datos\n";

        exit;
      }
    mysqli_close($mysqli);
    return $resultado;

  }
function mcomprobarregistro($formulario)
  {
    //$email,$contrasena,$contrasena1,$nombre,$apellido1,$apellido2,$sexo,$comunidad,$provincia,$poblacion,$direccion,$codpos){
    foreach ($formulario as $campo)
      {
        if ($campo == null)
          {
            return ("Rellene todos los campos del registro.");
          }
      }
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if (!$mysqli)
      {
        die("Conexion fallida:" . mysqli_connect_error);
      }
    if (!($sentencia = $mysqli->prepare("SELECT idUsuario FROM final_usuarios WHERE final_email=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia->bind_param("s", $formulario["email"]))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if ($sentencia->fetch())
      {
        return ("El email ya existe.");
      }
    else if (!filter_var($formulario["email"], FILTER_VALIDATE_EMAIL))
      {
        return ("Esta direcci&oacuten de correo no es v&aacutelida.");
      }
    else if ($formulario["contrasena"] != $formulario["contrasena1"])
      {
        return ("Las contrase&ntildeas no coinciden.");
      }
    else
      {
        $servidor = "127.0.0.1";
        $usuario  = "siw06";
        $password = "asahwaeche";
        $dbname   = "db_siw06";
        $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
        if (!$mysqli)
          {
            die("Conexion fallida:" . mysqli_connect_error);
          }
        //Ahora sacamos la info del pedido
        if (!($sentencia1 = $mysqli->prepare("INSERT INTO `usuarios`(`Nombre`, `apellido1`, `apellido2`, `email`, `password`, `direccion`,
           `CP`, `Sexo`, `Comunidad`, `Provincia`, `Municipio`) VALUES (?,?,?,?,?,?,?,?,?,?,?)")))
          {
            ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
          }
        print_r($formulario);
        //Preparamos la consulta
        if (!$sentencia1->bind_param("ssssssiiiii", $formulario["nombre"], $formulario["apellido1"], $formulario["apellido2"], $formulario["email"], $formulario["contrasena"], $formulario["direccion"], $formulario["codpos"], $formulario["sexo"], $formulario["comunidad"], $formulario["provincia"], $formulario["poblacion"]))
          {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        if (!$sentencia1->execute())
          {
            echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        $sentencia1->close();
        mysqli_close($mysqli);
        return "OK";
      }
  }
function mcomprobarlogin($user, $contrasena)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if (!$mysqli)
      {
        die("Conexion fallida:" . mysqli_connect_error);
      }
    if (!($sentencia = $mysqli->prepare("SELECT count(email) from final_usuarios WHERE email=? and password=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia->bind_param("ss", $user, $contrasena))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $login = ($sentencia->fetch()) > 0;
    mysqli_close($mysqli);
    return $login;

    mysqli_close($mysqli);
    return $login;
  }
function mgetinfo($idUser)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if (!$mysqli)
      {
        die("Conexion fallida:" . mysqli_connect_error);
      }
    if (!($sentencia = $mysqli->prepare("SELECT idUsuario,Nombre,apellido1,apellido2,email,password,direccion,CP,sexo,Comunidad,Provincia,Municipio from final_usuarios WHERE idUsuario=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia->bind_param("i", $idUser))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($formulario["id"], $formulario["nombre"], $formulario["apellido1"], $formulario["apellido2"], $formulario["email"], $formulario["contrasena"], $formulario["direccion"], $formulario["codpos"], $formulario["sexo"], $formulario["comunidad"], $formulario["provincia"], $formulario["poblacion"]))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    $sentencia->fetch();
    mysqli_close($mysqli);
    return $formulario;
  }

function msubirproducto($formulario)
  {

    $rand = rand(0, 10000);
    $im   = "imagenes/" . $rand . "_" . $formulario["nombreimagen"];

    if (move_uploaded_file($formulario["temporal"], $im))
      {
        list($ancho, $alto) = getimagesize($im);
        $ratio = ($ancho / $alto);

        switch ($formulario["tipo"])
        {
            case "image/jpeg":
                header('Content-Type: image/jpeg');
                $imagen = imagecreatefromjpeg($im);
                $peq    = imagecreatetruecolor(100, 100 / $ratio);
                $imp    = str_replace(".jpg", "_peq.jpg", $im);
                if ($imp == $im)
                    $imp = str_replace(".JPG", "_peq.jpg", $im);
                imagecopyresized($peq, $imagen, 0, 0, 0, 0, 100, 100 / $ratio, $ancho, $alto);
                imagejpeg($peq, $imp);

                $med = imagecreatetruecolor(200, 200 / $ratio);
                $imm = str_replace(".jpg", "_med.jpg", $im);
                if ($imm == $im)
                    $imm = str_replace(".JPG", "_med.jpg", $im);
                imagecopyresized($med, $imagen, 0, 0, 0, 0, 200, 200 / $ratio, $ancho, $alto);
                imagejpeg($med, $imm);

                $gran = imagecreatetruecolor(300, 300 / $ratio);
                $imgr = str_replace(".jpg", "_grande.jpg", $im);
                if ($imgr == $im)
                    $imgr = str_replace(".JPG", "_grande.jpg", $im);
                imagecopyresized($gran, $imagen, 0, 0, 0, 0, 300, 300 / $ratio, $ancho, $alto);
                imagejpeg($gran, $imgr);
                break;
            case "image/png":
                header('Content-Type: image/png');
                $peq    = imagecreatetruecolor(100, 100 / $ratio);
                $imagen = imagecreatefrompng($im);
                $imp    = str_replace(".png", "_peq.png", $im);
                if ($imp == $im)
                    $imp = str_replace(".PNG", "_peq.png", $im);
                imagecopyresized($peq, $imagen, 0, 0, 0, 0, 100, 100 / $ratio, $ancho, $alto);
                imagepng($peq, $imp);

                $med = imagecreatetruecolor(250, 250 / $ratio);
                $imm = str_replace(".png", "_med.png", $im);
                if ($imm == $im)
                    $imm = str_replace(".PNG", "_med.png", $im);
                imagecopyresized($med, $imagen, 0, 0, 0, 0, 250, 250 / $ratio, $ancho, $alto);
                imagepng($med, $imm);

                $gran = imagecreatetruecolor(500, 500 / $ratio);
                $imgr = str_replace(".png", "_grande.png", $im);
                if ($imgr == $im)
                    $imgr = str_replace(".PNG", "_grande.png", $im);
                imagecopyresized($gran, $imagen, 0, 0, 0, 0, 500, 500 / $ratio, $ancho, $alto);
                imagepng($gran, $imgr);
                break;
        }
        unlink($im);

        $servidor = "127.0.0.1";
        $usuario  = "siw06";
        $password = "asahwaeche";
        $dbname   = "db_siw06";
        $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
        if (!$mysqli)
          {
            die("Conexion fallida:" . mysqli_connect_error);
          }

        //Preparamos la consulta

        if (!($sentencia = $mysqli->prepare('SELECT principal FROM final_producto natural join final_productosimagenes WHERE Nombre=? and Precio=? and Descripcion=? and Stock=?')))
          {
            ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
          }
        if (!$sentencia->bind_param("sdsi", $formulario["nombre"], $formulario["precio"], $formulario["descripcion"], $formulario["stock"]))
          {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        if (!$sentencia->execute())
          {
            echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        if (!$sentencia->bind_result($resultado))
          {
            echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
          }
        $sentencia->fetch;
        $principal = ($resultado == 1);
        mysqli_close($mysqli);

        $mysqli = mysqli_connect($servidor, $usuario, $password, $dbname);
        if (!$mysqli)
          {
            die("Conexion fallida:" . mysqli_connect_error);
          }
        if (!$principal)
          {
            if (!($sentencia = $mysqli->prepare('INSERT INTO producto (Nombre,Precio,Descripcion,Stock) VALUES (?, ?, ?,?)')))
              {
                ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
              }
            if (!$sentencia->bind_param("sdsi", $formulario["nombre"], $formulario["precio"], $formulario["descripcion"], $formulario["stock"]))
              {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
              }
            if (!$sentencia->execute())
              {
                echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
              }


            if (!($sentencia = $mysqli->prepare('INSERT INTO productosimagenes (IdProducto,Imagen,ImagenMed,ImagenPeq,principal)VALUES ((select IdProducto from final_producto where nombre=? and precio=? and descripcion=? and stock=?),?,?,?,0)')))
              {
                ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
              }
            if (!$sentencia->bind_param("sdsisss", $formulario["nombre"], $formulario["precio"], $formulario["descripcion"], $formulario["stock"], $imgr, $imm, $imp))
              {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
              }
            if (!$sentencia->execute())
              {
                echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
              }
          }
        else
          {
            if (!($sentencia = $mysqli->prepare('INSERT INTO productosimagenes (IdProducto,Imagen,ImagenMed,ImagenPeq,principal)VALUES ((select IdProducto from producto where nombre=? and precio=? and descripcion=? and stock=?),?,?,?,0)')))
              {
                ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
              }
            if (!$sentencia->bind_param("sdsisss", $formulario["nombre"], $formulario["precio"], $formulario["descripcion"], $formulario["stock"], $imgr, $imm, $imp))
              {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
              }
            if (!$sentencia->execute())
              {
                echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
              }
          }
        mysqli_close($mysqli);

      }
  }

function misadmin($email)
  {
    $servidor = "127.0.0.1";
    $usuario  = "siw06";
    $password = "asahwaeche";
    $dbname   = "db_siw06";
    $mysqli   = mysqli_connect($servidor, $usuario, $password, $dbname);
    if (!$mysqli)
      {
        die("Conexion fallida:" . mysqli_connect_error);
      }
    if (!($sentencia = $mysqli->prepare("SELECT Admin from final_usuarios WHERE idUsuario=?")))
      {
        ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    //Preparamos la consulta
    if (!$sentencia->bind_param("s", $email))
      {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->execute())
      {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
      }
    if (!$sentencia->bind_result($resultado))
      {
        echo "Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
      }

    $sentencia->fetch();
    return $resultado == 1;
  }

?>
