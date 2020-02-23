<?php
/*
#### ERRORES
error_0, no existen datos POST (esto sirve de segunda verificacion del formulario)
error_1, no existe el archivo de configuracion de la BBDD 
error_2, no existen datos de la conexion a la bbdd
error_3, se intenta la conexion pero falla
*/

//$_POST = ['nombre' => 'John22', 'email' => 'joll225454@exmple.es', 'fecha' => '1211345678'];


if (!isset($_POST['nombre'])){
	echo json_encode(array('error' => 'error_0'));
}else{
	require_once('./lib.php');
	// datos de conexion a la BBDD, son propios de cada equipo
	$config_file = '../config/ddbb.php';
	if( !is_file($config_file) ){
		echo json_encode(array('error' => 'error_1'));
	}else{
		require_once($config_file);
		if( isset($ddbb_data) ){
			$conn = connect($ddbb_data);
			if( gettype($conn) == 'array' and isset($conn['error']) ){
				//echo json_encode(array('error' => $conn['error']));
				echo json_encode(array('error' => 'error_3'));	
			}else{
				// revisa los datos del form (undefined == vacio)
				$new_data = $_POST;
				// LOS VALORES UNDEFINED no vienen en POST, OK, permito que sean nulos y me olvido
				/*
				Array
				(
				    [nombre] => asfasdfasdf
				    [email] => felipe@cdigital.es
				    [fecha] => 1582467433390

				    [comunidades] => 3
				    [alta] => si
				    [gustaria] => no
				    [publicidad] => no
				    [acepto] => si
				)
				*/
		# el formulario envia LOS datos COMPLETOS, aqui separo los datos para cada tabla
				$keys_registro = ['nombre','email','fecha'];
				$data_registro = array_intersect_key($new_data, array_flip($keys_registro));
				$keys_cuestionario = ['comunidades','alta','gustaria', 'publicidad', 'acepto'];
				$data_cuestionario = array_intersect_key($new_data, array_flip($keys_cuestionario));

		# REGISTRO
				// guarda los datos de REGISTRO
				$table = 'ev_registro';
				$columns = get_columns($data_registro);
				$values = get_values($conn, $data_registro);
				// BUSCA duplicados
				$this_email = $data_registro['email'];
				$columna = 'email';
				$sql = "SELECT * FROM $table WHERE $columna LIKE '$this_email'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
				    echo json_encode(array('error' => 'email duplicado'));
				    exit;
				}else {
					$sql = "INSERT INTO $table ($columns) VALUES ($values)";
					if ($conn->query($sql) === TRUE) {
						$inserted_id = $conn->insert_id;
					    $msg = $conn->affected_rows." usuario registrado con ID ".$inserted_id;
					} else {
						echo json_encode(array('error' => $conn->error));
					}
				}

		# CUESTIONARIO
				if(isset($inserted_id)){
					// añado al formulario la ID del usuario que se acaba de registrar
					// guarda los datos del CUESTIONARIO
					$data_cuestionario['user_id'] = $inserted_id;
					$table = 'ev_cuestionario';
					$columns = get_columns($data_cuestionario);
					$values = get_values($conn, $data_cuestionario);
					#echo json_encode(array('success' => $msg));
					$sql = "INSERT INTO $table ($columns) VALUES ($values)";
					if ($conn->query($sql) === TRUE) {
						$inserted_id = $conn->insert_id;
					    $msg .= " - ".$conn->affected_rows." cuestionario guardado con ID ".$inserted_id;
					    #################
					    echo json_encode(array('success' => $msg));
					    #################
					} else {
						echo json_encode(array('error' => $conn->error));
					}
				}

			}
		}else{
			echo json_encode(array('error' => 'error_2'));	
		}
	}
    //echo json_encode(array('success' => 'datos ok'));  
}


/*
exit;
// INSERT DATA
$data = ['nombre' => 'John22', 'email' => 'jo22@exmple.es', 'fecha' => '1211345678'];

$columns = implode(", ",array_keys($data));
//$city = $mysqli->real_escape_string($city);
$escaped_values = array_map($conn->real_escape_string, array_values($data));
$values  = implode("','", $escaped_values);
$values = "'".$values."'";

	$this_email = $data['email'];
	$sql = "SELECT * FROM $table WHERE email LIKE '$this_email'";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo "ERROR. Este email ya está registrado: ".$this_email;
	    }
	} else {
		$sql = "INSERT INTO $table ($columns) VALUES ($values)";
		if ($conn->query($sql) === TRUE) {
		    echo "(".$conn->affected_rows.") Registro guardado correctamente con ID: ";
		    echo $conn->insert_id;
		} else {
		    echo "ERROR: " . $sql . "<br>" . $conn->error;
		}
	}



$conn->close();


?>
*/