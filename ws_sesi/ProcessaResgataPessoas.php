<?php
/**
 * Created by PhpStorm.
 * User: claudia
 * Date: 22/07/17
 * Time: 15:52
 */
// php cmd2.php  "class=ProcessaResgataFiliais&method=executar"

/**
 * Dados p/ Serviço de resgatar filiais
 *
 */
define('APP_CONSUMER_KEY', 'ym0jxbhY9eY4gewnxiGIcNeuKYAa');
define('APP_CONSUMER_SECRET','OzuKcRf27pzu113zzMGMazH0dc0a');
define('APP_URL_TOKEN', 'https://services.fiesc.com.br/token');

Class ProcessaResgataPessoas
{
    /**
     * Mensagens , notificações ref. processamento.
     * @var array
     */
    protected $msg=array();


    function executar($param = null)
    {

        try {
            $filewsdl = 'marcelo.wolff--Pessoa3.0.0.wsdl';
            //$cnpjEmpresa = '03.777.341/0026-14';
			$cnpjEmpresa = '03.777.341/0001-66';
            $access_token = $this->get_access_token();
            if(empty($access_token)){
                throw new Exception('Não gerou token .');
            }


            $opts = array(
                'http' => array(
                    'protocol_version' => 1.1,
                    //  'header' => "Authorization: Bearer {$access_token}"
                    'header' => "Authorization: Bearer {$access_token}"
                )
            );

            $context = stream_context_create($opts);
            $params = array('stream_context' => $context, 'trace' => 1);
            $soapClient = new SoapClient($filewsdl, $params);

            $arguments = array(
				"cnpj" => $cnpjEmpresa
				//"cnpjEmpresa" => $cnpjEmpresa,
                //"cnpjEmpresa" => '',
                //"cnpjFilial" => '',
                //"busca" => ''
            );

            $dtenvio=time();
            $result = $soapClient->consultarPessoaJuridicaNacional($arguments);

            echo 'Response: ';
            print_r($result);

            //var_dump($soapClient->__getFunctions());
            //var_dump($soapClient->__getTypes());
            //echo ' veja ResponseHeaders: ';
            //var_dump($soapClient->__getLastResponseHeaders());

			/*
            file_put_contents(APP_ROOT_PATH.'/app/output/ResponseHeader.txt',$soapClient->__getLastResponseHeaders());
            file_put_contents(APP_ROOT_PATH.'/app/output/Requestheaders.txt',$soapClient->__getLastRequestHeaders());
            file_put_contents(APP_ROOT_PATH.'/app/output/Request.txt',$soapClient->__getLastRequest());
            file_put_contents(APP_ROOT_PATH.'/app/output/Response.txt',$soapClient->__getLastResponse());


            $adados = $result->Filial;
            file_put_contents(APP_ROOT_PATH.'/app/output/Response_dados.txt',var_export($adados,true));


            if (empty($adados)) {
                throw new Exception('Não retornou Dados !');
            }

            $objuni = new Uni();
            $atualizados = 0;
            $inc = 0;



            foreach ($adados as $item => $objfil) {
                try{
                    $retorno=$objuni->atualiza_registro($objfil,$this->msg);

                    $inc+=$retorno['inc'];
                    $atualizados+=$retorno['alt'];


                    $this->atualiza_log($soapClient,$dtenvio,$retorno['descri'],$retorno['id'],1,$objfil,' OK  UNI_CD:'.$retorno['id']);

                }catch (Exception $e){
                     // file_put_contents("/tmp/obj_{$item}.txt",var_export($objfil,true));
                    if($item==33){
                        print_r($objfil);
                        echo " pais :{$objfil->pais}  cnpj: $objfil->cnpjFilial ";
                        exit();
                    }
                    $this->msg[]="Erro no item {$item}  Cnpj: {$objfil->cnpjFilial} :".$e->getMessage();
                    $this->atualiza_log($soapClient,$dtenvio,$objfil->nomeFilial,0 ,9,$objfil,$e->getMessage() );
                    //$this->atualiza_log($soapClient,$dtenvio,$objfil->nomeFilial,0 ,9,$objfil );


                }

              //  break;

            }
            $this->msg[] = 'Classe:' . __CLASS__ . " Registros Inseridos:  $inc.  Atualizados: $atualizados ";
            $msg=implode("\n",$this->msg);

            rclib_salva_log(APP_LOG_ERROS, $msg);
            echo $msg;

            TTransaction::close();
			
			*/
			
        } catch (Exception $e) {

            // \Adianti\Database\TTransaction::rollback();
            $erro = 'Classe:' . __CLASS__ . ' erro: ' . $e->getMessage();
            // rclib_salva_log(APP_LOG_ERROS, $erro);
            echo $erro;

        }


    }


    function get_access_token()
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

        //print_r($data);
        // Exemplo
        //stdClass Object ( [access_token] => 0dbbb0b2-43f2-3afb-a2b6-67476a9155b6 [scope] => am_application_scope default [token_type] => Bearer [expires_in] => 3238 )

        $access_token = $data->access_token;
        return $access_token;


    }


    /**
     * ws.ID     = idLog;
    ws.DT_ENVIO      = dataInicio;
    ws.DT_RETORNO = DateTime.Now;
    ws.WSTI_ID      IDWEBS_UNIDADE).FirstOrDefault().Id;
    = _logController.CarregarTipoIntegracao(LogTiposIntegracao.

    ws.WSTI_ID = 32 (FIXO 32) isso para o caso de unidade, quando por
    exemplo for fazer o de pessoas você tem que entrar em contato com a gente para
    fornecermos novo ID.

     * ws.WS_DESCRICAO = unidade.Nome;
    ws.OBJ_ID     = idUnidade;
    ws.XML_ENVIO  = xmlEnvio;
    ws.XML_RETORNO = xmlRetorno;
    ws.INNER_XML   = innerXml;
    O que gravar aqui ?
    Neste campo innerXml deveria ser gravado o que efetivamente chegou no
    WS ou seja o JSON ou XML no caso do SOAP
    No caso de um serviço por exemplo que você esta enviando para um WS
    deveria ser gravado JSON ou XML que você está enviando
    Isso é feito para que o suporte possa facilmente enviar a comunicação
    para analise caso sejam questionados referente a erros.
    Pode não ser gravado nada tb pois o campo aceita nulos. A gente usa pra
    facilitar nosso suporte.

    ws.WS_STATUS     = (int)integrado;
    O que gravar aqui ?
    Para este caso temos esta enum
     * public enum IntegracaoStatus
    {
    Pendente = 0,
    Integrado = 1,
    [Description("Erro de Integração")]
    ErroIntegracao = 9
    }
    dbLog.WS_LOGINTEGRACAO.Add(ws);
     *
     *
     *
     */

    /**
     *
     *
     *
     *
     * @param soapclient $soapClient
     * @param int  $dtenvio  data em unixtime qdo. enviou xml ao webservice (inicio)
     * @param string  $descri  nome da unidade
     * @param int  $id   id da unidade
     * @param int $status   0 - pendente, 1- integrado ,  9 - Erro de integração
     * @param string $msgadd   mensagem adicional, geralmente se houver erro
     *
     */
    function atualiza_log($soapClient, $dtenvio,$descri='',$id='',$status,$objfil,$msgadd=''){
        //$ln="\r";
        $ln="  ";

        $objid='00000000-0000-0000-0000-000000000000';

        if(empty($descri)){
            $descri='Não definido';
        }else{
            //$descri=addslashes($descri);
            $descri=str_replace("'" ,"''",$descri);
        }

        $dtenvio=$this->calc_date($dtenvio);
        $dtretorno=$this->calc_date();
        $wsti_id=32;
        //$xml_envio="Request Headers:".trim($soapClient->__getLastRequestHeaders())." {$ln}Request:". $this->XmlToString($soapClient->__getLastRequest());
        //$xml_retorno="Response Headers:".trim($soapClient->__getLastResponseHeaders())."{$ln}Response:". $this->XmlToString($soapClient->__getLastResponse());

       // $xml_envio=$soapClient->__getLastRequest();
        //$xml_retorno=$soapClient->__getLastResponse();

        $xml_envio='.';
        $xml_retorno='.';

        $inner_xml=str_replace(array("\n","'"), array(' ',''),var_export($objfil,true));

        if(!empty($msgadd)){
            $inner_xml.="{$ln}Mensagem: ".addslashes($msgadd);
        }

/*
        $objlog=new WsLogintegracao();
        $objlog->DT_ENVIO=$dtenvio;
        $objlog->DT_RETORNO=$dtretorno;
        $objlog->WSTI_ID=$wsti_id;
        $objlog->WS_DESCRICAO=$descri;
        $objlog->OBJ_ID=$id;
        $objlog->XML_ENVIO=$xml_envio;
        $objlog->XML_RETORNO=$xml_retorno;
        $objlog->WS_STATUS=$status;
        $objlog->INNER_XML=$inner_xml;
        $objlog->store();

*/

        $query="INSERT INTO WS_LOGINTEGRACAO (ID, DT_ENVIO, DT_RETORNO, WSTI_ID, WS_DESCRICAO, OBJ_ID, XML_ENVIO, XML_RETORNO, WS_STATUS,INNER_XML) 
              values (NEWID(),'{$dtenvio}','{$dtretorno}','{$wsti_id}','{$descri}','{$objid}','{$xml_envio}','{$xml_retorno}',{$status},'{$inner_xml}' )  ";

        if ($conn = TTransaction::get()) {
            // register the operation in the LOG file
            TTransaction::log($query);
            $result = $conn->query($query);
        }else{
            throw new Exception(AdiantiCoreTranslator::translate('No active transactions') . ': ' . __METHOD__ .' '. $this->getEntity());
        }



        }


    function XmlToString($xmlrec){
        //var_dump($xmlrec);
        //$xml = new SimpleXMLElement($xmlrec);
        //return addslashes($xml->__toString());

        //return serialize($xmlrec);



        return $xmlrec;


    }



    function calc_date($time=0){
        if(!$time){
            $time=time();
        }

        if(APP_SQLSEVER_LANGID==27){
            return date('d-m-Y H:i:s',$time);
        }else{
            return date('Y-m-d H:i:s',$time);
        }

    }



}

$obj = new ProcessaResgataPessoas();
$obj->executar();

?>
