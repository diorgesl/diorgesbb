<?php

namespace Diorgesl\DiorgesBB;

class Avalista implements \JsonSerializable
{
    protected $nomeRegistro;

    protected $numeroRegistro;

    protected $tipoRegistro;

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

    public function setNomeRegistro($nomeRegistro){
        $this->nomeRegistro = $nomeRegistro;
    }

    public function setNumeroRegistro($numeroRegistro){
        $this->numeroRegistro = $numeroRegistro;
    }

    public function setTipoRegistro($tipoRegistro){
        $this->tipoRegistro = $tipoRegistro;
    }

    public function getNomeRegistro(){
        return $this->nomeRegistro;
    }

    public function getNumeroRegistro(){
        return $this->numeroRegistro;
    }

    public function getTipoRegistro(){
        return strlen($this->numeroRegistro) <= 11 ? 1 : 2;
    }

    public function jsonSerialize (){
        $arr = [];
        foreach(get_class_methods($this) as $method){
            if (strpos($method, 'get') !== false) {
                $value = $this->{$method}();

                if(!empty($value) || strlen(trim($value)) > 0){
                    $arr[lcfirst(str_replace(['set', 'get'], ['', ''], $method))] = $value;
                }
            }
        }

        return $arr;
    }
}
