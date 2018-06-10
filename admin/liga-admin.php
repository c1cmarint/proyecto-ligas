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

		$equipos = obtener_equipos($conexion,$id_liga);

		$contador = 0;
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
<body style="width: auto; height: auto;">
	<a href="logout.php"><div class="home content-center" title="Log out"><i class="fas fa-sign-out-alt"></i></div></a>
<section class="content content-center">
	<div style="width: 100%;">	
	<div class="container" style="margin: 20px auto;">
		<a href="index.php"><i class="fas fa-arrow-left"></i> Volver</a>
		<h1 class="title"><?php echo $liga['nombre'];?></h1>
		<h2 class="title">EQUIPOS</h2>
		<?php foreach($equipos as $equipo): ?>
		<div class="liga-admin" onclick="desplegarJugadoresAdmin('cont-jug<?php echo $contador; ?>')">
			<div class="escudo-equipo"><img src="../assets/img/escudos/<?php echo $equipo['escudo']; ?>" alt="<?php echo $equipo['nombre']; ?>"><h2><?php echo $equipo['nombre']; ?></h2></div>
			<a href="borrarEquipo.php?id=<?php echo $equipo['id'] ?>" class="btn-borrar">Borrar</a>
		</div>
		<?php 
			$jugadores = obtener_jugadores_equipo($conexion,$equipo['id']);
		?>
		<div class="jugadores-equipo-admin" id="cont-jug<?php echo $contador; $contador++; ?>">
			<h3 class="title">Jugadores</h3>
			<?php if (count($jugadores) == 0) : ?>
				<p>No hay jugadores en este equipo.</p>
			<?php ;else : ?>
			<?php foreach($jugadores as $jugador): ?>
			<a href="jugador-admin.php?id-liga=<?php echo  $id_liga . '&id-equipo=' .$jugador['id_equipo'] . '&id-jugador=' . $jugador['id']; ?>"><div class="jugador-admin"><?php echo $jugador['dorsal'] . '. ' . $jugador['nombre'] . ' ' . $jugador['apellidos']; ?></div></a>
			<?php endforeach; endif;?>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="container" style="margin: 30px auto !important;">
		<h2 class="title">JORNADAS</h2>
		<div class="container-wrap">
			<?php if (count($equipos) == 0): ?>
			<p>Debes generar antes los equipos para poder crear las jornadas.</p>
			<a href="insertarEquipo.php?id=<?php echo $id_liga; ?>"><div class="back content-center" title="Nuevo equipo"><span><i class="fas fa-plus"></i></span></div></a>
			<?php
			;else:
				$nums_jornadas = obtener_nums_jornadas($conexion,$id_liga);
				if (count($nums_jornadas) == 0):
			?>
			<div class="generar-jornadas">
				<p>No se han generado aún las jornadas.</p>
				<a href="elegirFechas.php?id=<?php echo $id_liga; ?>" class="btn-jornadas">Elegir Fechas de los Partidos</a>
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
						<tr onclick="location.href = 'opcionPartido.php?id_partido=<?php echo $partido['id'] . '&id_liga=' . $id_liga; ?>'">
							<td>
								<div><img src="../assets/img/escudos/<?php echo $equipo_l['escudo']; ?>" alt=""></div>
								<div><?php echo $equipo_l['nombre']; ?></div> 
							</td>
							<td> 
							<?php 
								if ($partido['goles_l'] == null && $partido['goles_v'] == null) {
									echo $partido['fecha'];
								} else {
									echo $partido['goles_l'] . ' - ' . $partido['goles_v'];
								}
							?>
							</td>
							<td>
								<div><img src="../assets/img/escudos/<?php echo $equipo_v['escudo']; ?>" alt=""></div>
								<div><?php echo $equipo_v['nombre']; ?></div>
							</td>
						</tr>
						<?php endforeach;?>
					</table>
				</div>
				<?php endforeach;endif;endif;?>
			</div>	
	</div>
	</div>
</section>	
	<!-- SCRIPTS -->
	<script src="../assets/js/main.js"></script>
</body>
</html>
