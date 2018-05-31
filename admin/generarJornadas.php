<?php
require '../assets/php/config.php';
require '../assets/php/functions.php';

function generarJornadas( array $teams ) {

    if (count($teams)%2 != 0) {
        array_push($teams,"Descansa");
    }
    $visitantes = array_splice($teams,(count($teams)/2));
    $local = $teams;
    for ($i=0; $i < count($local)+count($visitantes)-1; $i++) {
        for ($j=0; $j<count($local); $j++) {
            $jornadas[$i][$j]["local"]=$local[$j];
            $jornadas[$i][$j]["visitantes"]=$visitantes[$j];
        }
        if(count($local)+count($visitantes)-1 > 2) {
            $s = array_splice($local,1,1);
            $slice = array_shift($s);
            array_unshift($visitantes,$slice);
            array_push($local, array_pop($visitantes));
        }
    }
    return $jornadas;
}

$conexion = conectar_bd($config_bd);

$id = $_GET['id'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

$fecha_completa = $fecha . ' ' . $hora;

$fecha_completa = date('d-m-Y H:i', strtotime($fecha_completa . '- 1 week - 1 hours'));

echo 'Fecha menos: ' . $fecha_completa;

$equipos = obtener_equipos($conexion,$id);

$equipos1 = [];

foreach ($equipos as $equipo) {
	array_push($equipos1,$equipo['id']);
}

$jornadas = generarJornadas($equipos1);

foreach ($jornadas as $jornada => $partidos) {
	$fecha_completa = date('d-m-Y H:i', strtotime($fecha_completa . '+ 1 week'));
	$insertarJornada = $conexion->prepare('
    	INSERT INTO jornadas (num_jornada,id_liga) VALUES (:num_jornada,:id_liga)
  	');

	$insertarJornada->execute(array(
		':num_jornada' => $jornada+1,
		':id_liga' => $id
	));
	$fecha_completa_buffer = $fecha_completa;
	foreach ($partidos as $partido) {
		$fecha_completa_buffer = date('d-m-Y H:i', strtotime($fecha_completa_buffer . '+ 1 hour'));
		echo '<p>' . $fecha_completa_buffer . '</p>';
		$insertarPartido = $conexion->prepare('
			INSERT INTO partidos (equipo_l,equipo_v,id_jornada,id_liga,fecha) VALUES (:equipo_l,:equipo_v,:id_jornada,:id_liga,:fecha)
		');

		$insertarPartido->execute(array(
			':equipo_l' => $partido['local'],
			':equipo_v' => $partido['visitantes'],
			':id_jornada' => $jornada+1,
			':id_liga' => $id,
			':fecha' => $fecha_completa_buffer
		));
	}
}

header('Location: liga-admin.php?id=' . $id);
?>