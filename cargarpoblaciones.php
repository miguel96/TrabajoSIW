<?php
header("Content-Type: text/html; charset=iso-8859-1");


	$servidor ="127.0.0.1";
	$usuario="siw06";
	$password="asahwaeche";
	$dbname="db_siw06";
	//Conectamos
	$con = mysqli_connect($servidor,$usuario,$password,$dbname);

	if ($con->connect_errno) {
		echo -1;
	} else {
		$idprovincia = $_GET["idprovincia"];
		$resultados=array(array());
		$consulta = "select * from municipios where provincia_id = $idprovincia order by municipio";
		$i=0;
		if ($resultado = $con->query($consulta)) {
			while ($datos = $resultado->fetch_assoc()) {
				$resultados[$i]["id"]= $datos["id"];
				$resultados[$i]["poblacion"]=utf8_encode($datos["municipio"]);
				$i+=1;
			}
		} else {
			echo -2;
		}
	}

	if ($idprovincia == 0) {
		echo  "";
	} else {
		echo json_encode($resultados);
	}



?>
