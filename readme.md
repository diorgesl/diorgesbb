# DiorgesBB

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Pacote para Laravel, API para registro/verificação de boletos usando o projeto Piloto do BB, compátivel com Laravel 5 ao 8.

A API piloto do Banco do Brasil serve para registrar/listar/alterar e baixar boletos diretamente
no banco a partir de comunicação OAuth.

Entre em contato com seu gerente bancário para conseguir utilizar a API.

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
```php
<?php 
use Diorgesl\DiorgesBB\Boletos;
use Diorgesl\DiorgesBB\Boleto;
use Diorgesl\DiorgesBB\Pagador;

$boletos = new Boletos();

// O pagador será definido como CPF ou CNPJ baseado no tamanho do numeroRegistro
$pagador = new Pagador([
    'numeroRegistro' => '979.659.401-32',
    'nome' => 'CLIENTE TESTE',
    'endereco' => 'RUA TESTE',
    'bairro' => 'BRASILIA',
    'cidade' => 'BRASIL',
    'cep' => '10304-210',
    'uf' => 'MS'
]);

// Todos os campos aqui tem explicação na classe Boleto (as informações contidas lá foram retiradas do Swagger da API)
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

Resposta do banco:
```json
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

Outras funções:

Dar baixa em um boleto:
```php
<?php 
use Diorgesl\DiorgesBB\Boletos;

$boletos = new Boletos();

$boletos->baixar(12345);
```

Resposta do banco:
```json
{
  "numeroContratoCobranca": "19581316",
  "dataBaixa": "01.10.2020",
  "horarioBaixa": "15:04:33"
}
```
---------
Listar boletos:
```php
<?php 
use Diorgesl\DiorgesBB\Boletos;

$boletos = new Boletos();

// Retornar todos os boletos baixados/liquidados no periodo do movimento
$boletos = $boletos->boletos([
    "indicadorSituacao" => "B", // A = em aberto (Padrão), B = Baixados/Protestados/Liquidados
    "dataInicioMovimento" => "01.10.2020",
    "dataFimMovimento" => "20.10.2020", 
]);

// Retornar todos os boletos de um CPF/CNPJ
// Todos os boletos em aberto do CPF
$boletos = $boletos->boletos([
    //"indicadorSituacao" => "A", // A = em aberto (Padrão), B = Baixados/Protestados/Liquidados
    "cpfPagador" => 979659401,
    "digitoCPFPagador" => 32,
]);

//Todos os boletos em aberto do CNPJ 
$boletos = $boletos->boletos([
    "cnpjPagador" => 196152360001,
    "digitoCNPJPagador" => 27,
]);
```
---------
Detalhar um Boleto:
```php
<?php 
use Diorgesl\DiorgesBB\Boletos;

$boletos = new Boletos();
// Busca o boleto com ID 123456
$boleto = $boletos->boleto(123456);
var_dump($boleto);
```

Resposta do banco:
```json
{
   "numeroContratoCobranca":"19581316",
   "codigoEstadoTituloCobranca":1,
   "codigoTipoTituloCobranca":10,
   "codigoModalidadeTitulo":1,
   "codigoAceiteTituloCobranca":"N",
   "codigoPrefixoDependenciaCobrador":14,
   "codigoIndicadorEconomico":9,
   "numeroTituloCedenteCobranca":"123456",
   "dataEmissaoTituloCobranca":"29.09.2020",
   "dataRegistroTituloCobranca":"30.09.2020",
   "dataVencimentoTituloCobranca":"30.10.2020",
   "valorOriginalTituloCobranca":109.9,
   "valorAtualTituloCobranca":109.9,
   "valorPagamentoParcialTitulo":0,
   "valorAbatimentoTituloCobranca":0,
   "percentualImpostoSobreOprFinanceirasTituloCobranca":0,
   "valorImpostoSobreOprFinanceirasTituloCobranca":0,
   "valorMoedaTituloCobranca":0,
   "quantidadeParcelaTituloCobranca":0,
   "dataBaixaAutomaticoTitulo":"30.10.2021",
   "textoCampoUtilizacaoCedente":"",
   "indicadorCobrancaPartilhadoTitulo":"N",
   "valorMoedaAbatimentoTitulo":0,
   "dataProtestoTituloCobranca":"",
   "numeroCarteiraCobranca":17,
   "numeroVariacaoCarteiraCobranca":35,
   "quantidadeDiaProtesto":0,
   "quantidadeDiaPrazoLimiteRecebimento":360,
   "dataLimiteRecebimentoTitulo":"25.10.2021",
   "indicadorPermissaoRecebimentoParcial":"N",
   "textoCodigoBarrasTituloCobranca":"00197842400000109900000003128557000000123456",
   "codigoOcorrenciaCartorio":0,
   "indicadorDebitoCreditoTitulo":0,
   "valorImpostoSobreOprFinanceirasRecebidoTitulo":0,
   "valorAbatimentoTotal":0,
   "valorCreditoCedente":0,
   "codigoTipoLiquidacao":0,
   "dataCreditoLiquidacao":"",
   "dataRecebimentoTitulo":"",
   "codigoPrefixoDependenciaRecebedor":0,
   "codigoNaturezaRecebimento":0,
   "codigoResponsavelAtualizacao":"",
   "codigoTipoBaixaTitulo":0,
   "valorReajuste":0,
   "valorOutroRecebido":0,
   "codigoIndicadorEconomicoUtilizadoInadimplencia":0,
   "sacado":{
      "codigoTipoInscricaoSacado":1,
      "numeroInscricaoSacadoCobranca":97965940132,
      "nomeSacadoCobranca":"CLIENTE TESTE",
      "textoEnderecoSacadoCobranca":"RUA TESTE",
      "nomeBairroSacadoCobranca":"TESTE",
      "nomeMunicipioSacadoCobranca":"BRASILIA",
      "siglaUnidadeFederacaoSacadoCobranca":"DF",
      "numeroCepSacadoCobranca":79000000,
      "valorPagoSacado":0,
      "numeroIdentidadeSacadoTituloCobranca":""
   },
   "sacador":{
      "codigoTipoInscricaoSacador":1,
      "numeroInscricaoSacadorAvalista":97965940132,
      "nomeSacadorAvalistaTitulo":"CLIENTE TESTE"
   },
   "multa":{
      "percentualMultaTitulo":0,
      "valorMultaTituloCobranca":0,
      "dataMultaTitulo":"",
      "valorMultaRecebido":0
   },
   "desconto":{
      "percentualDescontoTitulo":0,
      "dataDescontoTitulo":"",
      "valorDescontoTitulo":0,
      "codigoDescontoTitulo":0,
      "valorDescontoUtilizado":0,
      "segundoDesconto":{
         "percentualSegundoDescontoTitulo":0,
         "dataSegundoDescontoTitulo":"",
         "valorSegundoDescontoTitulo":0,
         "codigoSegundoDescontoTitulo":0
      },
      "terceiroDesconto":{
         "percentualTerceiroDescontoTitulo":0,
         "dataTerceiroDescontoTitulo":"",
         "valorTerceiroDescontoTitulo":0,
         "codigoTerceiroDescontoTitulo":0
      }
   },
   "juroMora":{
      "codigoTipoJuroMora":0,
      "percentualJuroMoraTitulo":0,
      "valorJuroMoraTitulo":0,
      "dataJuroMoraTitulo":"",
      "valorJuroMoraRecebido":0
   }
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
- [Eduardo Gusmão (Laravel Boleto)][link-eduardo]
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
