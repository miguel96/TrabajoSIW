<?php

	$servidor ="127.0.0.1";
	$usuario="siw06";
	$password="asahwaeche";
	$dbname="db_siw06";
	//Conectamos
	$con = mysqli_connect($servidor,$usuario,$password,$dbname);

	if ($con->connect_errno) {
		echo -1;
	} else {
		$resultados=array(array(),array());
		$consulta = "select * from comunidades order by comunidad";
		$i=0;
		if ($resultado = $con->query($consulta)) {
			while ($datos = $resultado->fetch_assoc()) {
				//TODO cambiar codificacion, ahora mismo fallan ñs y acentos
				$resultados[$i]["id"]= $datos["id"];
				$resultados[$i]["comunidad"]=utf8_encode($datos["comunidad"]);
				$i+=1;
		//			$resultados[$i]["comunidad"]=$datos["comunidad"];
			}
		} else {
			echo -2;
		}
	}
	echo json_encode($resultados);
?>
