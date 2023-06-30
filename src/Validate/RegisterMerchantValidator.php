<?php

return [
    'rules' => [
        'registrationRequestId' => 'require|string|max:64',
        'merchantInfo' => 'require|array',
        'merchantInfo.referenceMerchantId' => 'require|string|max:64',
        'merchantInfo.merchantMCC' => 'require|string|max:32',

        'merchantInfo.registrationDetail' => 'require|array',
        'merchantInfo.registrationDetail.legalName' => 'require|string|max:256',

        'merchantInfo.registrationDetail.attachments' => 'array',
        'merchantInfo.registrationDetail.attachments.attachmentType' => 'require|string|in:ARTICLES_OF_ASSOCIATION,ENTERPRISES_ANNUAL_INSPECTION_REPORT,PROOF_OF_ADDRESS,REGISTRATION_CERTIFICATE',
        'merchantInfo.registrationDetail.attachments.file' => 'require|string|max:1024',

        'merchantInfo.registrationDetail.contactInfo' => 'array',
        'merchantInfo.registrationDetail.contactInfo.contactNo' => 'require|string|max:64',
        'merchantInfo.registrationDetail.contactInfo.contactType' => 'require|string|in:MOBILE_PHONE,TELEPHONE,EMAIL',

        'merchantInfo.registrationDetail.registrationType' => 'require|string|in:ENTERPRISE_REGISTRATION_NO,INDEPENDENT_CONTRACTOR_LICENSE_NO,OTHER_IDENTIFICATION_NO,US_FEDERAL_EIN',
        'merchantInfo.registrationDetail.registrationNo' => 'require|string|max:64',

        'merchantInfo.registrationDetail.registrationAddress' => 'require|array',
        'merchantInfo.registrationDetail.registrationAddress.region' => 'require|string',

        'merchantInfo.registrationDetail.businessType' => 'require|string|in:ENTERPRISE,INDIVIDUAL',


        'websites.url'  => 'require|string|max:2048',
        'logo.logoName' => 'require|string',

        'storeInfo'                     => 'array',
        'storeInfo.referenceStoreId'    => 'require|string|max:32',
        'storeInfo.storeName'           => 'require|string|max:256',
        'storeInfo.storeMCC'            => 'require|string|max:32',
        'storeInfo.storeAddress'        => 'require|array',
        'storeInfo.storeAddress.region' => 'require|string',

        'storeInfo.storeContacts'          => 'array',
        'storeInfo.storeContacts.fullName' => 'require|string|max:128',
        'storeInfo.storeContacts.identificationId' => 'require|string|max:64',
    ],
    'message' => [

    ]
];