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
    if (!($sentencia = $mysqli->prepare('Select IdProducto,Nombre,Precio,Descripcion,Stock FROM producto  WHERE IdProducto=?'))){
      ECHO "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$sentencia->bind_param("i",$id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if (!$sentencia->execute()) {
    echo "Falló la ejecución: (" . $sentencia->errno . ") " . $sentencia->error;
    }
    if(!$sentencia->bind_result($resultado["IdProducto"],$resultado["Nombre"],$resultado["Precio"],$resultado["Descripcion"],$resultado["Stock"])){
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
  if (!($sentencia = $mysqli->prepare("SELECT estado,fecha,SUM(pr.Precio) precio ,p.idPedido FROM pedido p,productospedidos r,producto pr WHERE p.idPedido=r.idPedido AND pr.idProducto=r.idProducto AND p.idUsuario=? GROUP BY fecha,estado ORDER BY fecha"))){
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
	function mcontarproductos(){
    $servidor="127.0.0.1";
    $usuario="siw06";
    $password="asahwaeche";
    $dbname="db_siw06";
		$mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
		if(!$mysqli){
            die("Conexion fallida:" .mysqli_connect_error);
		}
		$resultado = mysqli_query($mysqli, "select count(IdProducto) contar from producto");
		if (!$resultado) {
            echo "Error de BD, no se pudo consultar la base de datos\n";

            exit;
        }
		while ($fila = mysqli_fetch_assoc($resultado)) {
	   		$contar = $fila['contar'];
        }
    mysqli_close($mysqli);
		return $contar;
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
		$resultado = mysqli_query($mysqli, "select nombre,precio,idProducto from producto");
        	if (!$resultado) {
            	echo "Error de BD, no se pudo consultar la base de datos\n";

            	exit;
        	}
    mysqli_close($mysqli);
		return $resultado;

	}

	function mcomprobarregistro($email,$contrasena,$contrasena1,$nombre,$apellidos,$direccion,$comunidad,$provincia,$localidad,$codpos,$sexo){

            if($email==null or $contrasena==null or $contrasena1==null or $nombre==null or $apellidos==null or $direccion==null or $comunidad==null or $provincia==null or $localidad==null or $codpos==null or $sexo==null){
                vmostrarregistro("Rellene todos los campos del registro.");
            }
            else{
            $servidor="dbserver";
            $usuario="siw06";
            $password="asahwaeche";
            $dbname="db_siw06";
            $mysqli = mysqli_connect($servidor,$usuario,$password,$dbname);
            if(!$mysqli){
            die("Conexion fallida:" .mysqli_connect_error);
		}
            $resultado = mysqli_query($mysqli, "select email from usuarios where email='$email'");
            $fila = mysqli_fetch_assoc($resultado);
            if (!$resultado) {
            	echo "Error de BD, no se pudo consultar la base de datos\n";
            }else if($fila['email'] == $email){
                    vmostrarregistro("El email ya existe.");
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                vmostrarregistro("Esta dirección de correo no es válida.");
            }else if($contrasena!=$contrasena1){
                vmostrarregistro("Las contraseñas no coinciden.");
            }
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
		$im = "imagenes/" .rand(0,10000)."_". $nombre;
		if (move_uploaded_file($img,$im)){
                        imagescale($im,1000,-1,IMG_NEAREST_NEIGHBOUR);
			$servidor="dbserver";
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
