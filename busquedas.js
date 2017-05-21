	function cargarcomunidades() {
		$.ajax({
			url: 'cargarcomunidades.php',
			success: function (response) {
				var infoComunidades=JSON.parse(response);
				cadena="<p>Comunidad </p> <select id='comunidad' name='comunidad' onchange='cargarprovincias();'><option value=0>Elige una comunidad</option>";

				for (comunidad in infoComunidades){
						cadena+="<option value="+infoComunidades[comunidad]["id"]+">"+infoComunidades[comunidad]["comunidad"]+"</option>";
				}
				cadena+="</select>";
				$("#capacomunidades").html(cadena);
				cadena="<p>Provincia </p><select id='provincia' name='provincia'><option value=0>Elige una provincia</option></select>";
				$("#capaprovincias").html(cadena);
				cadena="<p>Poblacion </p><select id='poblacion' name='poblacion'><option value=0>Elige una poblaci&oacuten</option></select>";
				$("#capapoblaciones").html(cadena);
			},
			error: function(response){
				alert(3);
			}
		});
	}

	function cargarprovincias() {
		if ($("#comunidad").val() == 0) {
			$("#capapoblaciones").html("");
		}
		$.ajax({
			url: 'cargarprovincias.php?idcomunidad=' + $("#comunidad").val(),
			success: function (response) {
				var infoProvincias=JSON.parse(response);
				cadena="<p>Provincia </p><select id='provincia' name='provincia' onchange='cargarpoblaciones();'><option value=0>Elige una provincia</option>";

				for (provincia in infoProvincias){
						cadena+="<option value="+infoProvincias[provincia]["id"]+">"+infoProvincias[provincia]["provincia"]+"</option>";
				}
				cadena+="</select>";
				$("#capaprovincias").html(cadena);
			},
			error: function(response){
				alert(3);
			}
		});
	}

	function cargarpoblaciones() {
		$.ajax({
			url: 'cargarpoblaciones.php?idprovincia=' + $("#provincia").val(),
			success: function (response) {
				var infoPoblaciones=JSON.parse(response);
				cadena="<p>Poblacion</p> <select id='poblacion' name='poblacion'><option value=0>Elige una poblaci&oacuten</option>";

				for (poblacion in infoPoblaciones){
						cadena+="<option value="+infoPoblaciones[poblacion]["id"]+">"+infoPoblaciones[poblacion]["poblacion"]+"</option>";
				}
				cadena+="</select>";
				$("#capapoblaciones").html(cadena);
			},
			error: function(response){
				alert(3);
			}
		});
	}
	function setProvinciaPoblacion(){
		$.ajax({
			function(){
				alert(2);

			}
		});
	}
	$( document ).ready(function() {
		cargarcomunidades();
		setProvinciaPoblacion
});
