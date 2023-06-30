<?php

return [
    'rules' => [
        'declarationRequestId'                    => 'require|string|max:32',
        'paymentId'                               => 'require|string|max:64',

        'declarationAmount'                       => 'require|array',
        'declarationAmount.currency'              => 'require|string|=:CNY',
        'declarationAmount.value'                 => 'integer',

        'customs'                                 => 'require|array',
        'customs.customsCode'                     => 'require|string|max:28',
        'customs.region'                          => 'require|string|max:2|=:CN',

        'merchantCustomsInfo'                     => 'require|array',
        'merchantCustomsInfo.merchantCustomsCode' => 'require|string|max:128',
        'merchantCustomsInfo.merchantCustomsCode' => 'require|string|max:256',

        'splitOrder'                              => 'require|boolean',
    ],
    'message' => [

    ]
];