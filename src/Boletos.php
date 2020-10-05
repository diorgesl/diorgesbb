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
     * @var string
     */
    private $convenio;

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
        $this->convenio = str_pad($this->secrets['numeroConvenio'], 10, '0', STR_PAD_LEFT);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function boleto($id)
    {
        $boleto = str_pad($id, 10, '0', STR_PAD_LEFT);
        try {
            $res = $this->client->request('GET', $this->uri.'/'.$this->convenio.$boleto, [
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
            return json_decode($e->getResponse()->getBody()->getContents());
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
            $request = $this->client->request('GET', $this->uri, [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-type' => 'application/json',
                ],
            ]);

            return json_decode($request->getBody()->getContents());
        } catch (GuzzleException $e) {
            if ($e->getCode() == 404) {
                return ['errors' => true, 'msg' => 'Nenhum boleto encontrado.'];
            } else {
                return json_decode($e->getResponse()->getBody()->getContents());
            }
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
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function baixar($id)
    {
        $boleto = str_pad($id, 10, '0', STR_PAD_LEFT);
        try {
            $res = $this->client->request('POST', $this->uri.'/'.$this->convenio.$boleto.'/baixar', [
                'query' => [
                    'gw-dev-app-key' => $this->secrets['developer_application_key'],
                ],
                'body' => json_encode(['numeroConvenio' => $this->secrets['numeroConvenio']]),
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($res->getBody()->getContents());
        } catch (GuzzleException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function alterarBoleto($id, $params = [])
    {
        $boleto = str_pad($id, 10, '0', STR_PAD_LEFT);

        // Dados Padrão
        $body = [
            'numeroConvenio' => $this->secrets['numeroConvenio'],
            'indicadorNovaDataVencimento' => 'N',
            'indicadorAtribuirDesconto' => 'N',
            'indicadorAlterarDesconto' => 'N',
            'indicadorAlterarDataDesconto' => 'N',
            'indicadorProtestar' => 'N',
            'indicadorSustacaoProtesto' => 'N',
            'indicadorCancelarProtesto' => 'N',
            'indicadorIncluirAbatimento' => 'N',
            'indicadorAlterarAbatimento' => 'N',
            'indicadorCobrarJuros' => 'N',
            'indicadorDispensarJuros' => 'N',
            'indicadorCobrarMulta' => 'N',
            'indicadorDispensarMulta' => 'N',
            'indicadorNegativar' => 'N',
            'indicadorAlterarSeuNumero' => 'N',
            'indicadorAlterarEnderecoPagador' => 'N',
            'indicadorAlterarPrazoBoletoVencido' => 'N',
        ];

        // Faz as alteracoes baseado nos parametros passados
        foreach ($params as $key => $param) {
            switch ($key) {
                case 'dataVencimento':
                    $body = array_merge($body, [
                        'indicadorNovaDataVencimento' => 'S',
                        'alteracaoData' => [
                            'novaDataVencimento' => $params[$key],
                        ],
                    ]);
                    break;
                case 'dispensarMulta':
                    $body = array_merge($body, [
                        'indicadorDispensarMulta' => 'S',
                    ]);
                    break;
                case 'diasPagamento':
                    $body = array_merge($body, [
                        'indicadorAlterarPrazoBoletoVencido' => 'S',
                        'alteracaoPrazo' => [
                            'quantidadeDiasAceite' => (int) $params[$key],
                        ],
                    ]);
                    break;
                case 'endereco':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'endereco':
                                $arr['enderecoPagador'] = $v;
                                break;
                            case 'bairro':
                                $arr['bairroPagador'] = $v;
                                break;
                            case 'cidade':
                                $arr['cidadePagador'] = $v;
                                break;
                            case 'uf':
                                $arr['UFPagador'] = $v;
                                break;
                            case 'cep':
                                $arr['CEPPagador'] = (int) $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorAlterarEnderecoPagador' => 'S',
                        'alteracaoEndereco' => $arr,
                    ]);
                    break;

                case 'desconto':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'tipo':
                                $arr['tipoPrimeiroDesconto'] = (int) $v;
                                break;
                            case 'valor':
                                $arr['valorPrimeiroDesconto'] = (float) $v;
                                break;
                            case 'percentual':
                                $arr['percentualPrimeiroDesconto'] = (float) $v;
                                break;
                            case 'data':
                                $arr['dataPrimeiroDesconto'] = $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorAtribuirDesconto' => 'S',
                        'desconto' => $arr,
                    ]);
                    break;
                case 'alterarDesconto':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'tipo':
                                $arr['tipoPrimeiroDesconto'] = (int) $v;
                                break;
                            case 'valor':
                                $arr['novoValorPrimeiroDesconto'] = (float) $v;
                                break;
                            case 'percentual':
                                $arr['novoPercentualPrimeiroDesconto'] = (float) $v;
                                break;
                            case 'data':
                                $arr['novaDataPrimeiroDesconto'] = $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorAlterarDesconto' => 'S',
                        'alteracaoDesconto' => $arr,
                    ]);
                    break;
                case 'alterarDataDesconto':
                    $body = array_merge($body, [
                        'indicadorAlterarDataDesconto' => 'S',
                        'alteracaoDataDesconto' => [
                            'novaDataLimitePrimeiroDesconto' => $params[$key],
                        ],
                    ]);
                    break;

                case 'protestar':
                    $body = array_merge($body, [
                        'indicadorProtestar' => 'S',
                        'protesto' => [
                            'quantidadeDiasProtesto' => (int) $params[$key],
                        ],
                    ]);
                    break;

                case 'sustarProtesto':
                    $body = array_merge($body, [
                        'indicadorSustacaoProtesto' => 'S',
                    ]);
                    break;

                case 'cancelarProtesto':
                    $body = array_merge($body, [
                        'indicadorCancelarProtesto' => 'S',
                    ]);
                    break;

                case 'abatimento':
                    $body = array_merge($body, [
                        'indicadorIncluirAbatimento' => 'S',
                        'abatimento' => [
                            'valorAbatimento' => $params[$key],
                        ],
                    ]);
                    break;
                case 'alterarAbatimento':
                    $body = array_merge($body, [
                        'indicadorAlterarAbatimento' => 'S',
                        'alteracaoAbatimento' => [
                            'novoValorAbatimento' => (float) $params[$key],
                        ],
                    ]);
                    break;

                case 'juros':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'tipo':
                                $arr['tipoJuros'] = (int) $v;
                                break;
                            case 'valor':
                                $arr['valorJuros'] = (float) $v;
                                break;
                            case 'taxa':
                                $arr['taxaJuros'] = (float) $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorCobrarJuros' => 'S',
                        'juros' => $arr,
                    ]);
                    break;

                case 'dispensarJuros':
                    $body = array_merge($body, [
                        'indicadorDispensarJuros' => 'S',
                    ]);
                    break;

                case 'multa':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'tipo':
                                $arr['tipoMulta'] = (int) $v;
                                break;
                            case 'valor':
                                $arr['valorMulta'] = (float) $v;
                                break;
                            case 'taxa':
                                $arr['taxaMulta'] = (float) $v;
                                break;
                            case 'data':
                                $arr['dataInicioMulta'] = $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorCobrarMulta' => 'S',
                        'multa' => $arr,
                    ]);
                    break;

                case 'negativar':
                    $arr = [];
                    foreach ($param as $k => $v) {
                        switch ($k) {
                            case 'dias':
                                $arr['quantidadeDiasNegativacao'] = (int) $v;
                                break;
                            case 'tipo':
                                $arr['tipoNegativacao'] = (int) $v;
                                break;
                        }
                    }
                    $body = array_merge($body, [
                        'indicadorNegativar' => 'S',
                        'negativacao' => $arr,
                    ]);
                    break;

                case 'seuNumero':
                    $body = array_merge($body, [
                        'indicadorAlterarSeuNumero' => 'S',
                        'alteracaoSeuNumero' => [
                            'codigoSeuNumero' => (int) $params[$key],
                        ],
                    ]);
                    break;
            }
        }

        //dd(json_encode($body));

        try {
            $res = $this->client->patch($this->uri.'/'.$this->convenio.$boleto, [
                'query' => [
                    'gw-dev-app-key' => $this->secrets['developer_application_key'],
                ],
                'body' => json_encode($body),
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($res->getBody()->getContents());
        } catch (GuzzleException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
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
}
