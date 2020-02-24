<?php
//permitir conexiones solo de ciertos sitios
require_once('./origin_allowed.php');

	require_once('./lib.php');
	// datos de conexion a la BBDD, son propios de cada equipo
	$config_file = '../config/ddbb.php';
	$conn = get_connection_from_file($config_file);
	if($conn == false){
		echo "ERROR. no se ha posido conectar a la BBDD. exit.";
		exit;
	}

$table = 'ev_registro';
$field = 'es_ganador';
$value = '1';

$sql = "SELECT * FROM $table WHERE $field LIKE '$value'";
$ganador = exec_sql($conn, $sql);
$conn->close();

echo json_encode($ganador);
// si no hay resultado es FALSE
// y si lo hay es un JSOn del que quiero el primer resultado