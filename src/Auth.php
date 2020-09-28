<?php

namespace Diorgesl\DiorgesBB;

use Illuminate\Support\Facades\Http;

class Auth
{
    public static function obtainAccessToken() {
        $basic = config('diorgesbb.basic');
        $formaBasic = base64_encode(config('diorgesbb.client_id'). ':' .config('diorgesbb.cliente_secret'));
        if($basic != $formaBasic) {
            $basic = $formaBasic;
        }
        $res = Http::withToken($formaBasic, 'Basic')->post('https://oauth.bb.com.br/oauth/token?grant_type=client_credentials&scope=cobrancas.boletos-info cobrancas.boletos-requisicao');
        return $res->json()->access_token;
    }
}
