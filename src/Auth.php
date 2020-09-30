<?php

namespace Diorgesl\DiorgesBB;

use GuzzleHttp\Client;

class Auth
{
    public static function obtainAccessToken()
    {
        $client = new Client();
        $uri = ! config('diorgesbb.production') ? 'https://oauth.hm.bb.com.br' : 'https://oauth.bb.com.br';
        $secrets = ! config('diorgesbb.production') ? config('diorgesbb.api.homologa') : config('diorgesbb.api.producao');

        $basic = $secrets['basic'];
        $formaBasic = base64_encode($secrets['client_id'].':'.$secrets['client_secret']);
        if ($basic != $formaBasic) {
            $basic = $formaBasic;
        }

        $res = $client->request('POST', $uri.'/oauth/token?grant_type=client_credentials&scope=cobrancas.boletos-info cobrancas.boletos-requisicao', [
            'headers' => [
                'Authorization' => 'Basic '.$basic,
                'Accept'     => 'application/json',

            ],
        ]);
        $access_token = json_decode($res->getBody()->getContents())->access_token;

        return $access_token;
    }
}
