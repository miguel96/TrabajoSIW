<?php
	include ("modelo.php");
	include ("vista.php");

	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} else {
		if (isset($_POST["accion"])) {
			$accion = $_POST["accion"];
		}
		else $accion="";
	}

	if (isset($_GET["id"])) {
		$id = $_GET["id"];
	} else {
		if (isset($_POST["id"])) {
			$id = $_POST["id"];
		} else {
			$id = 1;
		}
	}
	switch ($accion) {
		case "producto":
			vmostrarproducto(mmostrarproducto(),mmostrarreviewsproducto());
			break;
		case "misvaloraciones":
		session_start();
		$user=$_SESSION["Usuario"];
		if(!filter_var($user,FILTER_VALIDATE_INT)===false){
			$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
			if(mestalogin($user,$password))
				vmostrarmisvaloraciones(mmostrarreviewsusuario($user));
			else{
				header('Location:controlador.php?accion=login');
			}
		}
		else{
			header('Location:controlador.php?accion=login');
		}
			break;
		case "mispedidos":
		  session_start();
			$user=$_SESSION["Usuario"];
			if(!filter_var($user,FILTER_VALIDATE_INT)===false){
				$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
				if(mestalogin($user,$password))
					vmostrarmispedidos(mmostrarpedidosusuario($user));
			  else{
						header('Location:controlador.php?accion=login');
				}
		 	}
			else {
				header('Location:controlador.php?accion=login');
			}
			break;
		case "pedido":
				vmostrarpedido(mmostrarpedido());
				break;
		case "addCarrito":
			//AddCookie
			if(!isset($_COOKIE["Carrito"])) {
				$datos=array();
				array_push($datos,$id);
				setcookie("Carrito",serialize($datos),time()+3600);
			}
			else {
				$datos=unserialize($_COOKIE["Carrito"]);
				array_push($datos,$id);
			}
			setcookie("Carrito",serialize($datos),time()+3600);
			//Redirige a pag pedido
			header('Location:controlador.php?accion=pedido');
		  break;
			case "compruebalogin":
			if(!isset($_SESSION)){
				session_start();
			}
			if (isset($_POST["Email"])) {
				$email = $_POST["Email"];
			} else if (isset($_SESSION["Email"])) {
					$email = $_SESSION["Email"];
				}
				else {
					$email="";
				}
			if (isset($_POST["contrasena"])) {
				$contrasena = $_POST["contrasena"];
			} else if (isset($_SESSION["contrasena"])) {
					$contrasena = $_SESSION["contrasena"];
				}
				else {
					$contrasena="";
				}
				echo $email;
				if (mcomprobarlogin($email,$contrasena)){
						//Iniciamos la Sesion
						if(!isset($_SESSION)){
							session_start();
						}
						$_SESSION["Usuario"]=mgetUsuario($email);
						$_SESSION["Contrasena"]=$contrasena;
						header('Location:controlador.php');

					}
				else header('Location:controlador.php?accion=login');
			break;
			case "principal":
				vmostrarlistadoproductos(mcontarproductos(),mlistadoproductos());
				break;
			case "login":
				vmostrarlogin();
				break;
			case "logout":
				if(!isset($_SESSION))
					session_start();
				session_destroy();
				header('Location:controlador.php');
				break;
			case "pedidoapdf":
				if(!isset($_SESSION))
					session_start();
				$user=$_SESSION["Usuario"];
				if(!filter_var($user,FILTER_VALIDATE_INT)===false){
					$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
					if(mestalogin($user,$password)&mpedidopertenece($id,$user)){
							vpedidotoPDF(mmostrarproductospedidoid($id));
						}
					else{
							header('Location:controlador.php?accion=login');
					}
				}
				else {
					header('Location:controlador.php?accion=login');
				}
				break;
		  case "subir":
				msubirimagen($_FILES["file"]["name"],$_FILES["file"]["tmp_name"],$_FILES["file"]["type"]);
				break;
			case "registro":
				vmostrarregistro();
				break;
			case "compruebaregistro":
				$formulario=array("email"=>$_POST["email"],"contrasena"=>$_POST["contrasena"],"contrasena1"=>$_POST["contrasena1"],"nombre"=>$_POST["nombre"],
				"apellido1"=>$_POST["apellido1"],"apellido2"=>$_POST["apellido2"],"sexo"=>$_POST["sexo"],"comunidad"=>$_POST["comunidad"],
				"provincia"=>$_POST["provincia"],"poblacion"=>$_POST["poblacion"],"direccion"=>$_POST["direccion"],"codpos"=>$_POST["codpos"]);
				$resultado=mcomprobarregistro($formulario);
				if($resultado=="OK"){//Registro correcto
					if(!isset($_SESSION)){
						session_start();
					}
					$_SESSION["Email"]=$formulario["email"];
					$_SESSION["contrasena"]=$formulario["contrasena"];
					header('Location:controlador.php?accion=compruebalogin');
				}
				else{
					vmostrarregistro2($resultado,$formulario);
				}
			break;
		case "micuenta":
			if(!isset($_SESSION))
				session_start();
			$user=$_SESSION["Usuario"];
			if(!filter_var($user,FILTER_VALIDATE_INT)===false){
				$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
				if(mestalogin($user,$password)){
					vmicuenta(mgetinfo($user));
				}
				else{
						header('Location:controlador.php?accion=login');
				}
			}
			else {
				header('Location:controlador.php?accion=login');
			}
			break;
		case "actualizacuenta":
				//TODO actualiza la informacion de la cuenta
			break;
			default:
				vmostrarlistadoproductos(mlistadoproductos());
				break;
		}
		function cislogged(){
			if(!isset($_SESSION))
				session_start();
			if(isset($_SESSION["Usuario"])){
				$user=$_SESSION["Usuario"];
				if(!filter_var($user,FILTER_VALIDATE_INT)===false){
					$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
					return mestalogin($user,$password);
				}
			}
			return false;
		}
?>
