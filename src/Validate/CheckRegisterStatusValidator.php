<?php

return [
    'rules' => [
        'referenceMerchantId'   => 'string|max:64|requireWithout:registrationRequestId',
        'registrationRequestId' => 'string|max:64|requireWithout:referenceMerchantId',
        'string'                => 'string|max:32',
        
    ],
    'message' => [

    ]
];