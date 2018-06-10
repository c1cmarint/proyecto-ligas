<?php
session_start();
session_name('admin');

require '../assets/php/config.php';
require '../assets/php/functions.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
	$conexion = conectar_bd($config_bd);

	$id_partido = $_GET['id_partido'];
	$id_liga = $_GET['id_liga'];

	$partido = obtener_partido($conexion,$id_partido);

	$partido = $partido[0];

	$equipo_l = obtener_equipo($conexion,$partido['equipo_l']);
	$equipo_v = obtener_equipo($conexion,$partido['equipo_v']);
	$equipo_l = $equipo_l[0];
	$equipo_v = $equipo_v[0];

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$goles_l = $_POST['goles_l'];
		$goles_v = $_POST['goles_v'];
		
		$insertar_resultado = $conexion->prepare('
			UPDATE partidos SET goles_l = :goles_l, goles_v = :goles_v WHERE id = :id_partido
		');
		$insertar_resultado->execute(array(
			':id_partido' => $id_partido,
			':goles_l' => $goles_l,
			':goles_v' => $goles_v
		));

		if($goles_l > $goles_v) {
			$sumar_puntos_l = $conexion->prepare('
				UPDATE equipos SET puntos = puntos + 3, jugados = jugados + 1, ganados = ganados + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_l->execute(array(
				':id_equipo' => $equipo_l['id']
			));
			$sumar_puntos_v = $conexion->prepare('
				UPDATE equipos SET jugados = jugados + 1, perdidos = perdidos + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_v->execute(array(
				':id_equipo' => $equipo_v['id']
			));
		} else if ($goles_l == $goles_v) {
			$sumar_puntos_l = $conexion->prepare('
				UPDATE equipos SET puntos = puntos + 1, jugados = jugados + 1, empatados = empatados + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_l->execute(array(
				':id_equipo' => $equipo_l['id']
			));

			$sumar_puntos_v = $conexion->prepare('
				UPDATE equipos SET puntos = puntos + 1, jugados = jugados + 1, empatados = empatados + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_v->execute(array(
				':id_equipo' => $equipo_v['id']
			));
		} else {
			$sumar_puntos_v= $conexion->prepare('
				UPDATE equipos SET puntos = puntos + 3, jugados = jugados + 1, ganados = ganados + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_v->execute(array(
				':id_equipo' => $equipo_v['id']
			));
			$sumar_puntos_l = $conexion->prepare('
				UPDATE equipos SET jugados = jugados + 1, perdidos = perdidos + 1 WHERE id = :id_equipo
			');
			$sumar_puntos_l->execute(array(
				':id_equipo' => $equipo_l['id']
			));
		}

		for ($i=1; $i <= $goles_l; $i++) { 
			$goleador = $_POST['goleador-l-' . $i];

			$insertar_goleador  = $conexion->prepare('
				INSERT INTO goles (id_partido,id_jugador,id_liga,id_equipo) VALUES (:id_partido, :id_jugador,:id_liga,:id_equipo)
			');
			$insertar_goleador->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $goleador,
				':id_liga' => $id_liga,
				':id_equipo' => $equipo_l['id']
			));
		}

		for ($j=1; $j <= $goles_v; $j++) { 
			$goleador = $_POST['goleador-v-' . $j];
			
			$insertar_goleador  = $conexion->prepare('
				INSERT INTO goles (id_partido,id_jugador,id_liga,id_equipo) VALUES (:id_partido, :id_jugador,:id_liga,:id_equipo)
			');
			$insertar_goleador->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $goleador,
				':id_liga' => $id_liga,
				':id_equipo' => $equipo_v['id']
			));
		}

		for ($r=1; $r <= $_POST['tarjetas-a-l']; $r++) { 
			$amarilla = $_POST['amarillas-l-' . $r];

			$insertar_amarilla  = $conexion->prepare('
				INSERT INTO tarjetas (id_partido,id_jugador,id_liga,color) VALUES (:id_partido, :id_jugador,:id_liga,"amarilla")
			');
			$insertar_amarilla->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $amarilla,
				':id_liga' => $id_liga
			));
		}

		for ($x=1; $x <= $_POST['tarjetas-r-l']; $x++) { 
			$roja = $_POST['rojas-l-' . $x];

			$insertar_roja  = $conexion->prepare('
				INSERT INTO tarjetas (id_partido,id_jugador,id_liga,color) VALUES (:id_partido, :id_jugador,:id_liga,"roja")
			');
			$insertar_roja->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $roja,
				':id_liga' => $id_liga
			));
		}

		for ($p=1; $p <= $_POST['tarjetas-a-v']; $p++) { 
			$amarilla = $_POST['amarillas-v-' . $p];

			$insertar_amarilla  = $conexion->prepare('
				INSERT INTO tarjetas (id_partido,id_jugador,id_liga,color) VALUES (:id_partido, :id_jugador,:id_liga,"amarilla")
			');
			$insertar_amarilla->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $amarilla,
				':id_liga' => $id_liga
			));
		}

		for ($z=1; $z <= $_POST['tarjetas-r-v']; $z++) { 
			$roja = $_POST['rojas-v-' . $z];

			$insertar_roja  = $conexion->prepare('
				INSERT INTO tarjetas (id_partido,id_jugador,id_liga,color) VALUES (:id_partido, :id_jugador,:id_liga,"roja")
			');
			$insertar_roja->execute(array(
				':id_partido' => $id_partido,
				':id_jugador' => $roja,
				':id_liga' => $id_liga
			));
		}
		echo '<script> location.href="liga-admin.php?id=' . $id_liga . '"; </script>';
	}

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
	<a href="opcionPartido.php?id_partido=<?php echo $partido['id'] . '&id_liga=' . $id_liga; ?>"><div class="back content-center" title="Volver"><span><i class="fas fa-arrow-left"></i></span></div></a>
	<a href="logout.php"><div class="home content-center" title="Log out"><i class="fas fa-sign-out-alt"></i></div></a>
<section class="content content-center">	  
	<div class="container">
    	<h1 class="title"><?php echo $equipo_l['nombre'] . ' - ' . $equipo_v['nombre']; ?></h1>
		<div class="container-insertar-liga">
			<?php if($partido['goles_l'] == null && $partido['goles_v'] == null): ?>
			<form method="post">
				<label>Goles <?php echo $equipo_l['nombre']; ?></label>
				<input type="number" min="0" value="<?php if($partido['goles_l'] == null) {echo 0;} else {echo $partido['goles_l'];} ?>" name="goles_l" onchange="crearGoleadores(event,'goles','local')" required>
				<div id="goles_l" value="<?php echo $equipo_l['id']; ?>">
				</div>
				<label>Amarillas <?php echo $equipo_l['nombre']; ?></label>
				<input type="number" min="0" value="0" name="tarjetas-a-l" onchange="crearGoleadores(event,'amarillas','local')" required>
				<div id="amarillas_l" value="<?php echo $equipo_l['id']; ?>"></div>
				<label>Rojas <?php echo $equipo_l['nombre']; ?></label>
				<input type="number" min="0" value="0" name="tarjetas-r-l" onchange="crearGoleadores(event,'rojas','local')" required>
				<div id="rojas_l" value="<?php echo $equipo_l['id']; ?>"></div>
				<label>Goles <?php echo $equipo_v['nombre']; ?></label>
				<input type="number" min="0" value="<?php if($partido['goles_v'] == null) {echo 0;} else {echo $partido['goles_v'];} ?>" name="goles_v" onchange="crearGoleadores(event,'goles','visitante')" required>
				<div id="goles_v" value="<?php echo $equipo_v['id']; ?>">
				</div>
				<label>Amarillas <?php echo $equipo_v['nombre']; ?></label>
				<input type="number" min="0" value="0" name="tarjetas-a-v" onchange="crearGoleadores(event,'amarillas','visitante')" required>
				<div id="amarillas_v" value="<?php echo $equipo_v['id']; ?>"></div>
				<label>Rojas <?php echo $equipo_l['nombre']; ?></label>
				<input type="number" min="0" value="0" name="tarjetas-r-v" onchange="crearGoleadores(event,'rojas','visitante')" required>
				<div id="rojas_v" value="<?php echo $equipo_v['id']; ?>"></div>
				<input type="submit" value="Introducir datos">
			</form>
			<?php ;else: ?>
			<p>El partido ya se ha jugado.</p>
			<?php endif; ?>
		</div>
	</div>
</section>	  
	<!-- SCRIPTS -->
	<script src="../assets/js/main.js"></script>
</body>
</html>
