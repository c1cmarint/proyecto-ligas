<?php

function conectar_bd($config_bd) {
  try {
    $conexion = new PDO('mysql:host=localhost; dbname=' . $config_bd['nombre_bd'], $config_bd['usuario_bd'], $config_bd['password_bd']);
    return $conexion;
  } catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

function comprobarAdmin($conexion, $usuario) {
	$consulta = $conexion->prepare('
    	SELECT * FROM admins WHERE usuario = :usuario
  	');
  	$consulta->execute(array(
		':usuario' => $usuario
	));
  	return $consulta->fetchAll();
}

function obtener_ligas($conexion) {
  $consulta = $conexion->prepare('
    SELECT * FROM ligas ORDER BY nombre
  ');
  $consulta->execute();
  return $consulta->fetchAll();
}

function obtener_liga($conexion,$id) {
	$consulta = $conexion->prepare('
		SELECT * FROM ligas WHERE id = :id
	');
	$consulta->execute(array(
		':id' => $id
	));
	return $consulta->fetchAll();
}

function obtener_todos_equipos($conexion) {
	$consulta = $conexion->prepare('
	  SELECT * FROM equipos
	');
	$consulta->execute();
	return $consulta->fetchAll();
}

function obtener_equipos($conexion,$id_liga) {
	$consulta = $conexion->prepare('
	  SELECT * FROM equipos WHERE id_liga = :id_liga ORDER BY puntos DESC
	');
	$consulta->execute(array(
		':id_liga' => $id_liga
	));
	return $consulta->fetchAll();
}

function obtener_equipo($conexion,$id_equipo) {
	$consulta = $conexion->prepare('
	  SELECT * FROM equipos WHERE id = :id_equipo
	');
	$consulta->execute(array(
		':id_equipo' => $id_equipo
	));
	return $consulta->fetchAll();
}

function obtener_jugadores_equipo($conexion,$id_equipo) {
	$consulta = $conexion->prepare('
	  SELECT * FROM jugadores WHERE id_equipo = :id_equipo ORDER BY dorsal
	');
	$consulta->execute(array(
		':id_equipo' => $id_equipo
	));
	return $consulta->fetchAll();
}

function obtener_jugador($conexion,$id_jugador) {
	$consulta = $conexion->prepare('
	  SELECT * FROM jugadores WHERE id = :id_jugador
	');
	$consulta->execute(array(
		':id_jugador' => $id_jugador
	));
	return $consulta->fetchAll();
}

function obtener_nums_jornadas($conexion,$id_liga) {
	$consulta = $conexion->prepare('
	  SELECT num_jornada FROM jornadas WHERE id_liga = :id_liga
	');
	$consulta->execute(array(
		':id_liga' => $id_liga
	));
	return $consulta->fetchAll();
}

function obtener_partidos($conexion,$id_liga,$jornada) {
	$consulta = $conexion->prepare('
	  SELECT DISTINCT * FROM jornadas INNER JOIN partidos ON jornadas.id_liga = partidos.id_liga WHERE jornadas.num_jornada = partidos.id_jornada AND jornadas.id_liga = :id_liga AND partidos.id_jornada = :jornada
	');
	$consulta->execute(array(
		':id_liga' => $id_liga,
		':jornada' => $jornada
	));
	return $consulta->fetchAll();
}

function obtener_partido($conexion,$id_partido) {
	$consulta = $conexion->prepare('
	  SELECT * FROM partidos WHERE id = :id_partido
	');
	$consulta->execute(array(
		':id_partido' => $id_partido
	));
	return $consulta->fetchAll();
}

function obtener_goles_jugador($conexion,$id_jugador) {
	$consulta = $conexion->prepare('
	  SELECT count(id_jugador) FROM goles WHERE id_jugador = :id_jugador
	');
	$consulta->execute(array(
		':id_jugador' => $id_jugador
	));
	return $consulta->fetchAll();
}

function obtener_goles_equipo($conexion,$id_equipo) {
	$consulta = $conexion->prepare('
	  SELECT count(id_equipo) FROM goles WHERE id_equipo = :id_equipo
	');
	$consulta->execute(array(
		':id_equipo' => $id_equipo
	));
	return $consulta->fetchAll();
}

function obtener_amarillas_jugador($conexion,$id_jugador) {
	$consulta = $conexion->prepare('
	  SELECT count(id_jugador) FROM tarjetas WHERE id_jugador = :id_jugador AND color = "amarilla"
	');
	$consulta->execute(array(
		':id_jugador' => $id_jugador
	));
	return $consulta->fetchAll();
}

function obtener_rojas_jugador($conexion,$id_jugador) {
	$consulta = $conexion->prepare('
	  SELECT count(id_jugador) FROM tarjetas WHERE id_jugador = :id_jugador AND color = "roja"
	');
	$consulta->execute(array(
		':id_jugador' => $id_jugador
	));
	return $consulta->fetchAll();
}

function obtener_goles_partido($conexion,$id_partido) {
	$consulta = $conexion->prepare('
	  SELECT * FROM goles WHERE id_partido = :id_partido
	');
	$consulta->execute(array(
		':id_partido' => $id_partido
	));
	return $consulta->fetchAll();
}

function obtener_tarjetas_partido($conexion,$id_partido) {
	$consulta = $conexion->prepare('
	  SELECT * FROM tarjetas WHERE id_partido = :id_partido
	');
	$consulta->execute(array(
		':id_partido' => $id_partido
	));
	return $consulta->fetchAll();
}

function obtener_max_goleadores($conexion,$id_liga) {
	$consulta = $conexion->prepare('
		SELECT jugadores.id, jugadores.nombre, jugadores.apellidos, (SELECT COUNT(*) FROM goles goles WHERE goles.id_jugador = jugadores.id AND goles.id_liga = :id_liga)
		FROM jugadores jugadores ORDER BY (SELECT COUNT(*) FROM goles goles WHERE goles.id_jugador = jugadores.id AND goles.id_liga = :id_liga) DESC LIMIT 10
	');
	$consulta->execute(array(
		':id_liga' => $id_liga
	));
	return $consulta->fetchAll();
}

function obtener_max_amarillas($conexion,$id_liga) {
	$consulta = $conexion->prepare('
		SELECT jugadores.id, jugadores.nombre, jugadores.apellidos, (SELECT COUNT(*) FROM tarjetas tarjetas WHERE tarjetas.id_jugador = jugadores.id AND tarjetas.id_liga = :id_liga AND color = "amarilla")
		FROM jugadores jugadores ORDER BY (SELECT COUNT(*) FROM tarjetas tarjetas WHERE tarjetas.id_jugador = jugadores.id AND tarjetas.id_liga = :id_liga) DESC LIMIT 10
	');
	$consulta->execute(array(
		':id_liga' => $id_liga
	));
	return $consulta->fetchAll();
}

function obtener_max_rojas($conexion,$id_liga) {
	$consulta = $conexion->prepare('
		SELECT jugadores.id, jugadores.nombre, jugadores.apellidos, (SELECT COUNT(*) FROM tarjetas tarjetas WHERE tarjetas.id_jugador = jugadores.id AND tarjetas.id_liga = :id_liga AND color = "roja")
		FROM jugadores jugadores ORDER BY (SELECT COUNT(*) FROM tarjetas tarjetas WHERE tarjetas.id_jugador = jugadores.id AND tarjetas.id_liga = :id_liga) DESC LIMIT 10
	');
	$consulta->execute(array(
		':id_liga' => $id_liga
	));
	return $consulta->fetchAll();
}
?>
