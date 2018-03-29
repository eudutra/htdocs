<?php
/**
 * Genialnet
 * Autor: Anderson Dutra Moura
 * Data de Criação: 28/12/2017
 * Versão: 1.0
 */

// PARAMETROS
define('APP_CONSUMER_KEY', 'ym0jxbhY9eY4gewnxiGIcNeuKYAa');
define('APP_CONSUMER_SECRET','OzuKcRf27pzu113zzMGMazH0dc0a');
define('APP_URL_TOKEN', 'https://services.fiesc.com.br/token');

require_once("Rest.inc.php");

// CLASSE PRINCIPAL
class ProcessaResgataPessoas extends REST
{
    protected $msg=array();

	// MAIN
    function executar($param = null)
    {

        try {
            $filewsdl = 'marcelo.wolff--Pessoa3.0.0.wsdl';
            $access_token = $this->get_access_token();
            if(empty($access_token)){
                throw new Exception('Não gerou token.');
            }

            $opts = array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'header' => "Authorization: Bearer {$access_token}"
                )
            );

            $context = stream_context_create($opts);
            $params = array('stream_context' => $context, 'trace' => 1);
            $soapClient = new SoapClient($filewsdl, $params);

            $arguments = array(
                "cnpj" => $param
            );

            $dtenvio=time();
			$result = $soapClient->consultarPessoaJuridicaNacional($arguments);

			if (isset($result->pessoaJuridicaNacional)) {
				$error = NULL;
				$str_array = (array) $result->pessoaJuridicaNacional;
				$arr_res = array();
				$arr_res['error']  = $error;
				$arr_res['result'] = $str_array;
				$this->response($this->json($arr_res), 200);
			} else {
				$arr_res=array();
				$arr_res['error']  = array("msg" => "Person not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}

        } catch (Exception $e) {
			$arr_res=array();
			$arr_res['error']  = array("msg" => $e->getMessage());
			$arr_res['result'] = array('status' => "Failed");
			$this->response($this->json($arr_res), 400);
        }


    }

	// BUSCA TOKEN
    private function get_access_token()
    {

        //  http://services.fiesc.com.br/filiais/1.0.0
        $consumer_key = APP_CONSUMER_KEY;
        $consumer_secret = APP_CONSUMER_SECRET;
        $url_token = APP_URL_TOKEN;


        $url = $url_token;
        $pass = base64_encode("{$consumer_key}:{$consumer_secret}");
        $headers = array(
            "Authorization: Basic {$pass}"
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $data = curl_exec($ch);

        if (!$data) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        $data = json_decode($data);

        $access_token = $data->access_token;
        return $access_token;


    }

	//ENCODE ARRAY INTO JSON
	function json($data)
	{
		if (is_array($data)) {
			return json_encode($data);
		} else {
			return NULL;
		}
	}

}

	// KEY VALUE
	date_default_timezone_set('America/Sao_Paulo');
	$date_format = 'd-m-Y';
	$yesterday = strtotime('-1 day');
	$tomorrow = strtotime('+1 day');
	$key_yesterday = md5(date($date_format, $yesterday));
	$key_today = md5(date($date_format));
	$key_tomorrow = md5(date($date_format, $tomorrow));

	$key_array = array($key_yesterday, $key_today, $key_tomorrow);

	if (isset($_GET['key'])) {
		$key = $_GET['key'];
	} else {
		$key = null;
	}

	if (isset($_GET['cnpj'])) {
		$cnpj = $_GET['cnpj'];
	} else {
		$cnpj = null;
	}

	$obj = new ProcessaResgataPessoas();

	if (empty($key)) {
		$arr_res=array();
		$arr_res['error']  = array("msg" => "Key value not found");
		$arr_res['result'] = array('status' => "Failed");
		$obj->response($obj->json($arr_res), 400);
	} elseif (!in_array($key, $key_array)) {
		$arr_res=array();
		$arr_res['error']  = array("msg" => "Invalid Key");
		$arr_res['result'] = array('status' => "Failed");
		$obj->response($obj->json($arr_res), 400);
	} elseif (empty($cnpj)) {
		$arr_res=array();
		$arr_res['error']  = array("msg" => "CNPJ value not found");
		$arr_res['result'] = array('status' => "Failed");
		$obj->response($obj->json($arr_res), 400);
	} else {
		$obj->executar($cnpj);
	}
?>
