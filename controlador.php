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
				//print_r(mmostrarpedido());
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
		    $email=$_POST["Email"];
				$contrasena=$_POST["contrasena"];
				if (mcomprobarlogin($email,$contrasena)){
						//Iniciamos la Sesion
						session_start();
						$_SESSION["Usuario"]=mgetUsuario($email);
						$_SESSION["Contrasena"]=$contrasena;
						header('Location:controlador.php');

					}
				else vmostrarlogin();
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
				msubirimagen($_FILES["file"]["name"],$_FILES["file"]["tmp_name"]);
				break;
			case "registro":
				vmostrarregistro();
				break;
			case "compruebaregistro":
			//Comprobar 2 contraseñas iguales
			//Comprobar email es un email
			//Mandar a meter a la base de datos
			mcomprobarregistro($_POST["email"],$_POST["contrasena"],$_POST["contrasena1"],$_POST["nombre"],$_POST["apellidos"],$_POST["direccion"],$_POST["comunidad"],$_POST["provincia"],$_POST["localidad"],$_POST["codpos"],$_POST["sexo"]);
			break;
				break;
			default:
				vmostrarlistadoproductos(mcontarproductos(),mlistadoproductos());
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
