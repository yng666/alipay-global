<?php

return [
    'rules' => [
        'refundRequestId'       => 'require|string|max:64',
        'paymentId'             => 'require|string|max:64',
        'refundAmount'          => 'require|array',
        'refundAmount.currency' => 'require|string',
        'refundAmount.value'    => 'integer',
    ],
    'message' => [

    ]
];