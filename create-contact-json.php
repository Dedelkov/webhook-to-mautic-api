<?php

/*******************************************************************************
  Captura informações de um POST de um webhook e envia para a API do Mautic
********************************************************************************
*
*   @property               Powertic
*   @autor                  Luiz Eduardo - luiz@powertic.com
*   @license                GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*   @mautic-min-version     2.10.0
*   @TODO: Tratar as exceções
*
*/

include __DIR__ . '/vendor/autoload.php';
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

session_start();

include "credentials.php";

// Conecta no objeto de autenticação através da BasicAuth
$initAuth = new ApiAuth();
$auth = $initAuth->newAuth($credentials, 'BasicAuth');

// Objeto do Mautic API
$api = new MauticApi();

// Nova instância do objeto Contact
$contactApi = $api->newApi('contacts', $auth, $mauticUrl);

$id = 0;

$request_body = file_get_contents('php://input');

$payload = json_decode($request_body);

$mautic_data = array();

// coloque todos os dados que você quer atualizar aqui
$mautic_data["email"]         =    $payload->Email;  // customize a variavel
$mautic_data["firstname"]     =    $payload->Nome;    // customize a variavel
$mautic_data["tags"] = explode(",", $payload->Tag);

// Permite criar um novo contato caso o contato especificado não seja encontrado
$createIfNotFound = true;

// Envia a requisição para o Mautic atualizar ou criar o contato
$contact = $contactApi->edit($id, $mautic_data, $createIfNotFound);

// finalizado
echo json_encode($contact);
