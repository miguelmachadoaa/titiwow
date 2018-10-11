
<!DOCTYPE html>
<html>
<head>

	<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


	<title>	</title>
</head>
<body>

</body>
</html>





<?php
$mysqli = new mysqli("localhost", "root", "", "alpina");

/* comprobar la conexión */
if ($mysqli->connect_errno) {
    printf("Falló la conexión: %s\n", $mysqli->connect_error);
    exit();
}

/* Crear una tabla que no devuelve un conjunto de resultados */


$resultado = $mysqli->query("SELECT * FROM alp_productos limit 400,20");
/* Consultas de selección que devuelven un conjunto de resultados */

$res=mysqli_fetch_array($resultado);

?>

<table >	

		<tr>	

			<td style="width: 10em">Nombre	</td>
			<td style="width: 10em" >Imagen	</td>
			<td style="width: 10em">Accion	</td>

		</tr>




<?php

while ( $res=mysqli_fetch_array($resultado)) {

				$id=$res['id'];

	?>
		<tr>	
				<td><?php echo $res['nombre_producto']; ?>	</td>
				<td><img width="60px" src="<?php echo 'http://localhost/laravel55/public/uploads/productos/'.$res['imagen_producto']; ?>">	</td>
				<td><button type="button" onclick="establecerDefault('<?php echo $id; ?>')"	> Establecer defecto 	</button>	</td>
		</tr>

		


		<?php 
}


   
	?>
</table>
		<?php 

	

$mysqli->close();
?>

<script src="jquery-3.3.1.js" type="text/javascript"></script>


<script type="text/javascript">

			function establecerDefault(id){
				
				$.ajax({
                type: "POST",
                data:{ id},
                url: "update.php",
                    
                complete: function(datos){     

                    //location.reload();

                }
            		});
	


			}


		
</script>