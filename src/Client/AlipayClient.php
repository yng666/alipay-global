<?php

namespace Yng\AlipayGlobal\Client;

use Yng\AlipayGlobal\Model\HttpRpcResult;

/**
 * 支付宝国际支付客户端类
 */
class AlipayClient extends BaseAlipayClient
{
    /**
     * @param $gatewayUrl
     * @param $merchantPrivateKey
     * @param $alipayPublicKey
     */
    public function __construct($gatewayUrl, $merchantPrivateKey, $alipayPublicKey)
    {
        parent::__construct($gatewayUrl, $merchantPrivateKey, $alipayPublicKey);
    }

    /**
     * @return null
     */
    protected function buildCustomHeader()
    {
        return null;
    }

    /**
     * 发送请求
     * @param $requestUrl
     * @param $httpMethod
     * @param $headers
     * @param $reqBody
     * @return HttpRpcResult|null
     */
    protected function sendRequest($requestUrl, $httpMethod, $headers, $reqBody)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $reqBody);

        $rspContent = curl_exec($curl);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != '200') {
            return null;
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerContent = substr($rspContent, 0, $headerSize);
        $rspBody = substr($rspContent, $headerSize);

        $httpRpcResult = new HttpRpcResult();
        $httpRpcResult->setRspBody($rspBody);

        $headArr = explode("\r\n", $headerContent);
        foreach ($headArr as $headerItem) {
            if (strstr($headerItem, "response-time") || strstr($headerItem, "signature")) {
                $responseTime = $this->getResponseTime($headerItem);
                if (isset($responseTime) && $responseTime != null) {
                    $httpRpcResult->setRspTime(trim($responseTime));
                } else {
                    $signatureValue = $this->getResponseSignature($headerItem);
                    if (isset($signatureValue) && $signatureValue != null) {
                        $httpRpcResult->setRspSign($signatureValue);
                    }
                }
            }
        }

        curl_close($curl);

        return $httpRpcResult;
    }

    /**
     * 获取响应时间
     * @param $headerItem
     * @return false|string|null
     */
    private function getResponseTime($headerItem)
    {
        if (strstr($headerItem, "response-time")) {
            $startIndex = strpos($headerItem, ":") + 1;
            return substr($headerItem, $startIndex);
        }
        return null;
    }

    /**
     * 获取请求头中的签名
     * @param $headerItem
     * @return false|string|null
     */
    private function getResponseSignature($headerItem)
    {
        if (strstr($headerItem, "signature")) {
            $startIndex = strrpos($headerItem, "=") + 1;
            return substr($headerItem, $startIndex);
        }
        return null;
    }
}
