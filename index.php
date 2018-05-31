<?php
	require 'assets/php/config.php';
	require 'assets/php/functions.php';

	$conexion = conectar_bd($config_bd);

	$ligas = obtener_ligas($conexion);

	$equipos = obtener_todos_equipos($conexion);

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$nombre = $_POST['nombre'];
		$email = $_POST['email'];
		$asunto = $_POST['asunto'];
		$mensaje = $_POST['mensaje'];
		$para = 'c1cmarint@ieslavereda.es';

		$cabeceras = 'From' . " " . $email . "\r\n";
      	$cabeceras .= "Content-type: text/html; charset=utf-8";

	    $msjCorreo = "Nombre: " . $nombre;
      	$msjCorreo .= "\r\n";
      	$msjCorreo .= "Email: " . $email;
      	$msjCorreo .= "\r\n";
      	$msjCorreo .= "Asunto: " . $asunto;
      	$msjCorreo .= "\r\n";
      	$msjCorreo .= "Mensaje: " . $mensaje;
		$msjCorreo .= "\r\n";
		  
		if (mail($para, $asunto, $msjCorreo, $cabeceras)) {
        	echo "<script language='javascript'>alert('Mensaje enviado.');</script>";
    	} else {
        	echo "<script language='javascript'>alert('No se ha enviado correctamente, por favor contacte con nosotros a través del teléfono móvil. Disculpe las molestias.');</script>";
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
	<header>
		<nav>
			<ul>
				<li><a href="#quienes-somos">¿QUIÉNES SOMOS?</a></li>
				<li><a href="#ligas">LIGAS</a></li>
				<li><a href="#inscribirse">¿CÓMO INSCRIBIRSE?</a></li>
				<li><a href="#contacta">CONTACTA</a></li>
			</ul>
		</nav>
	</header>
	<section id="portada" class="content-center">
		<div class="portada-logo">
			<img src="assets/img/logo.png" alt="Logo">
		</div>
	</section>
	<section id="quienes-somos" class="content content-center">
		<div class="content-center">
			<div class="container">
				<h1 class="title">¿QUIÉNES SOMOS?</h1>
				<div class="text-widget">
					<img src="assets/img/portada.png" alt="">
					<p>Somos una organización creada para poder disfrutar del deporte que más nos apasiona, gestionando las ligas de fútbol sala. Formada por un grupo de administradores que llevan a cabo toda la inscripción y actualizando la web constantemente con todos los resultados. Contamos con un equipo de árbitros titulado y con años de experiéncia en el ámbito futbolístico.</p>
					<p>Contamos con unas instalaciones en perfectas condiciones, con hasta cuatro campos al aire libre y otros dos cubiertos para poder disputar todos los partidos. Las pista cubiertas y dos de las cuatro al aire libre cuentan con marcadores a pie de pista para poder seguir el tiempo y el marcador en directo durante el transcurso de los partidos.</p>
					<p style="text-align: center;"><b>¡Reúne tú equipo y apúntate a una de nuestras ligas para disfrutar de este deporte!</b></p>
					<p>El pago se realizará en la dispita de cada partido pagando <b>20€</b> por equipo. Antes de empezar la liga, se pagará un partido por adelantado de fianza, y la manera de devolver la fianza, será no pagando el último partido de la liga.</p>
				</div>
			</div>
		</div>
	</section>
	<section id="ligas" class="content content-center">
		<div class="content-center">
			<div class="container">
				<h1 class="title">NUESTRAS LIGAS</h1>
				<div class="container-wrap">
					<?php foreach($ligas as $liga): ?>
					<a href="liga.php?id=<?php echo $liga['id']; ?>"><div class="ligas-btn content-center"><span><?php echo $liga['nombre'] ?></span></div></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<section id="inscribirse" class="content content-center">
		<div class="content-center">
			<div class="container" style="text-align: center;">
				<h1 class="title">¿CÓMO INSCRIBIRSE?</h1>
				<div class="text-widget">
					<p>Para poder inscribirse, primero uno de los miembros del equipo deberá contactar con nosotros, bien por correo o por teléfono, para que nos diga el nombre del equipo y podamos darlos de alta. Una vez hayamos introducio el equipo, avisaremos a esa persona que ya está disponible y os podries inscribir.</p>
					<p style="text-align: center">Para poder inscribiros en una de nuestras ligas y apuntaros a vuestro equipo, serán necesarios estos datos:</p>
					<div class="text-widget-list">
						<ul>
						<li>Nombre</li>
						<li>Apellidos</li>
						<li>Dorsal</li>
						<li>Imágen de la cara</li>
					</ul>
					</div>
				</div>
				<button class="btn-inscribirse header-btn-inscribirse right-btn-inscribirse" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">INSCRIBIRME</button>
			</div>
		</div>
	</section>
	<section id="contacta" class="content content-center">
		<div class="container">
			<h1 class="title contacta-title">Contacta</h1>
			<div class="contacta-box">
				<p>Contacta con nosotros para cualquier duda o problema que surja. Para contactar con nosotros puedes hacerlo de las siguientes maneras: </p>
				<ul>
					<li><i class="fas fa-envelope"></i> <span>Puedes enviarnos un correo a través de nuestro formulario.</span></li>
					<li><i class="fas fa-mobile"></i> <span>Contacta llamandonos a nuestro teléfono o escríbenos por Whatsapp: 694897561.</span></li>
					<li><i class="fas fa-map-marker-alt"></i><span>También puedes venir a visitarnos a nuestras instalaciones.</span></li>
				</ul>
				<div class="map">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3074.812213379089!2d-0.5422048853022857!3d39.58638241364327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd605eeaf2aec983%3A0x2fc43e3ecc61e17b!2sIES+la+Vereda!5e0!3m2!1ses!2ses!4v1527534274592" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="contacta-box">
				<form>
					<input type="text" name="nombre" placeholder="Nombre" required>
					<input type="email" name="email" placeholder="Correo" required>	
					<input type="text" name="asunto" placeholder="Asunto" required>
					<textarea name="mensaje" cols="30" rows="10" placeholder="Mensaje" requiered></textarea>
					<input type="submit" value="Enviar">
				</form>				
			</div>
		</div>
	</section>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
    		<div class="modal-content">
      			<div class="modal-header">
        			<h5 class="modal-title" id="exampleModalLabel">INSCRIBIRME</h5>
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          				<span aria-hidden="true">&times;</span>
        			</button>
      			</div>
      			<div class="modal-body">
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Equipo</label>
						<select class="form-control" id="equipo-form" name="id_equipo" required>
							<?php foreach ($equipos as $equipo):?>
							<option value="<?php echo $equipo['id']; ?>"><?php echo $equipo['nombre'] ?></option>
							<?php endforeach;?>
						</select>
					</div>
          			<div class="form-group">
						<input type="text" name="nombre-jugador" class="form-control" placeholder="Nombre" id="nombre-form">
					</div>
					<div class="form-group">
						<input type="text" name="apellidos-jugador" class="form-control" placeholder="Apellidos" id="apellidos-form">
					</div>
					<div class="form-group">
						<input type="text" name="dni-jugador" class="form-control" placeholder="DNI" id="dni-form">
					</div>
					<div class="form-group">
						<input type="number" name="dorsal-jugador" class="form-control" min="1" max="99" placeholder="Dorsal" id="dorsal-form">
					</div>
					<div class="form-group">
						<input type="file" name="foto-jugador" class="form-control" id="file" style="overflow: hidden;">
					</div>
					<div class="form-group" id="alert"></div>
					<button id="enviarformulario" class="btn btn-primary">INSCRIBIRME</button>
      			</div>
    		</div>
  		</div>
	</div>
	<!-- SCRIPTS -->
	<script src="assets/js/main.js"></script>
</body>
</html>