<?php

return [
    /*
     *  Usar API de Homologação ou Produção
     *  Se = false usa homologação
     */
    'production' => false,

    'api' => [
        'homologa' => [

            /*
             * Dados ficticios fornecidos pelo BB, não alterar
             */
            'agenciaBeneficiario' => '452',
            'contaBeneficiario' => '123873',
            'numeroConvenio' => '3128557',
            'numeroCarteira' => 17,
            'numeroVariacaoCarteira' => 35,
            /*
             * Fim dados ficticios
             */

            'developer_application_key' => '',
            'client_id' => '',
            'client_secret' => '',
            'basic' => '',
        ],
        'producao' => [
            'agenciaBeneficiario' => '', // SEM DIFITO VERIFICADOR
            'contaBeneficiario' => '', // SEM DIGITO VERIFICADOR
            'numeroConvenio' => 0,
            'numeroCarteira' => 0,
            'numeroVariacaoCarteira' => 0,
            'developer_application_key' => '',
            'client_id' => '',
            'client_secret' => '',
            'basic' => '',
        ],
    ],
];
