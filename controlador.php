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

                case "anadirproducto":
                    vanadirproducto();
                    break;
		case "subir":
                    $formulario = array("nombreimagen"=>$_FILES["file"]["name"],"temporal"=>$_FILES["file"]["tmp_name"],"tipo"=>$_FILES["file"]["type"],"nombre"=>$_POST["nombre"],"precio"=>$_POST["precio"],"descripcion"=>$_POST["descripcion"],"stock"=>$_POST["stock"]);

                    msubirproducto($formulario);
                    break;

		case "pedirimagen":
                    $formulario=array("nombre"=>$_POST["nombre"],"precio"=>$_POST["precio"],"descripcion"=>$_POST["descripcion"],"stock"=>$_POST["stock"]);
                    vpedirimagen($formulario);
                    break;
		case "producto":
			vmostrarproducto(mmostrarproducto(),mmostrarreviewsproducto());
			break;
		case "cuenta":
			if(!isset($_SESSION))
				session_start();
			if(isset($_SESSION["Usuario"])){
				vmostrarcuenta(mgetinfo($_SESSION["Usuario"]));
			}
			break;
		case "misvaloraciones":
		session_start();
		$user=$_SESSION["Usuario"];
		if(!filter_var($user,FILTER_VALIDATE_INT)===false){
			$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
			if(mestalogin($user,$password) and misadmin($user)){
				vmostrarmisvaloracionesadmin(mmostrarreviewsadmin());
			}
			else if(mestalogin($user,$password))
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


				if(mestalogin($user,$password)){
					if (misadmin($user))
						vmostrarmispedidosadmin(mmostrarpedidoadmin());
					else
					vmostrarmispedidos(mmostrarpedidosusuario($user));
				}
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
					$datos=array($id=>1);
					setcookie("Carrito",json_encode($datos),time()+3600);
				}
				else {
					$datos=json_decode($_COOKIE["Carrito"],true);
					if(isset($datos[$id])){
						$datos[$id]=$datos[$id]+1;
					}
					else{
						$datos[$id]=1;
					}
				}
				echo json_encode($datos);
				setcookie("Carrito",json_encode($datos),time()+3600);
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
			vmostrarlistadoproductos(mlistadoproductos());
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
	case "galeria":
		vgaleria(mgaleria($id),$id);
		break;
	case "comentario":
		vmostrarcomentario($id);
		break;
	case "registraComentario":
		$formulario=array("idProducto"=>$_POST["id"],"valoracion"=>$_POST["valoracion"],"comentario"=>$_POST["comentario"]);
		if(!isset($_SESSION))
			session_start();
		$formulario["idUsuario"]=$_SESSION["Usuario"];
		print_r($formulario);
		if(!filter_var($formulario["idUsuario"],FILTER_VALIDATE_INT)===false){
			$password=filter_var($_SESSION["Contrasena"],FILTER_SANITIZE_STRING);
			if(mestalogin($formulario["idUsuario"],$password)){
				msavecomentario($formulario);
				header('Location:controlador.php?accion=producto&id='.$formulario["idProducto"]);
			}
			else{
					header('Location:controlador.php?accion=login');
			}
			}
			else {
				header('Location:controlador.php?accion=login');
		}
		break;
		case "buscar":
			if (isset($_GET["texto"])) {
				$texto = $_GET["texto"];
			} else {
				if (isset($_POST["texto"])) {
					$texto = $_POST["texto"];
				} else {
					$texto = "";
				}
			}
			mbuscar($texto);
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
		function cisadmin(){
			if(cislogged()){
				return misadmin($_SESSION["Usuario"]);
			}
			else
				return false;
		}

?>
