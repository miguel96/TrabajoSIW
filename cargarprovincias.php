<?php
header("Content-Type: text/html; charset=iso-8859-1");

	$servidor ="dbserver";
	$usuario="siw06";
	$password="asahwaeche";
	$dbname="db_siw06";
	//Conectamos
	$con = mysqli_connect($servidor,$usuario,$password,$dbname);

	if ($con->connect_errno) {
		echo -1;
	} else {
		$resultados=array(array());
		$comunidad_id = $_GET["idcomunidad"];
		$consulta = "select * from provincias where comunidad_id = $comunidad_id order by provincia";
		$i=0;
		if ($resultado = $con->query($consulta)) {
			while ($datos = $resultado->fetch_assoc()) {
					$resultados[$i]["id"]= $datos["id"];
					$resultados[$i]["provincia"]=utf8_encode($datos["provincia"]);
					$i+=1;
			}
		} else {
			echo -2;
		}
	}

	if ($comunidad_id == 0) {
		echo "";
	} else {
		echo json_encode($resultados);
	}


?>
