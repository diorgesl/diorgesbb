<?php

namespace Diorgesl\DiorgesBB;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Boletos
{
    /*
     * @var string
     *
     * Carrega o Access Token de Autenticação com a API
     */
    protected $token;

    /*
     * @var Guzzle\Client
     */
    protected $client;

    /*
     * @var string
     *
     * Carrega a URL da API
     */
    protected $uri;

    /*
     * @var array
     *
     * Carrega as credenciais da API
     */
    protected $secrets = [];

    /**
     * Boletos constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $token = Auth::obtainAccessToken();
        $this->client = $client;
        $this->token = $token;
        $this->uri = ! config('diorgesbb.production') ? 'https://api.hm.bb.com.br/cobrancas/v1/boletos' : 'https://api.bb.com.br/cobrancas/v1/boletos';
        $this->secrets = ! config('diorgesbb.production') ? config('diorgesbb.api.homologa') : config('diorgesbb.api.producao');
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function boleto($id)
    {
        $convenio = str_pad($this->secrets['numeroConvenio'], 10, '0', STR_PAD_LEFT);
        $boleto = str_pad($id, 10, '0', STR_PAD_LEFT);
        try {
            $res = $this->client->request('GET', $this->uri.'/'.$convenio.$boleto, [
                'query' => [
                    'gw-dev-app-key' => $this->secrets['developer_application_key'],
                    'numeroConvenio' => $this->secrets['numeroConvenio'],
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                ],
            ]);

            return json_decode($res->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->__responseException($e);
        }
    }

    /**
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function boletos($params = [])
    {
        $query = [
            'gw-dev-app-key' => $this->secrets['developer_application_key'],
            'agenciaBeneficiario' => $this->secrets['agenciaBeneficiario'],
            'contaBeneficiario' => $this->secrets['contaBeneficiario'],
            'indicadorSituacao' => 'A',
        ];

        $query = array_merge($query, $params);

        try {
            $request = $this->client->get($this->uri.'/', [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                ],
            ]);

            return json_decode($request->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->__responseException($e);
        }
    }

    /**
     * @param Boleto $boleto
     * @return string
     * @throws \Exception
     */
    public function registrar(Boleto $boleto)
    {
        try {
            $res = $this->client->request('POST', $this->uri, [
                'query' => [
                    'gw-dev-app-key' => $this->secrets['developer_application_key'],
                ],
                'body' => json_encode($boleto),
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($res->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->__responseException($e);
        }
    }

    public function verificarRegisto(array $boletos)
    {
    }

    public function linhaDigitavel($codigoBarras)
    {
        $codigo = $codigoBarras;

        $s1 = substr($codigo, 0, 4).substr($codigo, 19, 5);
        $s1 = $s1.$this->__modulo10($s1);
        $s1 = substr_replace($s1, '.', 5, 0);

        $s2 = substr($codigo, 24, 10);
        $s2 = $s2.$this->__modulo10($s2);
        $s2 = substr_replace($s2, '.', 5, 0);

        $s3 = substr($codigo, 34, 10);
        $s3 = $s3.$this->__modulo10($s3);
        $s3 = substr_replace($s3, '.', 5, 0);

        $s4 = substr($codigo, 4, 1);

        $s5 = substr($codigo, 5, 14);

        return sprintf('%s %s %s %s %s', $s1, $s2, $s3, $s4, $s5);
    }

    /**
     * @param     $n
     * @param int $factor
     * @param int $base
     * @param int $x10
     * @param int $resto10
     *
     * @return int
     */
    public function __modulo11($n, $factor = 2, $base = 9, $x10 = 0, $resto10 = 0)
    {
        $sum = 0;
        for ($i = mb_strlen($n); $i > 0; $i--) {
            $sum += ((int) mb_substr($n, $i - 1, 1)) * $factor;
            if ($factor == $base) {
                $factor = 1;
            }
            $factor++;
        }

        if ($x10 == 0) {
            $sum *= 10;
            $digito = $sum % 11;
            if ($digito == 10) {
                $digito = $resto10;
            }

            return $digito;
        }

        return $sum % 11;
    }

    /**
     * @param $n
     *
     * @return int
     */
    public function __modulo10($n)
    {
        $chars = array_reverse(str_split($n, 1));
        $odd = array_intersect_key($chars, array_fill_keys(range(1, count($chars), 2), null));
        $even = array_intersect_key($chars, array_fill_keys(range(0, count($chars), 2), null));
        $even = array_map(
            function ($n) {
                return ($n >= 5) ? 2 * $n - 9 : 2 * $n;
            }, $even
        );
        $total = array_sum($odd) + array_sum($even);

        return ((floor($total / 10) + 1) * 10 - $total) % 10;
    }

    /**
     * @param GuzzleException $e
     * @throws \Exception
     */
    protected function __responseException(GuzzleException $e)
    {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        throw new \Exception($responseBodyAsString);
    }
}
