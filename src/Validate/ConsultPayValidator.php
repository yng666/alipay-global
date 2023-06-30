<?php

return [
    'rules' => [
        'paymentAmount'               => 'require|array',
        'paymentAmount.currency'      => 'require|string',
        'paymentAmount.value'         => 'integer',
        'userRegion'                  => 'string|max:2',
        'merchantRegion'              => 'string|max:2',
        'allowedPaymentMethodRegions' => 'array',
        'env'                         => 'require|array',
        'env.terminalType'            => 'require|string|in:WEB,WAP,APP,MINI_APP',
    ],
    'message' => [
       
    ]
];