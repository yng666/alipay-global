<?php

return [
    'rules' => [
        'paymentRequestId'                         => 'require|string|max:64',

        'order'                                    => 'require|array',
        'order.orderAmount'                        => 'require|array',
        'order.orderAmount.currency'               => 'require|string',
        'order.orderAmount.value'                  => 'require|integer',
        'order.orderDescription'                   => 'require|string|max:256',

        'order.goods.referenceGoodsId'             => 'requireWith|string|max:64',
        'order.goods.goodsName'                    => 'requireWith|string|max:256',
        'order.goods.goodsUnitAmount.currency'     => 'requireWith|string',
        'order.goods.goodsUnitAmount.value'        => 'requireWith|integer',

        'order.merchant'                           => 'require|array',
        'order.merchant.referenceMerchantId'       => 'require|string|max:32',
        'order.merchant.merchantMCC'               => 'require|string|max:32',
        'order.merchant.merchantName'              => 'require|string|max:256',
        'order.merchant.merchantAddress.region'    => 'require|string',

        'order.merchant.store'                     => 'require|string',
        'order.merchant.store.referenceStoreId'    => 'require|string|max:64',
        'order.merchant.store.storeName'           => 'require|string|max:256',
        'order.merchant.store.storeMCC'            => 'require|string|max:32',
        'order.merchant.store.storeAddress.region' => 'require|string',

        'order.extendInfo.chinaExtraTransInfo.businessType' => 'require|string',

        'paymentAmount'                            => 'require|array',
        'paymentAmount.currency'                   => 'require|string',
        'paymentAmount.value'                      => 'integer',

        'paymentMethod'                            => 'require|array',
        'paymentMethod.paymentMethodType'          => 'require|string|max:32',

        'paymentNotifyUrl'                         => 'require|string|max:2048',

        'paymentFactor.inStorePaymentScenario'     => 'require|=:PaymentCode',

        'settlementStrategy.settlementCurrency'    => 'require',

    ],
    'message' => [
       
    ]
];