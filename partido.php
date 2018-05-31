<?php
	require 'assets/php/config.php';
	require 'assets/php/functions.php';

	$conexion = conectar_bd($config_bd);

    $id_liga = $_GET['id_liga'];
    $id_partido = $_GET['id_partido'];

	$partido = obtener_partido($conexion,$id_partido);
	$partido = $partido[0];
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
	<a href="index.php"><div class="home content-center"><i class="fas fa-home"></i></div></a>
	<section class="content content-center">
		<div class="container">
			<div class="partido">
				<?php 
					$equipo_l = obtener_equipo($conexion,$partido['equipo_l']);
					$equipo_v = obtener_equipo($conexion,$partido['equipo_v']);
					$equipo_l = $equipo_l[0];
                    $equipo_v = $equipo_v[0];
                    $goles = obtener_goles_partido($conexion,$partido['id']);
                    $tarjetas = obtener_tarjetas_partido($conexion,$partido['id']);
				?>                    
                <div class="cont-result">
                    <div class="result-cont-part">
                        <div class="cont-escudo"><img src="assets/img/escudos/<?php echo $equipo_l['escudo']; ?>" alt="<?php echo $equipo_l['nombre']; ?>" ></div>
		        		<div><h3><?php echo $equipo_l['nombre']; ?></h3></div>
                    </div>
                    <div class="result-cont-part">
                        <span><?php echo $partido['fecha']; ?></span>
                        <h1><?php 
                            if($partido['goles_l'] == null && $partido['goles_v'] == null) {
                                echo ' - ';
                            } else {
                                echo $partido['goles_l'] . ' - ' . $partido['goles_v'];
                            }
                        ?></h1>
                    </div>
                    <div class="result-cont-part">
                        <div class="cont-escudo"><img src="assets/img/escudos/<?php echo $equipo_v['escudo']; ?>" alt="<?php echo $equipo_v['nombre']; ?>"></div>
					    <div><h3><?php echo $equipo_v['nombre']; ?></h3></div>
                    </div>                    
                </div>
                <div class="partido-info">
					<h4 class="title" style="color: #424242">Datos</h4>
                    <div class="partido-info-half">
                        <?php foreach ($goles as $gol):
                            $jugador = obtener_jugador($conexion,$gol['id_jugador']);
                            $jugador = $jugador[0];
                            
                            if($jugador['id_equipo'] == $partido['equipo_l']):
                        ?>
                        <div class="dato"> <?php echo $jugador['dorsal'] . '. ' . $jugador['nombre'] . ' ' . $jugador['apellidos'];  ?> <i class="fas fa-futbol"></i></div>
                        <?php endif;endforeach;?>
                        <?php foreach ($tarjetas as $tarjeta):
                            $jugador = obtener_jugador($conexion,$tarjeta['id_jugador']);
                            $jugador = $jugador[0];
                            
                            if($jugador['id_equipo'] == $partido['equipo_l']):
                        ?>
                        <div class="dato"> <?php echo $jugador['dorsal'] . '. ' . $jugador['nombre'] . ' ' . $jugador['apellidos'];  ?> 
                            <?php if($tarjeta['color'] == 'amarilla'): ?>
                            <img src="assets/img/amarilla.png" alt="Amarilla">
                            <?php ;else : ?>
                            <img src="assets/img/roja.png" alt="Roja">
                            <?php endif;?>
                        </div>
                        <?php endif;endforeach;?>
                    </div>
                    <div class="partido-info-half">
                        <?php foreach ($goles as $gol):
                            $jugador = obtener_jugador($conexion,$gol['id_jugador']);
                            $jugador = $jugador[0];
                            if($jugador['id_equipo'] == $partido['equipo_v']):
                        ?>
                        <div class="dato"><i class="fas fa-futbol"></i> <?php echo $jugador['dorsal'] . '. ' . $jugador['nombre'] . ' ' . $jugador['apellidos'];  ?></div>
                        <?php endif;endforeach;?>
                        <?php foreach ($tarjetas as $tarjeta):
                            $jugador = obtener_jugador($conexion,$tarjeta['id_jugador']);
                            $jugador = $jugador[0];
                            
                            if($jugador['id_equipo'] == $partido['equipo_v']):
                        ?>
                        <div class="dato"> 
                            <?php if($tarjeta['color'] == 'amarilla'): ?>
                            <img src="assets/img/amarilla.png" alt="Amarilla">
                            <?php ;else : ?>
                            <img src="assets/img/roja.png" alt="Roja">
                            <?php endif;?>
                            <?php echo $jugador['dorsal'] . '. ' . $jugador['nombre'] . ' ' . $jugador['apellidos'];  ?>
                        </div>
                        <?php endif;endforeach;?>
                    </div>
                </div>
			</div>
		</div>
	</section>
	<!-- SCRIPTS -->
	<script src="assets/js/main.js"></script>
</body>
</html>