<?php

namespace Diorgesl\DiorgesBB;

class Boleto
{

    /*
     * Identificador determinado pelo sistema Cobrança para controlar a emissão
     * de boletos, liquidação, crédito de valores ao Beneficiário e intercâmbio
     * de dados com o cliente. Campo Obrigatório.
     *
     * @var float
     */
    protected $numeroConvenio = 0;

    /*
     * Características do serviço de boleto bancário e como ele deve ser tratado
     * pelo banco. Campo Obrigatório.
     *
     * @var float
     */
    protected $numeroCarteira = 0;

    /*
     * Código do tipo de portfolio do boleto. Todos os portfólios devem ter um
     * código específico. Campo Obrigatório.
     *
     * @var float
     */
    protected $numeroVariacaoCarteira = 0;

    /*
     * Identifica a característica dos boletos dentro das modalidades de cobrança
     * existentes no banco. Campo Obrigatório. Domínio: 01 - SIMPLES; 04 - VINCULADA
     *
     * @var float
     */

    protected $codigoModalidade = 0;

    /*
     * Data de emissão do boleto (formato "dd.mm.aaaaa"). Campo Obrigatório.
     *
     * @var string
     */
    protected $dataEmissao;

    /*
     * Data de vencimento do boleto (formato "dd.mm.aaaaa"), >= dataEmissao.
     * Campo Obrigatório.
     *
     * @var string
     */
    protected $dataVencimento;

    /*
     * Valor de cobrança > 0.00, emitido em Real (formato decimal separado por ".").
     * Valor do boleto no registro. Deve ser maior que a soma dos campos
     * “VALOR DO DESCONTO DO TÍTULO” e “VALOR DO ABATIMENTO DO TÍTULO”, se informados.
     * Informação não passível de alteração após a criação. No caso de emissão com valor
     * equivocado, sugerimos cancelar e emitir novo boleto. Campo Obrigatório.
     *
     * @var float
     */
    protected $valorOriginal = 0;

    /*
     * Valor de dedução do boleto >= 0.00 (formato decimal separado por ".").
     *
     * @var float
     */
    protected $valorAbatimento = 0;

    /*
     * Quantos dias após a data de emissão do boleto para iniciar o processo
     * de cobrança através de protesto. (valor inteiro >= 0).
     *
     * @var float
     */
    protected $quantidadeDiasProtesto = 0;

    /*
     * Indicador de que o boleto pode ou não ser recebido após o vencimento.
     * Campo não obrigatório Se não informado, será assumido a informação de
     * limite de recebimento que está definida no convênio.
     *
     * @var string
     */
    protected $indicadorNumeroDiasLimiteRecebimento; // S para SIM ou N para NÃO

    /*
     * Número de dias limite para recebimento. Informar valor inteiro > 0 se
     * o campo indicadorNumeroDiasLimiteRecebimento = "S".
     *
     * @var float
     */
    protected $numeroDiasLimiteRecebimento = 0;

    /*
     * Código para identificar se o boleto de cobrança foi aceito (reconhecimento
     * da dívida pelo Pagador). Campo Obrigatório.
     * Domínios: A - ACEITE N - NAO ACEITE
     */
    protected $codigoAceite;

    /*
     * Código para identificar o tipo de boleto de cobrança. Campo Obrigatório.
     * Domínios:
     * 1- CHEQUE
     * 2- DUPLICATA MERCANTIL
     * 3- DUPLICATA MTIL POR INDICACAO
     * 4- DUPLICATA DE SERVICO
     * 5- DUPLICATA DE SRVC P/INDICACAO
     * 6- DUPLICATA RURAL
     * 7- LETRA DE CAMBIO
     * 8- NOTA DE CREDITO COMERCIAL
     * 9- NOTA DE CREDITO A EXPORTACAO
     * 10- NOTA DE CREDITO INDULTRIAL
     * 11- NOTA DE CREDITO RURAL
     * 12- NOTA PROMISSORIA
     * 13- NOTA PROMISSORIA RURAL
     * 14- TRIPLICATA MERCANTIL
     * 15- TRIPLICATA DE SERVICO
     * 16- NOTA DE SEGURO
     * 17- RECIBO
     * 18- FATURA
     * 19- NOTA DE DEBITO
     * 20- APOLICE DE SEGURO
     * 21- MENSALIDADE ESCOLAR
     * 22- PARCELA DE CONSORCIO
     * 23- DIVIDA ATIVA DA UNIAO
     * 24- DIVIDA ATIVA DE ESTADO
     * 25- DIVIDA ATIVA DE MUNICIPIO
     * 31- CARTAO DE CREDITO
     * 32- BOLETO PROPOSTA
     * 99- OUTROS.
     *
     * @var int
     */
    protected $codigoTipoTitulo = 0;

    /*
     * Descrição do tipo de boleto.
     *
     * @var string
     */
    protected $descricaoTipoTitulo;

