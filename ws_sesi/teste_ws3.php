<?php

	$request_headers=array();
    //$request_headers[]='Bearer: '.$access_token;
	$access_token = "6ef19107-94f8-3044-8ba1-39e28109d484";
    //$request_headers[]='Content-Length:150';
    $handle1=curl_init();
    $api_url='http://services.fiesc.com.br/pessoa/3.0.0/consultarPessoaJuridicaNacional';
	
	//$data = array("CNPJ" => "03.777.341/0026-14");
	$data = array("CNPJ" => "03777341002614");
	$data_string = json_encode($data);
	
	//var_dump($data_string);

	$ch = curl_init();
    curl_setopt_array(
        $handle1,
        array(
                CURLOPT_URL=>$api_url,
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"),
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string),
                //CURLOPT_POST=>false,
                CURLOPT_RETURNTRANSFER=>true,
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$access_token)),
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_HEADER=>true,
                //CURLOPT_TIMEOUT=>-1
				curl_setopt($ch, CURLOPT_TIMEOUT, 5),
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5)
        )
    );

    $data=curl_exec($handle1);
    echo serialize($data);
?>