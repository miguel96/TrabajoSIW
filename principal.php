<?php
        
	$dblink = mysqli_connect("dbserver", "siw06", "asahwaeche", "db_siw06");
	if(!$dblink){
            die("Conexion fallida:" .mysqli_connect_error);
	}
	
	$resultado = mysqli_query($dblink, "select count(IdProducto) contar from Producto");
	if (!$resultado) {
            echo "Error de BD, no se pudo consultar la base de datos\n";
            
            exit;
        }   
	
	while ($fila = mysqli_fetch_assoc($resultado)) {
	   $productos = $fila['contar'];
        } 
        
    
	$cadena = file_get_contents("principal.html");

	$trozos = explode("##productos##", $cadena);
			
				
	$cuerpo = "";
	
	for ($i=0; $i < $productos; $i++) {
		$aux = $trozos[1];
		$resultado = mysqli_query($dblink, "select Nombre,Precio from Producto where idproducto=$i+1");
        	if (!$resultado) {
            	echo "Error de BD, no se pudo consultar la base de datos\n";
            
                exit;
            }

		while ($fila = mysqli_fetch_assoc($resultado)) {
        	$nombre = $fila['Nombre'];
            $precio = $fila['Precio'];
        }
		$aux = str_replace("##foto##", "'imagenes/chrome.png'", $aux);
		$aux = str_replace("##nombre##", "$nombre", $aux);
		$aux = str_replace("##precio##", "$precio €", $aux);

		$cuerpo .= $aux;
	}

	echo $trozos[0] . $cuerpo . $trozos[2];


?>
