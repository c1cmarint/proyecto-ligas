<?php
	require 'assets/php/config.php';
	require 'assets/php/functions.php';

	$conexion = conectar_bd($config_bd);

	$id_liga = $_GET['id'];

	$liga = obtener_liga($conexion,$id_liga);

	$liga = $liga[0];

	$equipos = obtener_equipos($conexion,$id_liga);
	$max_goleadores = obtener_max_goleadores($conexion,$id_liga);
	$max_amarillas = obtener_max_amarillas($conexion,$id_liga);
	$max_rojas = obtener_max_rojas($conexion,$id_liga);
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</head>
<body>
	<div class="menu-icon content-center">
		<i id="menu" class="fas fa-bars"></i>
		<i id="cross" class="fas fa-times"></i>
	</div>
	<a href="index.php"><div class="home content-center" title="Inicio"><i class="fas fa-home"></i></div></a>
	<header>
		<nav>
			<ul>
				<li><a href="#equipos">Equipos</a></li>
				<li><a href="#jornadas">Jornadas</a></li>
				<li><a href="#clasificacion">Clasificación</a></li>
				<li><a href="#estadisticas">Estadísticas</a></li>
			</ul>
		</nav>
	</header>
	<section id="equipos" class="content content-center">
		<div class="content-center">
			<div class="container">
				<h1 class="title"><?php echo $liga['nombre']; ?></h1>
				<div class="container-wrap box-teams">
					<?php foreach($equipos as $equipo): ?>
					<a href="equipo.php?id-equipo=<?php echo $equipo['id'] . '&id-liga=' . $equipo['id_liga']; ?>"><div class="liga-equipo content-center">
						<div class="liga-equipo-content">
							<img src="assets/img/escudos/<?php echo $equipo['escudo']; ?>" alt="Escudo">
							<p><?php echo $equipo['nombre']; ?></p>
						</div>
					</div></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<section id="jornadas" class="content content-center">
		<div class="container">
			<h1 class="title">JORNADAS</h1>
			<div class="container-wrap">
			<?php
				$nums_jornadas = obtener_nums_jornadas($conexion,$id_liga);
				if (count($nums_jornadas) == 0): ?>
				<div class="genera-jornadas">
					<p>No se han generado aún las jornadas.</p>
				</div>
				<?php 
					;else:
						foreach ($nums_jornadas as $num_jornada):
							$partidos = obtener_partidos($conexion,$id_liga,$num_jornada['num_jornada']);
				?>
				<div class="jornada">
					<table>
						<caption>JORNADA <?php echo $num_jornada['num_jornada'] ?></caption>
						<?php foreach ($partidos as $partido): ?>
						<?php 
							$equipo_l = obtener_equipo($conexion,$partido['equipo_l']);
							$equipo_v = obtener_equipo($conexion,$partido['equipo_v']);
							$equipo_l = $equipo_l[0];
							$equipo_v = $equipo_v[0];
						?>
						<tr onclick="location.href = 'partido.php?id_liga=<?php echo $id_liga . '&id_partido=' . $partido['id']; ?>'">
							<td>
								<div><img src="assets/img/escudos/<?php echo $equipo_l['escudo']; ?>" alt="<?php echo $equipo_l['nombre']; ?>"></div>
								<div><?php echo $equipo_l['nombre']; ?></div> 
							</td>
							<td >
								<div class="partido-resultado">
							<?php 
								if ($partido['goles_l'] == null && $partido['goles_v'] == null) {
									echo $partido['fecha'];
								} else {
									echo $partido['goles_l'] . ' - ' . $partido['goles_v'];
								}
							?>
							</div></td>
							<td>
								<div><img src="assets/img/escudos/<?php echo $equipo_v['escudo']; ?>" alt="<?php echo $equipo_v['nombre']; ?>"></div>
								<div><?php echo $equipo_v['nombre']; ?></div>
							</td>
						</tr>
						<?php endforeach;?>
					</table>
				</div>
				<?php endforeach;endif;?>
			</div>
		</div>	
	</section>
	<section id="clasificacion" class="content content-center">
		<div class="container">
			<h1 class="title">CLASIFICACIÓN</h1>
			<table>
				<tr>
					<th class="table-center">POS</th>
					<th>Equipo</th>
					<th class="table-center">pt</th>
					<th class="table-center">pj</th>
					<th class="table-center">pg</th>
					<th class="table-center">pe</th>
					<th class="table-center">pp</th>
					<th class="table-center">Goles</th>
				</tr>
				<?php 
					$pos = 1;
					foreach ($equipos as $equipo):
						$goles = obtener_goles_equipo($conexion,$equipo['id']);
						$goles = $goles[0];
				?>
				<tr onclick="location.href = 'equipo.php?id-equipo=<?php echo $equipo['id'] . '&id-liga=' . $equipo['id_liga']; ?>'">
					<td class="table-center" style="background-color: #424242; color: #fff; font-weight: bold;"><?php echo $pos . 'º'; ?></td>
					<td><?php echo $equipo['nombre']; ?></td>
					<td class="table-center"><?php echo $equipo['puntos']; ?></td>
					<td class="table-center"><?php echo $equipo['jugados']; ?></td>
					<td class="table-center"><?php echo $equipo['ganados']; ?></td>
					<td class="table-center"><?php echo $equipo['empatados']; ?></td>
					<td class="table-center"><?php echo $equipo['perdidos']; ?></td>
					<td class="table-center"><?php echo $goles['count(id_equipo)']; ?></td>
				</tr>
				<?php $pos++;endforeach; ?>
			</table>
		</div>
	</section>
	<section id="estadisticas" class="content content-center">
		<div class="container">
			<div class="container-wrap">
				<div class="estadisticas-tablas">
					<table>
						<tr style="height: 60px;">
							<th>Nombre</th>
							<th class="table-center"><i class="fas fa-futbol"></i></th>
						</tr>
						<?php foreach ($max_goleadores as $max_goleador): ?>
						<tr>
							<td><?php echo $max_goleador['nombre'] . ' ' . $max_goleador['apellidos']; ?></td>
							<td class="table-center"><?php echo $max_goleador[3]; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class="estadisticas-tablas">
					<table>
						<tr>
							<th>Nombre</th>
							<th class="table-center"><img src="assets/img/amarilla.png" alt="Amarillas"></th>
						</tr>
						<?php foreach ($max_amarillas as $max_amarilla): ?>
						<tr>
							<td><?php echo $max_amarilla['nombre'] . ' ' . $max_amarilla['apellidos']; ?></td>
							<td class="table-center"><?php echo $max_amarilla[3]; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class="estadisticas-tablas">
					<table>
						<tr>
							<th>Nombre</th>
							<th class="table-center"><img src="assets/img/roja.png" alt="Rojas"></th>
						</tr>
						<?php foreach ($max_rojas as $max_roja): ?>
						<tr>
							<td><?php echo $max_roja['nombre'] . ' ' . $max_roja['apellidos']; ?></td>
							<td class="table-center"><?php echo $max_roja[3]; ?></td>
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
