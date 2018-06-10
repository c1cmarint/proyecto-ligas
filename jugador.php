<?php
	require 'assets/php/config.php';
	require 'assets/php/functions.php';

	$conexion = conectar_bd($config_bd);

	$id_jugador = $_GET['id-jugador'];
	$id_equipo = $_GET['id-equipo'];
	$id_liga = $_GET['id-liga'];

	$jugador = obtener_jugador($conexion,$id_jugador);
	$jugador = $jugador[0];

	$goles_jug = obtener_goles_jugador($conexion,$id_jugador);
	$goles_jug = $goles_jug[0];

	$goles_eq = obtener_goles_equipo($conexion,$jugador['id_equipo']);
	$goles_eq = $goles_eq[0];

	$amarillas = obtener_amarillas_jugador($conexion,$id_jugador);
	$amarillas = $amarillas[0];

	$rojas = obtener_rojas_jugador($conexion,$id_jugador);
	$rojas = $rojas[0];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>LIGAS FUTBOL SALA</title>
		<!-- FAVICON -->
		<link rel="icon" type="image/png" href="assets/img/logo.png">
		<!-- CSS -->
		<link rel="stylesheet" href="assets/css/styles.css">
		<!-- GOOGLE FONTS -->
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Archivo+Black" rel="stylesheet">
		<!-- FONTAWESOME ICONS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
		<!-- JQUERY -->
		<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
		<!-- BOOTSTRAP -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</head>
<body>
	<a href="equipo.php?id-equipo=<?php echo $id_equipo .'&id-liga=' . $id_liga; ?>"><div class="back content-center" title="Volver"><span><i class="fas fa-arrow-left"></i></span></div></a>
	<a href="index.php"><div class="home content-center" title="Inicio"><i class="fas fa-home"></i></div></a>
	<section class="content content-center">
		<div class="container">
			<div class="container-equipo">
				<div class="header" style="border: none;">
					<img src="assets/img/jugadores/<?php echo $jugador['foto']; ?>" alt="<?php echo $jugador['nombre']; ?>">
					<h2 class="title"><?php echo $jugador['nombre'] . ' ' . $jugador['apellidos']; ?></h2>
				</div>
				<div class="jugadores">
					<div class="jugador-info-half">
						<div style="width: 100%; text-align: center;">
							<h4>GOLES</h4>
							<div class="tarjetas">
								<div><span><?php echo $goles_jug[0] . '/' . $goles_eq[0]; ?><i class="fas fa-futbol"></i></span></div>
							</div>
						</div>
					</div>
					<div class="jugador-info-half">
						<div style="width: 100%; text-align: center;">
							<h4>TARJETAS</h4>
							<div class="tarjetas">
								<div class="tarjetas-amarillas"><?php echo $amarillas[0]; ?></div>
								<div class="tarjetas-rojas"><?php echo $rojas[0]; ?></div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- SCRIPTS -->
	<script src="assets/js/main.js"></script>
</body>
</html>
