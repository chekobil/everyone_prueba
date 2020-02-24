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

$table1 = 'ev_registro';
$table2 = 'ev_cuestionario';
$field1 = 'id';
$field2 = 'user_id';
$sql = "SELECT * FROM $table1 LEFT JOIN $table2 ON $table1.$field1 = $table2.$field2";
$list = exec_sql($conn, $sql);
echo json_encode($list);
// es false si NO hay resultados
