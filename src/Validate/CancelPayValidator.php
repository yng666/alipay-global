<?php

return [
    'rules' => [
        'paymentId'        => 'requireWithout:paymentRequestId',
        'paymentRequestId' => 'requireWithout:paymentId',
    ],
    'message' => [
    ]
];