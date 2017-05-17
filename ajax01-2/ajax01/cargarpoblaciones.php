<?php

	$cadena = "Poblaciones <select id='poblacion'><option value=0>Elige una poblacion</option>";

	$con = new mysqli("localhost", "root", "", "poblacionesajax");

	if ($con->connect_errno) {
		echo -1;
	} else {
		$idprovincia = $_GET["idprovincia"];

		$consulta = "select * from poblaciones where idprovincia = $idprovincia order by poblacion";

		if ($resultado = $con->query($consulta)) {
			while ($datos = $resultado->fetch_assoc()) {
				$cadena .= "<option value=" . $datos["IDPoblacion"] . ">" . $datos["Poblacion"] . "</option>";
			}
		} else {
			echo -2;
		}
	}

	$cadena .= "</select>";

	if ($idprovincia == 0) {
		echo  "";
	} else {
		echo $cadena;	
	}

	

?>