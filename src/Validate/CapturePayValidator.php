<?php

return [
    'rules' => [
        'captureRequestId'       => 'require|string|max:64',
        'paymentId'              => 'require|string|max:64',
        'captureAmount'          => 'require|array',
        'captureAmount.currency' => 'require|string',
        'captureAmount.value'    => 'integer',
    ],
    'message' => [
       
    ]
];