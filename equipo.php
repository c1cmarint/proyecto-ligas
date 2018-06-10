<?php
	require 'assets/php/config.php';
	require 'assets/php/functions.php';

	$conexion = conectar_bd($config_bd);

	$id_equipo = $_GET['id-equipo'];
	$id_liga = $_GET['id-liga'];

	$equipo = obtener_equipo($conexion,$id_equipo);
	$equipo = $equipo[0];
	$jugadores = obtener_jugadores_equipo($conexion,$id_equipo);
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
	<a href="liga.php?id=<?php echo $id_liga; ?>"><div class="back content-center" title="Volver"><span><i class="fas fa-arrow-left"></i></span></div></a>
	<a href="index.php"><div class="home content-center" title="Inicio"><i class="fas fa-home"></i></div></a>
	<section class="content content-center">
		<div class="container">
			<div class="container-equipo">
				<div class="header">
					<img src="assets/img/escudos/<?php echo $equipo['escudo']; ?>" alt="<?php echo $equipo['nombre']; ?>">
					<h2 class="title"><?php echo $equipo['nombre']; ?></h2>
				</div>
				<div class="jugadores">
					<table>
						<tr>
							<th>Jugador</th>
							<th class="table-center">Dorsal</th>
							<th class="table-center"><i class="fas fa-futbol"></i></th>
							<th class="table-center"><img src="assets/img/amarilla.png" alt="Amarillas"></th>
							<th class="table-center"><img src="assets/img/roja.png" alt="Rojas"></th>
						</tr>
						<?php foreach ($jugadores as $jugador): 
							$goles = obtener_goles_jugador($conexion,$jugador['id']);
							$goles = $goles[0];
							$amarillas = obtener_amarillas_jugador($conexion,$jugador['id']);
							$amarillas = $amarillas[0];
							$rojas = obtener_rojas_jugador($conexion,$jugador['id']);
							$rojas = $rojas[0];
						?>
						<tr onclick="location.href = 'jugador.php?id-jugador=<?php echo $jugador['id'] . '&id-equipo=' . $jugador['id_equipo'] . '&id-liga=' . $id_liga; ?>'">
							<td><?php echo $jugador['nombre'] . ' ' .$jugador['apellidos'] ?></td>
							<td class="table-center"><?php echo $jugador['dorsal'] ?></td>
							<td class="table-center"><?php echo $goles['count(id_jugador)']; ?></td>
							<td class="table-center"><?php echo $amarillas['count(id_jugador)']; ?></td>
							<td class="table-center"><?php echo $rojas['count(id_jugador)']; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
	</section>
	<!-- SCRIPTS -->
	<script src="assets/js/main.js"></script>
</body>
</html>
