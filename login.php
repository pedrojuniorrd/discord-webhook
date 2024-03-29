<?php

require('conn.php');
require __DIR__ . '/vendor/autoload.php';

use \Curl\Curl;

$conf = new \stdClass();
$curl = new Curl();
$curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');

$response = $curl->post(
    'https://discord.com/api/oauth2/token',
    array(
            'grant_type' => 'authorization_code',
            'client_id' => '{BOT CLIENT ID}',
            'client_secret' => '{BOT CLIENT SECRET}',
            'redirect_uri' => '{REDIRECT URI}',
            'code' => $_GET["code"],
        )
);
echo "<pre>";
$response = json_decode(json_encode($curl->response),true);
// print_r ($response["access_token"]);

$t_access = json_decode(json_encode($curl->response->access_token), true);
$t_refresh = json_decode(json_encode($curl->response->refresh_token), true);
$wh_id = json_decode(json_encode($curl->response->webhook->id), true);
$wh_ch_id = json_decode(json_encode($curl->response->webhook->channel_id), true);
$wh_name = json_decode(json_encode($curl->response->webhook->name), true);
$wh_token = json_decode(json_encode($curl->response->webhook->token), true);
$ep_url = json_decode(json_encode($curl->response->webhook->url), true);
$t_type = json_decode(json_encode($curl->response->token_type), true);
$wh_servidor = json_decode(json_encode($curl->response->webhook->guild_id), true);
$expire_t = json_decode(json_encode($curl->response->expires_in), true);
$time = time() + $expire_t;

$stmt = $conn->prepare("SELECT canal FROM webhook_dados WHERE canal='$wh_ch_id'");
$stmt->execute(['$wh_ch_id']);
$canal = $stmt->fetch();
if ($canal) {
    $curl1 = new Curl();
    $response = $curl1->delete('https://discord.com/api/webhooks/'.$wh_id.'/'.$wh_token);
    print_r($response);
    $canal=null;
    $conn=null;
} else {
    $sql = "INSERT INTO webhook_dados(webhook_id,webhook_name,webhook_token,url_endpoint,canal,servidor,token_acesso,expires_in,token_refresh,token_type)VALUES ('$wh_id','$wh_name','$wh_token','$ep_url','$wh_ch_id','$wh_servidor','$t_access', '$time','$t_refresh','$t_type')";
    
    
    $conn->query($sql);
}

header("Location: https://www.wowhelp.com.br/");
