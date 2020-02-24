<?php
//permitir conexiones solo de ciertos sitios
require_once('./origin_allowed.php');
/*
#### ERRORES
error_0, no existen datos POST (esto sirve de segunda verificacion del formulario)
error_1, no es posible conectar a la BBDD
*/

if (!isset($_POST['nombre'])){
	echo json_encode(array('error' => 'error_0'));
}else{
	require_once('./lib.php');
	// datos de conexion a la BBDD, son propios de cada equipo
	$config_file = '../config/ddbb.php';
	$conn = get_connection_from_file($config_file);
	if($conn == false){
		echo json_encode(array('error' => 'error_1'));
		exit;
	}else{
			// revisa los datos del form (undefined == vacio)
			$new_data = $_POST;
			# convierte la fecha de UNIXTIME a string
			$new_data['fecha'] = gmdate("d/m/Y", $new_data['fecha']);
			// LOS VALORES UNDEFINED no vienen en POST, OK, permito que sean nulos y me olvido
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
					$conn->close();
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
				    $conn->close();
				    #################
				    echo json_encode(array('success' => $msg));
				    #################
				} else {
					echo json_encode(array('error' => $conn->error));
				}
			}

	}
}
