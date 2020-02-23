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