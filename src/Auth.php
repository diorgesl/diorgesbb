<?php

namespace Diorgesl\DiorgesBB;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Auth
{
    public static function obtainAccessToken() {
        $client = new Client();
        $basic = config('diorgesbb.basic');
        $formaBasic = base64_encode(config('diorgesbb.client_id'). ':' .config('diorgesbb.client_secret'));
        if($basic != $formaBasic) {
            $basic = $formaBasic;
        }

        $res = $client->request('POST', 'https://oauth.bb.com.br/oauth/token?grant_type=client_credentials&scope=cobrancas.boletos-info cobrancas.boletos-requisicao', [
            'headers' => [
                'Authorization' => 'Basic '. $basic,
                'Accept'     => 'application/json',
            ]
        ]);
        $access_token = json_decode($res->getBody()->getContents())->access_token;

        return $access_token;

    }
}
