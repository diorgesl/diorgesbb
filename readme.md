# DiorgesBB

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Pacote para Laravel, API para registro/verificação de boletos usando o projeto Piloto do BB, compátivel com Laravel 5 ao 8.

## Installation

Via Composer

``` bash
$ composer require diorgesl/diorgesbb
```

## Usage

Arquivo de Configuração
``` bash
php artisan vendor:publish --provider="Diorgesl\DiorgesBB\DiorgesBBServiceProvider"
```

Configurar o arquivo `config/diorgesbb.php` com os dados de acesso da API.

Para registrar um boleto:
```
<?php 
use Diorgesl\DiorgesBB\Boletos;
use Diorgesl\DiorgesBB\Boleto;
use Diorgesl\DiorgesBB\Pagador;

$boletos = new Boletos();

$pagador = new Pagador([
    'numeroRegistro' => 97965940132,
    'nome' => 'CLIENTE TESTE',
    'endereco' => 'AL SATELITE 13',
    'bairro' => 'UNIVERSITARIO',
    'cidade' => 'CORUMBA',
    'cep' => 79304310,
    'uf' => 'MS'
]);

$boleto = new Boleto([
    "codigoModalidade" => 1,
    "dataEmissao" => "29.09.2020",
    "dataVencimento" => "30.10.2020",
    "valorOriginal" => 109.90,
    "codigoAceite" => "N",
    "codigoTipoTitulo" => 10,
    "descricaoTipoTitulo" => "Duplicata Mercantil",
    "indicadorPermissaoRecebimentoParcial" => "N",
    "numeroTituloBeneficiario" => "123456",
    "numeroTituloCliente" => 43832319,
    "pagador" => $pagador,
]);

$ret = $boletos->registrar($boleto);

var_dump($ret);
```

Resposta do servidor:
```
{
   "numero":"00031285570043832319",
   "numeroCarteira":17,
   "numeroVariacaoCarteira":35,
   "codigoCliente":704950857,
   "linhaDigitavel":"00190000090312855700043832319172884240000010990",
   "codigoBarraNumerico":"00198842400000109900000003128557004383231917",
   "numeroContratoCobranca":19581316,
   "beneficiario":{
      "agencia":452,
      "contaCorrente":123873,
      "tipoEndereco":0,
      "logradouro":"Cliente nao localizado ou sem enderecos validos.",
      "bairro":"",
      "cidade":"",
      "codigoCidade":0,
      "uf":"",
      "cep":0,
      "indicadorComprovacao":""
   },
   "quantidadeOcorrenciasNegativacao":"0",
   "listaOcorrenciasNegativacao":[
      
   ]
}
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email diorges@gis.net.br instead of using the issue tracker.

## Credits

- [Diorges Rocha][link-author]
- [Eduardo Kum (Laravel Boleto)][link-eduardo]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/diorgesl/diorgesbb.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/diorgesl/diorgesbb.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/diorgesl/diorgesbb/master.svg?style=flat-square
[ico-styleci]: https://github.styleci.io/repos/299090003/shield

[link-packagist]: https://packagist.org/packages/diorgesl/diorgesbb
[link-downloads]: https://packagist.org/packages/diorgesl/diorgesbb
[link-travis]: https://travis-ci.org/diorgesl/diorgesbb
[link-styleci]: https://github.styleci.io/repos/299090003
[link-author]: https://github.com/diorgesl
[link-eduardo]: https://github.com/eduardokum/laravel-boleto
[link-contributors]: ../../contributors
