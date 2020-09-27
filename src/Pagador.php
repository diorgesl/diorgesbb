<?php

namespace Diorgesl\DiorgesBB;

class Pagador
{
    protected $nome;

    protected $endereco;

    protected $bairro;

    protected $cep;

    protected $uf;

    protected $cidade;

    protected $documento;

    public function __construct($params = [])
    {
        foreach ($params as $param => $value) {
            $param = str_replace(' ', '', ucwords(str_replace('_', ' ', $param)));
            if (method_exists($this, 'getProtectedFields') && in_array(lcfirst($param), $this->getProtectedFields())) {
                continue;
            }
            if (method_exists($this, 'set' . ucwords($param))) {
                $this->{'set' . ucwords($param)}($value);
            }
        }
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function setEndereco($endereco){
        $this->endereco = $endereco;
    }

    public function setBairro($bairro){
        $this->bairro = $bairro;
    }

    public function setCep($cep){
        $this->cep = $cep;
    }

    public function setUf($uf){
        $this->uf = $uf;
    }

    public function setCidade($cidade){
        $this->cidade = $cidade;
    }

    public function setDocumento($documento){
        $this->documento = $documento;
    }

    public function getNome($nome){
        return $this->nome;
    }

    public function getEndereco($endereco){
        return $this->endereco;
    }

    public function getBairro($bairro){
        return $this->bairro;
    }

    public function getCep($cep){
        return $this->cep;
    }

    public function getUf($uf){
        return $this->uf;
    }

    public function getCidade($cidade){
        return $this->cidade;
    }

    public function getDocumento($documento){
        return $this->documento;
    }
}
