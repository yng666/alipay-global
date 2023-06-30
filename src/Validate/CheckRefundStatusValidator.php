<?php

return [
    'rules' => [
        'refundId'        => 'requireWithout:refundRequestId',
        'refundRequestId' => 'requireWithout:refundId',
    ],
    'message' => [
    ]
];