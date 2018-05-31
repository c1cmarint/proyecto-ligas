<?php
session_start();
session_name('admin');

require '../assets/php/config.php';
require '../assets/php/functions.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
	$conexion = conectar_bd($config_bd);
	$id_liga = $_GET['id-liga'];
	$id_equipo = $_GET['id-equipo'];
	$id_jugador = $_GET['id-jugador'];

	$jugador = obtener_jugador($conexion,$id_jugador);
	$jugador = $jugador[0];

} else {
	header('Location: login.php');
}

$now = time();

if ($now > $_SESSION['expire']) {
	session_destroy();
	header('Location: login.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	$dorsal = $_POST['dorsal'];

	$existe_dorsal = $conexion->prepare('
		SELECT * FROM jugadores WHERE dorsal = :dorsal_jugador AND id_equipo = :id_equipo AND id != :id_jugador
	');

	$existe_dorsal->execute(array(
		':dorsal_jugador' => $dorsal,
		':id_equipo' => $id_equipo,
		':id_jugador' => $id_jugador
	));
				   
	$existe_dorsal = $existe_dorsal->fetchAll();

	if (count($existe_dorsal) == 0) {	
		$consulta = $conexion->prepare('
			UPDATE jugadores SET nombre = :nombre, apellidos = :apellidos, dorsal = :dorsal WHERE id = :id_jugador
		  ');

		$consulta->execute(array(
			':nombre' => $nombre,
			':apellidos' => $apellidos,
			':dorsal' => $dorsal,
			':id_jugador' => $id_jugador
		));
		header('Location: liga-admin.php?id=' . $id_liga);
	} else {
		echo '<div class="alert alert-danger">El dorsal ya está cogido. Prueba con otro dorsal.</div>';
	}
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
	<a href="liga-admin.php?id=<?php  echo $id_liga ?>"><div class="back content-center" title="Volver"><span><i class="fas fa-arrow-left"></i></span></div></a>
	<a href="logout.php"><div class="home content-center" title="Log out"><i class="fas fa-sign-out-alt"></i></div></a>
<section class="content content-center">  
	<div class="container-insertar-liga">
	    <h2 class="title" style="color:#2b3c4d;">Modificar Jugador</h2>
			<img class="img-jugador" src="../assets/img/jugadores/<?php echo $jugador['foto']; ?>" alt="<?php echo $jugador['nombre'] . ' ' . $jugador['apellidos']; ?>">
      		<form method="post">
				<input type="text" name="nombre" value="<?php echo $jugador['nombre'] ?>" required>
	  			<input type="text" name="apellidos" value="<?php echo $jugador['apellidos'] ?>" required>
	  			<input type="number" name="dorsal" value="<?php echo $jugador['dorsal'] ?>" min="1" max="99" required>	  
				<input type="submit" value="Actualizar">
			</form>
			<a href="borrarJugador.php?id=<?php echo $jugador['id']; ?>" class="btn-borrar-jugador">Borrar</a>  
	  </div>
</section>	  
	<!-- SCRIPTS -->
	<script src="../assets/js/main.js"></script>
</body>
</html>
