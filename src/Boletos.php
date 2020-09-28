<?php

namespace Diorgesl\DiorgesBB;

use Diorgesl\DiorgesBB\Auth;
use Diorgesl\DiorgesBB\Boleto;
use Illuminate\Support\Facades\Http;

class Boletos
{
    protected $token;

    public function __construct()
    {
        $token = Auth::obtainAccessToken();
        $this->token = $token;
    }

    public function boleto($id){
        $convenio = str_pad(config('bb.numeroConvenio'), 10, "0", STR_PAD_LEFT);
        $boleto = str_pad($id, 10, "0", STR_PAD_LEFT);
        $response = Http::withToken($this->token)->get('https://api.bb.com.br/cobrancas/v1/boletos/'.$convenio.$boleto, [
            'query' => [
                'gw-dev-app-key' => config('diorgesbb.developer_application_key'),
                'numeroConvenio' => config('diorgesbb.numeroConvenio'),
            ],
        ]);

        return $response->json();
    }

    public function boletos() {
        $response = Http::withToken($this->token)->get('https://api.bb.com.br/cobrancas/v1/boletos/', [
            'query' => [
                'gw-dev-app-key' => config('bb.developer_application_key'),
                'agenciaBeneficiario' => config('bb.agenciaBeneficiario'),
                'contaBeneficiario' => config('bb.contaBeneficiario'),
                'indicadorSituacao' => 'A'
            ],
        ]);

        return $response->json();
    }

    public function registrar($boleto) : Boleto
    {

    }

    public function verificarRegisto($boletos) : array
    {

    }
}
