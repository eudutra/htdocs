<?php
	//require_once("lib/nusoap.php");
	
	$authorization = "Authorization: Bearer d40e42e2-9af9-38df-8186-0f59172a1ae3";
	
	$wsdl  = "http://services.fiesc.com.br/pessoa/3.0.0"; 

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $wsdl); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_VERBOSE, '1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result);

?>