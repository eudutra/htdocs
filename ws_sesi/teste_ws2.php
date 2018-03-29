<?php

$serverURL = "http://services.fiesc.com.br/pessoa/3.0.0";
/*
$token = auth();
if ($token === null)
   exit;
*/

$token['access_token'] = "766653e0-3b7c-3df5-a44b-3d4bdafbc1ee";

$cl = curl_init();
curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
/* uncomment the next line in case you don't have the required SSL certificates */
// curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);

/* show the token and add it to our future requests*/
//var_dump($token);
curl_setopt($cl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $token['access_token']));

/*
 * Get the API version
 */
curl_setopt($cl, CURLOPT_URL, "$serverURL/consultarPessoaJuridicaNacional/");
//curl_setopt($cl, CURLOPT_URL, "$serverURL/system/version");
$response = curl_exec($cl);
echo "<BR>";
print_r($response);
echo "<BR>";
echo PHP_EOL;

/*
 * Get list of aircraft templates
 */
 
 /*
curl_setopt($cl, CURLOPT_URL, "$serverURL/aircraft/templates");
$response = curl_exec($cl);
print_r($response);
*/

curl_close($cl);



/**
 * Get an authentication token
 */
function auth()
{
   global $serverURL;
   $cl = curl_init();
   curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($cl, CURLOPT_URL, "$serverURL/oauth2/token");
   curl_setopt($cl, CURLOPT_POST, true);
   /* uncomment this line if you don't have the required SSL certificates */
   // curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($cl, CURLOPT_POSTFIELDS, array(
     "grant_type" => "client_credentials",
     "client_id" => "my@email.address",
     "client_secret" => "my password"
   ));
   $auth_response = curl_exec($cl);
   if ($auth_response === false)
   {
      echo "Failed to authenticate\n";
      var_dump(curl_getinfo($cl));
      curl_close($cl);
      return NULL;
   }
   curl_close($cl);
   return json_decode($auth_response, true);
}
?>