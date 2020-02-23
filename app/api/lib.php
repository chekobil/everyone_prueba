<?php

// intenta una conexion a base de datos
// OK: devuelve la propia conexion
// ERROR: devuelve el mensaje de error

function connect($conn){
	// Create connection
	$conn = new mysqli($conn['servername'], $conn['username'], $conn['password'], $conn['dbname']);
	// Check connection
	if ($conn->connect_error) {
	    return array('error' => $conn->connect_error);
	}else{
		return $conn;
	}
}

// a partir del archivo que contiene los datos de conexion
// devuelve la conexion a la BBDD o error si no es posible establecerla
function get_connection_from_file($config_file){
	if( !is_file($config_file) ){
		return false;
	}else{
		require_once($config_file);
		if( isset($ddbb_data) ){
			$conn = connect($ddbb_data);
			if( gettype($conn) == 'array' and isset($conn['error']) ){
				//echo json_encode(array('error' => $conn['error']));
				return false;	
			}else{
				return $conn;
			}
		}else{
			return false;
		}
	}
}


// ESCAPE tiene en cuenta la codificacion de caracteres de la conexion actual
// obtiene los VALUES, preparados para guardarlos
function get_values($conn, $data){
	$escaped_values = array_map($conn->real_escape_string, array_values($data));
	$values  = implode("', '", $escaped_values);
	$values = "'".$values."'";
	return $values;
}
// obtiene las KEYS, corresponden a las COLUMNAS de la tabla
function get_columns($data){
	$columns = implode(", ",array_keys($data));
	return $columns;
}

// ejecuta una query SQL y devuelve false si no hay resultado o devuelve los resultados
// probada con SELECT y funciona perfecta
function exec_sql($conn, $sql){
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		return false;
	}else {
        while($res = mysqli_fetch_assoc($result)) {
        	$list[] = $res;
        }
        return $list;
	}	
}
