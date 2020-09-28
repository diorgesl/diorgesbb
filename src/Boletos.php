<?php

namespace Diorgesl\DiorgesBB;

use Diorgesl\DiorgesBB\Auth;
use Diorgesl\DiorgesBB\Boleto;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class Boletos
{
    protected $token;
    protected $client;

    public function __construct(Client $client)
    {
        $token = Auth::obtainAccessToken();
        $this->client = $client;
        $this->token =  $token;
    }

    public function boleto($id){
        $convenio = str_pad(config('diorgesbb.numeroConvenio'), 10, "0", STR_PAD_LEFT);
        $boleto = str_pad($id, 10, "0", STR_PAD_LEFT);
        $res = $this->client->request('GET', 'https://api.bb.com.br/cobrancas/v1/boletos/'.$convenio.$boleto, [
            'query' => [
                'gw-dev-app-key' => config('diorgesbb.developer_application_key'),
                'numeroConvenio' => config('diorgesbb.numeroConvenio'),
            ],
            'headers' => [
                'Authorization' => 'Bearer '. $this->token
            ]
        ]);

        return json_decode($res->getBody()->getContents());
    }

    public function boletos() {
        $res = $this->client->request('GET', 'https://api.bb.com.br/cobrancas/v1/boletos', [
            'query' => [
                'gw-dev-app-key' => config('diorgesbb.developer_application_key'),
                'agenciaBeneficiario' => config('diorgesbb.agenciaBeneficiario'),
                'contaBeneficiario' => config('diorgesbb.contaBeneficiario'),
                'indicadorSituacao' => 'A'
            ],
            'headers' => [
                'Authorization' => 'Bearer '. $this->token
            ]
        ]);

        return json_decode($res->getBody()->getContents());
    }

    public function registrar(Boleto $boleto)
    {

    }

    public function verificarRegisto(array $boletos)
    {

    }
}
