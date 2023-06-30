<?php

return [
    'rules' => [
        'customerBelongsTo' => 'require|string|max:64',
        'authClientId'      => 'string|max:64',
        'authRedirectUrl'   => 'require|string|max:1024',
        'scopes'            => 'require|string|in:BASE_USER_INFO,USER_INFO,AGREEMENT_PAY',
        'authState'         => 'require|string|max:256',
        'terminalType'      => 'require|string|in:WEB,WAP,APP,MINI_APP',
        'osType'            => 'string|requireWhen:terminalType,WAP,APP,MINI_APP',
        'osVersion'         => 'string|max:16',
        'merchantRegion'    => 'string|max:2',
    ],
    'message' => [
        'osType.requireWhen' => 'When the value of terminalType is WAP/APP/MINI_APP, the osType field cannot be empty',
    ]
];
