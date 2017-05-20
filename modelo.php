<?php
function mmostrarproducto(){
    $id = $_GET["id"];
    //Codigo para coger info de bbdd
    $servidor ="127.0.0.1";
    $usuario="siw06";
    $password="asahwaeche";
    $dbname="db_siw06";
    //Conectamos
    $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
    if($mysqli->connect_errno){
      die("Conexion fallida:" .$mysqli->connect_error.".\n");
    }
    //Preparamos la consulta
    if (!($sentencia = $mysqli->prepare('Select p.IdProducto,p.Nombre,p.Precio,p.Descripcion,p.Stock,pi.Imagen FROM producto p, productosimagenes pi WHERE p.IdProducto=? and pi.Idproducto=p.idProducto'))){
      ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$sentencia->bind_param("i",$id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if(!$sentencia->bind_result($resultado["IdProducto"],$resultado["Nombre"],$resultado["Precio"],$resultado["Descripcion"],$resultado["Stock"],$resultado["Imagen"])){
      echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    $sentencia->fetch();
    $sentencia->close();
    //Devolvemos el producto
    mysqli_close($mysqli);
    return $resultado;
}

function mmostrarreviewsproducto(){
  $id = $_GET["id"];
  //Codigo para coger info de bbdd
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT Titulo,Valoracion,Comentario,Imagen,Nombre,apellido1,apellido2 FROM reviews R, usuarios U WHERE IdProducto=? AND R.IdUsuario=U.IdUsuario "))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$sentencia->bind_param("i",$id)) {
  echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
  echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado["Titulo"],$resultado["Valoracion"],$resultado["Comentario"],$resultado["Imagen"],$resultado["Nombre"],$resultado["apellido1"],$resultado["apellido2"])){
    echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $i=0;
  $resultados="";
  while($sentencia->fetch()){
      $resultados[$i]=$resultado;
      $i=$i+1;
  }
  $sentencia->close();
  //Devolvemos el producto
  mysqli_close($mysqli);
  return $resultados;
}

function mmostrarreviewsusuario($id){
  //Codigo para coger info de bbdd
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT Titulo,Valoracion,Comentario,Imagen,Nombre FROM reviews R, producto P WHERE IdUsuario=? AND R.IdProducto=P.IdProducto ORDER BY R.Fecha"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$sentencia->bind_param("i",$id)) {
  echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
  echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado["Titulo"],$resultado["Valoracion"],$resultado["Comentario"],$resultado["Imagen"],$resultado["Nombre"])){
    echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $i=0;
  $resultados="";
  while($sentencia->fetch()){
      $resultados[$i]=$resultado;
      $i=$i+1;
  }
  $sentencia->close();
  mysqli_close($mysqli);
  return $resultados;
}

function mmostrarpedidosusuario($id){
//Buscamos la id en la sesion o en la url, si no no mostramos nada
  //Codigo para coger info de bbdd
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT estado,fecha,SUM(pr.Precio) precio ,p.idPedido FROM pedido p,productospedidos r,producto pr WHERE p.idPedido=r.idPedido AND pr.idProducto=r.idProducto AND p.idUsuario=? GROUP BY fecha,estado,p.idPedido ORDER BY fecha"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$sentencia->bind_param("i",$id)) {
  echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
  echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado["estado"],$resultado["fecha"],$resultado["precio"],$resultado["idPedido"])){
    echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $i=0;
  $resultados="";
  while($sentencia->fetch()){
      //Si copiamos el array de golpe se pasa por referencia al ser una subconsulta
      $resultados[$i]["estado"]=$resultado["estado"];
      $resultados[$i]["fecha"]=$resultado["fecha"];
      $resultados[$i]["precio"]=$resultado["precio"];
      $resultados[$i]["idPedido"]=$resultado["idPedido"];
      $i=$i+1;
  }

  mysqli_close($mysqli);
  return $resultados;
}