    /*
     * Código para identificação da autorização de pagamento
     * parcial do boleto. Campo Obrigatório.
     * Domínios: S - SIM N - NÃO
     *
     * @var string
     */
    protected $indicadorPermissaoRecebimentoParcial;

    /*
     * Número de identificação do boleto
     * (correspondente ao SEU NÚMERO), no formato STRING.
     *
     * @var string
     */
    protected $numeroTituloBeneficiario;

    /*
     * Informações adicionais sobre o beneficiário.
     *
     * @var string
     */
    protected $textoCampoUtilizacaoBeneficiario;

    /*
     * Número de identificação do boleto (correspondente ao NOSSO NÚMERO),
     * no formato STRING, com 20 dígitos, que deverá ser formatado da
     * seguinte forma:
     * “000” + (número do convênio com 7 dígitos) + (10 algarismos - se
     * necessário, completar com zeros à esquerda).
     * Campo Obrigatório.
     *
     * @var string
     */
    protected $numeroTituloCliente;

    /*
     * Mensagem definida pelo beneficiário para ser impressa no boleto.
     *
     * @var string
     */
    protected $textoMensagemBloquetoOcorrencia;

    /*
     * Endereço de e-mail para o qual irá o boleto gerado.
     * Campo NÃO OBRIGATÓRIO.
     *
     * @var string
     */
    protected $email;

    /*
     * Quantidade de dias para negativar depois do vencimento do boleto.
     * Valor inteiro >= 0.
     *
     * @var int
     */
    protected $quantidadeDiasNegativacao;

    protected $avalista;

    /*
     * Dados do Pagador
     *
     * @var Object Pagador
     */
    protected $pagador;

    protected $multa;

    protected $jurosMora;

    protected $desconto;

    protected $segundoDesconto;

    protected $terceiroDesconto;

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

    /**
     * @return float
     */
    public function getNumeroDiasLimiteRecebimento(): float
    {
        return $this->numeroDiasLimiteRecebimento;
    }

    /**
     * @param float $numeroDiasLimiteRecebimento
     */
    public function setNumeroDiasLimiteRecebimento(float $numeroDiasLimiteRecebimento): void
    {
        $this->numeroDiasLimiteRecebimento = $numeroDiasLimiteRecebimento;
    }

    /**
     * @return int
     */
    public function getNumeroConvenio(): int
    {
        return $this->numeroConvenio;
    }

    /**
     * @param int $numeroConvenio
     */
    public function setNumeroConvenio(int $numeroConvenio): void
    {
        $this->numeroConvenio = $numeroConvenio;
    }

    /**
     * @return int
     */
    public function getNumeroCarteira(): int
    {
        return $this->numeroCarteira;
    }

    /**
     * @param int $numeroCarteira
     */
    public function setNumeroCarteira(int $numeroCarteira): void
    {
        $this->numeroCarteira = $numeroCarteira;
    }

    /**
     * @return int
     */
    public function getNumeroVariacaoCarteira(): int
    {
        return $this->numeroVariacaoCarteira;
    }

    /**
     * @param int $numeroVariacaoCarteira
     */
    public function setNumeroVariacaoCarteira(int $numeroVariacaoCarteira): void
    {
        $this->numeroVariacaoCarteira = $numeroVariacaoCarteira;
    }

    /**
     * @return int
     */
    public function getCodigoModalidade(): int
    {
        return $this->codigoModalidade;
    }

    /**
     * @param int $codigoModalidade
     */
    public function setCodigoModalidade(int $codigoModalidade): void
    {
        $this->codigoModalidade = $codigoModalidade;
    }

    /**
     * @return mixed
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    /**
     * @param mixed $dataEmissao
     */
    public function setDataEmissao($dataEmissao): void
    {
        $this->dataEmissao = $dataEmissao;
    }

    /**
     * @return mixed
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param mixed $dataVencimento
     */
    public function setDataVencimento($dataVencimento): void
    {
        $this->dataVencimento = $dataVencimento;
    }

    /**
     * @return int
     */
    public function getValorOriginal(): int
    {
        return $this->valorOriginal;
    }

    /**
     * @param int $valorOriginal
     */
    public function setValorOriginal(int $valorOriginal): void
    {
        $this->valorOriginal = $valorOriginal;
    }

    /**
     * @return int
     */
    public function getValorAbatimento(): int
    {
        return $this->valorAbatimento;
    }

    /**
     * @param int $valorAbatimento
     */
    public function setValorAbatimento(int $valorAbatimento): void
    {
        $this->valorAbatimento = $valorAbatimento;
    }

    /**
     * @return int
     */
    public function getQuantidadeDiasProtesto(): int
    {
        return $this->quantidadeDiasProtesto;
    }

    /**
     * @param int $quantidadeDiasProtesto
     */
    public function setQuantidadeDiasProtesto(int $quantidadeDiasProtesto): void
    {
        $this->quantidadeDiasProtesto = $quantidadeDiasProtesto;
    }

