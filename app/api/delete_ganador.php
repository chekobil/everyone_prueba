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
	$field = 'id';
	$field2 = 'es_ganador';
	// cambia el valor de es_ganador a 0
	// UPDATE `ev_registro` SET `es_ganador` = '0' WHERE `ev_registro`.`id` = 2; 
	$sql = "UPDATE $table SET $field2 = 0 WHERE $field2 = 1";
	$update = $conn->query($sql);
	if( $conn->affected_rows == 0 ){
		echo json_encode(false);
	}else{
		echo json_encode(array('msg'=>'ganador eliminado'));	
	}
