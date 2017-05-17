<?php

	$cadena = "Provincias <select id='provincia' onchange='cargarpoblaciones();'><option value=0>Elige una provincia</option>";

	$con = new mysqli("localhost", "root", "", "poblacionesajax");

	if ($con->connect_errno) {
		echo -1;
	} else {
		$idcomunidad = $_GET["idcomunidad"];
		$consulta = "select * from provincias where IDComunidad = $idcomunidad order by provincia";

		if ($resultado = $con->query($consulta)) {
			while ($datos = $resultado->fetch_assoc()) {
				$cadena .= "<option value=" . $datos["IDProvincia"] . ">" . $datos["Provincia"] . "</option>";
			}
		} else {
			echo -2;
		}
	}

	$cadena .= "</select>";

	if ($idcomunidad == 0) {
		echo "";
	} else {
		echo $cadena;	
	}
	

?>