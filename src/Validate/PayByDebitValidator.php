<?php

return [
    'rules' => [
        'paymentRequestId'                      => 'require|string|max:64',

        'order'                                 => 'require|array',
        'order.orderAmount'                     => 'require|array',
        'order.orderAmount.currency'            => 'require|string',
        'order.orderAmount.value'               => 'require|integer',

        'order.referenceOrderId'                => 'require|string|max:64',
        'order.orderDescription'                => 'require|string|max:256',

        'order.goods.referenceGoodsId'          => 'requireWith|string|max:64',
        'order.goods.goodsName'                 => 'requireWith|string|max:256',

        'order.goods.goodsUnitAmount.currency'  => 'requireWith|string',
        'order.goods.goodsUnitAmount.value'     => 'requireWith|integer',

        'paymentAmount'                         => 'require|array',
        'paymentAmount.currency'                => 'require|string',
        'paymentAmount.value'                   => 'integer',

        'paymentMethod'                         => 'require|array',
        'paymentMethod.paymentMethodType'       => 'require|string|max:64',
        'paymentMethod.paymentMethodId'         => 'require|string|max:128',

        // 'paymentRedirectUrl'                    => 'require|string|max:2048',
        // 'paymentNotifyUrl'                      => 'require|string|max:2048',
        'settlementStrategy'                    => 'require|array',
        'settlementStrategy.settlementCurrency' => 'require',

        'creditPayPlan'                         => 'array',
        'creditPayPlan.installmentNum'          => 'requireWith|string',
        'appId'                                 => 'requireIf:terminalType,MINI_APP|string|max:32',

    ],
    'message' => [
       
    ]
];