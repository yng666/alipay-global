<?php

namespace Yng\AlipayGlobal\Tool;

use Yng\AlipayGlobal\Exception\ErrorException;

/**
 * tool
 */
class Tool
{

    /**
     * 构建header头部
     * @param $requestTime 请求时间
     * @param $clientId    客户端id
     * @param $keyVersion  key版本
     * @param $signValue   签名
     * @return array
     */
    public static function buildBaseHeader($requestTime, $clientId, $keyVersion, $signValue)
    {
        $baseHeader = array();
        $baseHeader[] = "Content-Type:application/json; charset=UTF-8";
        $baseHeader[] = "User-Agent:global-alipay-sdk-php";
        $baseHeader[] = "Request-Time:" . $requestTime;
        $baseHeader[] = "client-id:" . $clientId;

        if (!isset($keyVersion)) {
            $keyVersion = 1;
        }
        $signatureHeader = "algorithm=RSA256,keyVersion=" . $keyVersion . ",signature=" . $signValue;
        $baseHeader[] = "Signature:" . $signatureHeader;
        return $baseHeader;
    }




    /**
     * @return string
     */
    public static function CreateId()
    {
        list($ms) = explode(' ', microtime());
        return date('YmdHis') . ($ms * 1000000) . rand(00, 99);
    }

    /**
     * @return string
     */
    public static function CreateReferenceOrderId()
    {
        return 'ORDER-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreatePaymentRequestId()
    {
        return 'PAYMENT-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreatePaymentMethodId()
    {
        return 'PAYMENTMETHOD-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreateBuyerId()
    {
        return 'BUYER-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreateReferenceGoodsId()
    {
        return 'GOODS-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreateReferenceMerchantId()
    {
        return 'MERCHANT-' . self::CreateId();
    }

    /**
     * @return string
     */
    public static function CreateAuthState()
    {
        return 'STATE-' . self::CreateId();
    }


}
