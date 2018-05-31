<?php
require '../assets/php/config.php';
require '../assets/php/functions.php';

$conexion = conectar_bd($config_bd);
$id_equipo = $_POST['id_equipo'];

$consulta = $conexion->prepare('
	SELECT * FROM jugadores WHERE id_equipo = :id_equipo
');
$consulta->execute(array(
	':id_equipo' => $id_equipo
));

$prueba = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($prueba, JSON_FORCE_OBJECT);
?>