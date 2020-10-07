<?php

namespace Diorgesl\DiorgesBB;

class Pagador implements \JsonSerializable
{
    /**
     * Nome do Pagador.
     *
     * @var string
     */
    protected $nome;

    /**
     * Endereço do Pagador.
     *
     * @var string
     */
    protected $endereco;

    /**
     * Bairro do Pagador.
     *
     * @var string
     */
    protected $bairro;

    /**
     * CEP do Pagador.
     *
     * @var string
     */
    protected $cep;

    /**
     * UF do Pagador.
     *
     * @var string
     */
    protected $uf;

    /**
     * Cidade do Pagador.
     *
     * @var string
     */
    protected $cidade;

    /**
     * Documento do Pagador - CPF ou CNPJ.
     *
     * @var string
     */
    protected $numeroRegistro;

    /**
     * Identifica se é CPF ou CNPJ.
     *
     * @var int
     */
    protected $tipoRegistro;

    /**
     * Pagador constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        foreach ($params as $param => $value) {
            $param = str_replace(' ', '', ucwords(str_replace('_', ' ', $param)));
            if (method_exists($this, 'getProtectedFields') && in_array(lcfirst($param), $this->getProtectedFields())) {
                continue;
            }
            if (method_exists($this, 'set'.ucwords($param))) {
                $this->{'set'.ucwords($param)}($value);
            }
        }
    }

    /**
     * @param $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @param $endereco
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * @param $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @param $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @param $uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * @param $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @param $numeroRegistro
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;
    }

    /**
     * @param $tipoRegistro
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @return int
     */
    public function getCep()
    {
        $cep = (int) ltrim(str_replace(['.','-'],'', $this->cep), '0');
        return $cep;
    }

    /**
     * @return mixed
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @return int
     */
    public function getNumeroRegistro()
    {
        $registro = (int) ltrim(str_replace(['.', '/', '-'], '', $this->numeroRegistro), '0');

        return $registro;
    }

    /**
     * @return int
     */
    public function getTipoRegistro()
    {
        return strlen($this->numeroRegistro) >= 14 ? 2 : 1;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $arr = [];

        foreach (get_class_methods($this) as $method) {
            if (strpos($method, 'get') !== false) {
                $value = $this->{$method}();

                if (! empty($value) || strlen(trim($value)) > 0) {
                    $arr[lcfirst(str_replace(['set', 'get'], ['', ''], $method))] = $value;
                }
            }
        }

        return $arr;
    }
}
