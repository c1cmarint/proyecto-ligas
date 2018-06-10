<?php
session_start();
session_name('admin');

require '../assets/php/config.php';
require '../assets/php/functions.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
	$conexion = conectar_bd($config_bd);

	$id_liga = $_GET['id'];

	$liga = obtener_liga($conexion,$id_liga);

	$liga = $liga[0];

} else {
	header('Location: login.php');
}

$now = time();

if ($now > $_SESSION['expire']) {
	session_destroy();
	header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>LIGAS FUTBOL SALA</title>
		<!-- FAVICON -->
		<link rel="icon" type="image/png" href="../assets/img/logo.png">
		<!-- CSS -->
		<link rel="stylesheet" href="../assets/css/styles.css">
		<!-- GOOGLE FONTS -->
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Archivo+Black" rel="stylesheet">
		<!-- FONTAWESOME ICONS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
		<!-- JQUERY -->
		<script type="text/javascript" src="../assets/js/jquery-3.3.1.min.js"></script>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</head>
<body>
	<a href="liga-admin.php?id=<?php echo $id_liga; ?>"><div class="back content-center" title="Volver"><span><i class="fas fa-arrow-left"></i></span></div></a>
	<a href="logout.php"><div class="home content-center" title="Log out"><i class="fas fa-sign-out-alt"></i></div></a>
<section class="content content-center"> 
	<div class="container-insertar-liga">
    	<h1 class="title" style="color:#2b3c4d;">INSERTAR EQUIPO</h1>
		<h2  style="color:#2b3c4d;"><?php echo $liga['nombre']; ?></h2>
    	<form method="post" enctype="multipart/form-data">
      		<input type="text" name="equipo" placeholder="Nombre del equipo" required>
	  	<input type="file" name="escudo" required>
		<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$nombre_equipo = $_POST['equipo'];
	  
			$equipo_existe = $conexion->prepare('
		  		SELECT * FROM equipos WHERE nombre = :nombre_equipo AND id_liga = :id_liga
			');
  
			$equipo_existe->execute(array(
				':nombre_equipo' => $nombre_equipo,
				':id_liga' => $id_liga
			));
			
			$equipo_existe = $equipo_existe->fetchAll();

			if (count($equipo_existe) == 0) {
				$destino = '../assets/img/escudos/';
				$foto = $destino . $_FILES['escudo']['name'];
				move_uploaded_file($_FILES['escudo']['tmp_name'], $foto);

		  		$consulta = $conexion->prepare('
					INSERT INTO equipos (nombre,id_liga,escudo) VALUES (:nombre_equipo,:id_liga,:destino)
		  		');

				$consulta->execute(array(
					':nombre_equipo' => $nombre_equipo,
					':id_liga' => $id_liga,
					':destino' => $_FILES['escudo']['name']
				));
				echo '<script> location.href="liga-admin.php?id=' . $id_liga . '"; </script>';
			} else {
				echo '<div class="alert alert-danger">Este equipo ya está inscrito en esta liga. Prueba con otro nombre.</div>';
			}
		}
		?>
      		<input type="submit" value="Insertar">
    	</form>
	  </div>
</section>	  
	<!-- SCRIPTS -->
	<script src="../assets/js/main.js"></script>
</body>
</html>