function mmostrarpedido(){
  if(isset($_COOKIE['Carrito'])) {
    $productos=unserialize($_COOKIE['Carrito']);
  } else {
    $productos=array();
  }
  //Codigo para coger info de bbdd
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT Nombre,Descripcion,Precio,Stock FROM producto WHERE idProducto=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  $i=0;
  $resultados="";
  foreach ($productos as $id) {
        $i=$i+1;
        if (!$sentencia->bind_param("i",$id)) {
        echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        if (!$sentencia->execute()) {
        echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        if(!$sentencia->bind_result($resultados[$i]["Nombre"],$resultados[$i]["Descripcion"],$resultados[$i]["Precio"],$resultados[$i]["Stock"])){
          echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        $sentencia->fetch();
  }
  mysqli_close($mysqli);
  return $resultados;
}

function mgetUsuario($email){
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT idUsuario FROM usuarios WHERE email=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  $resultados="";
  if (!$sentencia->bind_param("s",$email)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado)){
      echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
    $sentencia->fetch();
  mysqli_close($mysqli);
  return $resultado;
}
function mestalogin($id,$contrasena){
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT count(idUsuario) FROM usuarios WHERE idUsuario=? and password=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  $resultado="";
  if (!$sentencia->bind_param("is",$id,$contrasena)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado)){
      echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
    $sentencia->fetch();
  mysqli_close($mysqli);
  return $resultado==1;
}
//Codigo Ruben

function mpedidopertenece($idPedido,$idUsuario){
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT count(idPedido) FROM pedido WHERE idPedido=? and idUsuario=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  $resultado="";
  if (!$sentencia->bind_param("ii",$idPedido,$idUsuario)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado)){
      echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $sentencia->fetch();
  mysqli_close($mysqli);
  return $resultado>0;
}

function mmostrarproductospedidoid($id){
  //Codigo para coger info de bbdd
  $servidor="127.0.0.1";
  $usuario="siw06";
  $password="asahwaeche";
  $dbname="db_siw06";
  //Conectamos
  $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
  if($mysqli->connect_errno){
    die("Conexion fallida:" .$mysqli->connect_error.".\n");
  }
  if (!($sentencia = $mysqli->prepare("SELECT p.Nombre,p.Descripcion,p.Precio FROM producto p, productospedidos pe WHERE p.idProducto=pe.idProducto and pe.idPedido=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  if (!$sentencia->bind_param("i",$id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia->bind_result($resultado["Nombre"],$resultado["Descripcion"],$resultado["Precio"])){
    echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $i=0;
  $resultados="";
  while($sentencia->fetch()){
      //Si copiamos el array de golpe se pasa por referencia al ser una subconsulta
      $resultados['productos'][$i]["Nombre"]=$resultado["Nombre"];
      $resultados['productos'][$i]["Descripcion"]=$resultado["Descripcion"];
      $resultados['productos'][$i]["Precio"]=$resultado["Precio"];
      $i=$i+1;
  }
  //Ahora sacamos la info del pedido
  if (!($sentencia1 = $mysqli->prepare("SELECT fecha,estado FROM pedido WHERE idPedido=?"))){
    ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  //Preparamos la consulta
  if (!$sentencia1->bind_param("i",$id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if (!$sentencia1->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  if(!$sentencia1->bind_result($infoPedido["fecha"],$infoPedido["estado"])){
    echo"Fallo el resultado: (" . $sentencia->errno . ") " . $sentencia->error;
  }
  $sentencia1->fetch();
  $resultados['infoPedido']['estado']=$infoPedido["estado"];
  $resultados['infoPedido']['fecha']=$infoPedido["fecha"];
  mysqli_close($mysqli);
  return $resultados;
}
	function mlistadoproductos(){
    $servidor="127.0.0.1";
    $usuario="siw06";
    $password="asahwaeche";
    $dbname="db_siw06";
 		$mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
		if(!$mysqli){
            die("Conexion fallida:" .mysqli_connect_error);
		}
		$resultado = mysqli_query($mysqli, "select p.nombre,p.precio,p.idProducto, pi.Imagen from producto p, productosimagenes pi where p.idProducto=pi.idProducto");
        	if (!$resultado) {
            	echo "Error de BD, no se pudo consultar la base de datos\n";

            	exit;
        	}
    mysqli_close($mysqli);
		return $resultado;

	}

	function mcomprobarregistro($formulario){
    //$email,$contrasena,$contrasena1,$nombre,$apellido1,$apellido2,$sexo,$comunidad,$provincia,$poblacion,$direccion,$codpos){
    foreach($formulario as $campo){
      if($campo==null){
        return("Rellene todos los campos del registro.");
      }
    }
    echo $formulario["email"];
    $servidor="127.0.0.1";
    $usuario="siw06";
    $password="asahwaeche";
    $dbname="db_siw06";
    $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
    if(!$mysqli){
      die("Conexion fallida:" .mysqli_connect_error);
		}
    if (!($sentencia = $mysqli->prepare("SELECT idUsuario FROM usuarios WHERE email=?"))){
      ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    //Preparamos la consulta
    if (!$sentencia->bind_param("s",$formulario["email"])) {
      echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if (!$sentencia->execute()) {
      echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if($sentencia->fetch()){
        return("El email ya existe.");
    }
    else if (!filter_var($formulario["email"], FILTER_VALIDATE_EMAIL)) {
        return("Esta dirección de correo no es válida.");
    }else if($formulario["contrasena"]!=$formulario["contrasena1"]){
        return("Las contraseñas no coinciden.");
    }
    else{
        //TODO insertar al usuario en la BBDD

        $servidor="127.0.0.1";
        $usuario="siw06";
        $password="asahwaeche";
        $dbname="db_siw06";
        $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
        if(!$mysqli){
          die("Conexion fallida:" .mysqli_connect_error);
    		}
        //Ahora sacamos la info del pedido
        if (!($sentencia1 = $mysqli->prepare("INSERT INTO `usuarios`(`idUsuario`, `Nombre`, `apellido1`, `apellido2`, `email`, `password`, `direccion`,
           `CP`, `Sexo`, `Comunidad`, `Provincia`, `Municipio`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"))){
          ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $formulario["nombre"]="'".$formulario["nombre"]."'";
        $formulario["apellido1"]="'".$formulario["apellido1"]."'";
        $formulario["apellido2"]="'".$formulario["apellido2"]."'";
        $formulario["email"]="'".$formulario["email"]."'";
        $formulario["contrasena"]="'".$formulario["contrasena"]."'";
        $formulario["direccion"]="'".$formulario["direccion"]."'";

        print_r($formulario);
        //Preparamos la consulta
        if (!$sentencia1->bind_param("issssssiiiii",$idUsuario=4,$formulario["nombre"],$formulario["apellido1"],$formulario["apellido2"],
        $formulario["email"],$formulario["contrasena"],$formulario["direccion"],$formulario["codpos"],$formulario["sexo"],$formulario["comunidad"],
        $formulario["provincia"],$formulario["poblacion"])){
          echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        if (!$sentencia1->execute()) {
          echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        $sentencia->close();
        mysqli_close($mysqli);
        return "OK";
      }
  }
	function mcomprobarlogin($user,$contrasena){
    $servidor="127.0.0.1";
    $usuario="siw06";
    $password="asahwaeche";
    $dbname="db_siw06";
		$mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
		if(!$mysqli){
            die("Conexion fallida:" .mysqli_connect_error);
		}
		$consulta = mysqli_query($mysqli,"select email,password from usuarios");
		$login = false;
		while ($fila = mysqli_fetch_assoc($consulta)){
			if ($user==$fila["email"] and $contrasena==$fila["password"]){
				$login = true;
				break;
			}
		}
    mysqli_close($mysqli);
		return $login;
	}

  function msubirimagen($nombre,$img){

		$rand = rand(0,10000);
		$im = "imagenes/" .$rand."_". $nombre;
		header('Content-Type: image/jpeg');
		if (move_uploaded_file($img,$im)){
			list($ancho,$alto) = getimagesize($im);
			$ratio = ($ancho/$alto);
			$peq = imagecreatetruecolor(100,100*$ratio);
			$imagen = imagecreatefromjpeg($im);
			$imp = str_replace(".jpg","_peq.jpg",$im );
			imagecopyresized($peq,$imagen,0,0,0,0,100,100*$ratio,$ancho,$alto);
			imagejpeg($peq,$imp);

			$servidor="127.0.0.1";
			$usuario="siw06";
			$password="asahwaeche";
			$dbname="db_siw06";
			$mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
			if(!$mysqli){
				die("Conexion fallida:" .mysqli_connect_error);
			}
			//Falta subir a la base de datos, se mete con la creacion de cada review.

		}
	}
?>
