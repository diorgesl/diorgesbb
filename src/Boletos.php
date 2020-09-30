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
    protected $uri;
    protected $secrets = [];

    public function __construct(Client $client)
    {
        $token = Auth::obtainAccessToken();
        $this->client = $client;
        $this->token =  $token;
        $this->uri = !config('diorgesbb.production') ? 'https://api.hm.bb.com.br/cobrancas/v1/boletos' : 'https://api.bb.com.br/cobrancas/v1/boletos';
        $this->secrets = !config('diorgesbb.production') ? config('diorgesbb.api.homologa') : config('diorgesbb.api.producao');
    }

    public function boleto($id){
        $convenio = str_pad($this->secrets['numeroConvenio'], 10, "0", STR_PAD_LEFT);
        $boleto = str_pad($id, 10, "0", STR_PAD_LEFT);
        try {
            $res = $this->client->request('GET', $this->uri . '/' . $convenio . $boleto, [
                'query' => [
                    'gw-dev-app-key' => $this->secrets['developer_application_key'],
                    'numeroConvenio' => $this->secrets['numeroConvenio'],
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);

            return json_decode($res->getBody()->getContents());
        }catch (GuzzleException $e) {
            $this->__responseException($e);
        }
    }

    public function boletos($params = []) {
        $query = [
            'gw-dev-app-key' => $this->secrets['developer_application_key'],
            'agenciaBeneficiario' => $this->secrets['agenciaBeneficiario'],
            'contaBeneficiario' => $this->secrets['contaBeneficiario'],
            'indicadorSituacao' => 'A'
        ];

        $query = array_merge($query, $params);

        try {
            $request = $this->client->get($this->uri. '/', [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer '. $this->token
                ]
            ]);
            return json_decode($request->getBody()->getContents());
        }catch (GuzzleException $e) {
            $this->__responseException($e);
        }
    }

    public function registrar(Boleto $boleto)
    {
        try {
            $res = $this->client->request("POST", $this->uri, [
                "query" => [
                    "gw-dev-app-key" => $this->secrets["developer_application_key"],
                ],
                "body" => json_encode($boleto),
                "headers" => [
                    "Authorization" => "Bearer " . $this->token,
                    "Content-Type" => "application/json",
                ]
            ]);
            return json_decode($res->getBody()->getContents());
        }catch (GuzzleException $e){
            $this->__responseException($e);
        }
    }

    public function verificarRegisto(array $boletos)
    {

    }

    protected function __responseException(GuzzleException $e){
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        throw new \Exception($responseBodyAsString);
    }
}
