<?php

return [
    'rules' => [
        'grantType'         => 'require|string',
        'customerBelongsTo' => 'require|string|max:64',
        'authCode'          => 'string|requireIf:grantType,AUTHORIZATION_CODE|max:64',
        'refreshToken'      => 'string|requireIf:grantType,REFRESH_TOKEN|max:128',
        'merchantRegion'    => 'string|max:2',
    ],
    'message' => [
        'authCode.requireIf'     => 'When the value of grantType is AUTHORIZATION_CODE, the authCode field cannot be empty',
        'refreshToken.requireIf' => 'When the value of grantType is REFRESH_TOKEN, the refreshToken field cannot be empty',
    ]
];