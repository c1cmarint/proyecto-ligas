<?php
require '../assets/php/config.php';
require '../assets/php/functions.php';
require '../vendor/sfm/simple_files_manager.php';


if($_FILES['file']['error'] > 0) {
	echo "Error";
} else {
	$nombre_jugador = $_POST['nombre'];
	$apellidos_jugador = $_POST['apellidos'];
	$dni_jugador = $_POST['dni'];
	$dorsal_jugador = $_POST['dorsal'];
	$id_equipo = $_POST['id-equipo'];
	
	$conexion = conectar_bd($config_bd);

	$existe_dorsal = $conexion->prepare('
		SELECT * FROM jugadores WHERE dorsal = :dorsal_jugador AND id_equipo = :id_equipo
	');
										  
	$existe_dorsal->execute(array(
		':dorsal_jugador' => $dorsal_jugador,
		':id_equipo' => $id_equipo
	));
													
	$existe_dorsal = $existe_dorsal->fetchAll();
		
	if(count($existe_dorsal) == 0) {

		$existe_dni = $conexion->prepare('
			SELECT * FROM jugadores WHERE dni = :dni_jugador AND id_equipo = :id_equipo
		');
										  
		$existe_dni->execute(array(
			':dni_jugador' => $dni_jugador,
			':id_equipo' => $id_equipo
		));
		
		$existe_dni = $existe_dni->fetchAll();

		if (count($existe_dni) == 0) {
			$response = SFM::upload_file('file', '../assets/img/jugadores');
			
			if($response['status'] == 'success') {
		
				$consulta = $conexion->prepare('
					INSERT INTO jugadores(nombre, apellidos, dni, dorsal, id_equipo, foto) VALUES(:nombre, :apellidos, :dni, :dorsal, :id_equipo, :foto)'
				);
									
				$consulta->execute(array(
					':nombre' => $nombre_jugador,
					':apellidos' => $apellidos_jugador,
					':dni' => $dni_jugador,
					':dorsal' => $dorsal_jugador,
					':id_equipo' => $id_equipo,
					'foto' => $response['file_info']['name']
				));
				echo true;
			}
		} else {
			echo 'existe dni';
		}

	} else {
		echo false;
	}
}
?>