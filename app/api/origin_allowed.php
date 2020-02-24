<?php

	$origin_allowed = array(
		"http://cdigital.es",
		"https://cdigital.es",		
		"cdigital.es",
		"http://www.cdigital.es",
		"https://www.cdigital.es",
		"http://localhost"
	);


	// https://fritsvancampen.wordpress.com/2013/02/03/cross-site-origin-requests-aka-cross-origin-resource-sharing/
	//$calling_domain = $_SERVER['HTTP_ORIGIN'];

	if (isset($_SERVER['HTTP_ORIGIN'])) {
  		$calling_domain = $_SERVER['HTTP_ORIGIN'];
	}
	elseif (isset($_SERVER['HTTP_HOST'])) {
  		$calling_domain = $_SERVER['HTTP_HOST'];
	}


	if( isset($calling_domain) ){
		if (in_array($calling_domain, $origin_allowed)) {
		    header("Access-Control-Allow-Origin: " . $calling_domain);
		    //header("Access-Control-Allow-Headers: x-requested-with");
		}else{
			// RESPONSE
			//echo "No tienes permisos para ver esto";
			echo json_encode(['result'=>0, 'msg'=>'origin not allowed: '.$calling_domain]);
		    exit;
		}
	}else{
		echo json_encode(['result'=>0, 'msg'=>'origin unknown ?']);
		exit;
	}

?>