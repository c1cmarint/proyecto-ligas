<?php 
require '../assets/php/config.php';
require '../assets/php/functions.php';

$conexion = conectar_bd($config_bd);

$id = $_GET['id'];

$consulta = $conexion->prepare('
	DELETE FROM equipos WHERE id = :id
');
$consulta->execute(array(
	':id' => $id
));

header('Location: index.php');
?>