    /**
     * @return mixed
     */
    public function getIndicadorNumeroDiasLimiteRecebimento()
    {
        return $this->indicadorNumeroDiasLimiteRecebimento;
    }

    /**
     * @param mixed $indicadorNumeroDiasLimiteRecebimento
     */
    public function setIndicadorNumeroDiasLimiteRecebimento($indicadorNumeroDiasLimiteRecebimento): void
    {
        $this->indicadorNumeroDiasLimiteRecebimento = $indicadorNumeroDiasLimiteRecebimento;
    }

    /**
     * @return mixed
     */
    public function getCodigoAceite()
    {
        return $this->codigoAceite;
    }

    /**
     * @param mixed $codigoAceite
     */
    public function setCodigoAceite($codigoAceite): void
    {
        $this->codigoAceite = $codigoAceite;
    }

    /**
     * @return int
     */
    public function getCodigoTipoTitulo(): int
    {
        return $this->codigoTipoTitulo;
    }

    /**
     * @param int $codigoTipoTitulo
     */
    public function setCodigoTipoTitulo(int $codigoTipoTitulo): void
    {
        $this->codigoTipoTitulo = $codigoTipoTitulo;
    }

    /**
     * @return mixed
     */
    public function getDescricaoTipoTitulo()
    {
        return $this->descricaoTipoTitulo;
    }

    /**
     * @param mixed $descricaoTipoTitulo
     */
    public function setDescricaoTipoTitulo($descricaoTipoTitulo): void
    {
        $this->descricaoTipoTitulo = $descricaoTipoTitulo;
    }

    /**
     * @return mixed
     */
    public function getIndicadorPermissaoRecebimentoParcial()
    {
        return $this->indicadorPermissaoRecebimentoParcial;
    }

    /**
     * @param mixed $indicadorPermissaoRecebimentoParcial
     */
    public function setIndicadorPermissaoRecebimentoParcial($indicadorPermissaoRecebimentoParcial): void
    {
        $this->indicadorPermissaoRecebimentoParcial = $indicadorPermissaoRecebimentoParcial;
    }

    /**
     * @return mixed
     */
    public function getNumeroTituloBeneficiario()
    {
        return $this->numeroTituloBeneficiario;
    }

    /**
     * @param mixed $numeroTituloBeneficiario
     */
    public function setNumeroTituloBeneficiario($numeroTituloBeneficiario): void
    {
        $this->numeroTituloBeneficiario = $numeroTituloBeneficiario;
    }

    /**
     * @return mixed
     */
    public function getTextoCampoUtilizacaoBeneficiario()
    {
        return $this->textoCampoUtilizacaoBeneficiario;
    }

    /**
     * @param mixed $textoCampoUtilizacaoBeneficiario
     */
    public function setTextoCampoUtilizacaoBeneficiario($textoCampoUtilizacaoBeneficiario): void
    {
        $this->textoCampoUtilizacaoBeneficiario = $textoCampoUtilizacaoBeneficiario;
    }

    /**
     * @return mixed
     */
    public function getNumeroTituloCliente()
    {
        return $this->numeroTituloCliente;
    }

    /**
     * @param mixed $numeroTituloCliente
     */
    public function setNumeroTituloCliente($numeroTituloCliente): void
    {
        $this->numeroTituloCliente = $numeroTituloCliente;
    }

    /**
     * @return mixed
     */
    public function getTextoMensagemBloquetoOcorrencia()
    {
        return $this->textoMensagemBloquetoOcorrencia;
    }

    /**
     * @param mixed $textoMensagemBloquetoOcorrencia
     */
    public function setTextoMensagemBloquetoOcorrencia($textoMensagemBloquetoOcorrencia): void
    {
        $this->textoMensagemBloquetoOcorrencia = $textoMensagemBloquetoOcorrencia;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeDiasNegativacao()
    {
        return $this->quantidadeDiasNegativacao;
    }

    /**
     * @param mixed $quantidadeDiasNegativacao
     */
    public function setQuantidadeDiasNegativacao($quantidadeDiasNegativacao): void
    {
        $this->quantidadeDiasNegativacao = $quantidadeDiasNegativacao;
    }

    /**
     * @return mixed
     */
    public function getPagador()
    {
        return $this->pagador;
    }

    /**
     * @param mixed $pagador
     * @return object Pagador
     */
    public function setPagador($pagador)
    {
        $this->pagador = new Pagador($pagador);
        return $this;
    }

    public function toArray() {
        return [
            'numeroConvenio' => $this->getNumeroConvenio(),
            'numeroCarteira' => $this->getNumeroCarteira(),
            'numeroVariacaoCarteira' => $this->getNumeroVariacaoCarteira(),
            'codigoModalidade' => $this->getCodigoModalidade(),

        ];
    }

}
