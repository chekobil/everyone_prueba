<?php

	require_once('./lib.php');
	// datos de conexion a la BBDD, son propios de cada equipo
	$config_file = '../config/ddbb.php';
	$conn = get_connection_from_file($config_file);
	if($conn == false){
		echo "ERROR. no se ha posido conectar a la BBDD. exit.";
		exit;
	}


if($_POST['token'] == '12345'){
	// lista a todos los participantes y dame sus IDs
	$table = 'ev_registro';
	$field = 'id';
	$field2 = 'es_ganador';
	$sql = "SELECT $field FROM $table";
	$list = exec_sql($conn, $sql);

	// elige 1 ID al azar
	$res = array_rand($list, 1);
	$this_id = $list[$res][$field];
	// cambia el valor de es_ganador

	// UPDATE `ev_registro` SET `es_ganador` = '0' WHERE `ev_registro`.`id` = 2; 
	$sql = "UPDATE $table SET $field2 = 1 WHERE $field LIKE '$this_id'";
	$update = $conn->query($sql);
	if( $conn->affected_rows == 0 ){
		echo json_encode(false);
	}else{
		//echo json_encode($this_id);
		echo json_encode($this_id);	
	}
}else{
	echo json_encode(false);	
}