<?php

namespace Diorgesl\DiorgesBB;

class Pagador implements \JsonSerializable
{
    protected $nome;

    protected $endereco;

    protected $bairro;

    protected $cep;

    protected $uf;

    protected $cidade;

    protected $numeroRegistro;

    protected $tipoRegistro;

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

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;
    }

    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function getUf()
    {
        return $this->uf;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    public function getTipoRegistro()
    {
        return strlen($this->numeroRegistro) <= 11 ? 1 : 2;
    }

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
