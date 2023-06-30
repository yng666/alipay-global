<?php

namespace Yng\AlipayGlobal;

use Exception;
use Yng\AlipayGlobal\Client\AlipayClient;
use Yng\AlipayGlobal\Exception\ErrorException;
use Yng\AlipayGlobal\Model\Amount;
use Yng\AlipayGlobal\Model\Buyer;
use Yng\AlipayGlobal\Model\ChinaExtraTransInfo;
use Yng\AlipayGlobal\Model\Endpoint;
use Yng\AlipayGlobal\Model\Env;
use Yng\AlipayGlobal\Model\ExtendInfo;
use Yng\AlipayGlobal\Model\Goods;
use Yng\AlipayGlobal\Model\Merchant;
use Yng\AlipayGlobal\Model\Order;
use Yng\AlipayGlobal\Model\PaymentMethod;
use Yng\AlipayGlobal\Model\ProductCodeType;
use Yng\AlipayGlobal\Model\SettlementStrategy;
use Yng\AlipayGlobal\Request\OnlinePay\Auth\AlipayAuthApplyTokenRequest;
// use Yng\AlipayGlobal\Request\OnlinePay\Auth\AlipayAuthConsultRequest;
use Yng\AlipayGlobal\Request\Notify\AlipayAcNotify;
use Yng\AlipayGlobal\Request\AlipayCommentPay;
use Yng\AlipayGlobal\Tool\Tool;
use Yng\AlipayGlobal\Tool\SignatureTool;
use Yng\AlipayGlobal\Model\HttpRpcResult;
use Yng\AlipayGlobal\Tool\Signature;

/**
 * 支付宝国际支付
 */
class AliPayGlobal
{
    /**
     * 支付宝实例
     * @var 
     */
    private $alipayClient;

    /**
     * 请求地址前缀
     */
    const PATH_PREFIX = '/ams/{sandbox}api/';

    /**
     * 默认key版本
     */
    const KEY_VERSION = 1;

    /**
     * 完整的请求地址
     */
    private $gatewayUrl = '';

    /**
     * 地区
     */
    private $area = '';

    private $client_id;
    private $is_sandbox;
    private $alipayPublicKey;
    private $merchantPrivateKey;



    /**
     * 初始配置
     * @param $params
     */
    public function __construct($params)
    {
        $params = array_merge(array(
            'client_id'          => '',
            'endpoint_area'      => 'ASIA',
            'merchantPrivateKey' => '',
            'alipayPublicKey'    => '',
            'is_sandbox'         => false,
        ), $params);
        
        $this->alipayPublicKey    = $params['alipayPublicKey'];
        $this->merchantPrivateKey = $params['merchantPrivateKey'];
        $this->client_id          = $params['client_id'];
        $this->is_sandbox         = $params['is_sandbox'];
        // $this->area               = $params['endpoint_area'];
        $this->gatewayUrl         = constant(Endpoint::class . '::' . $params['endpoint_area']);
        // var_dump($this);
    }

    /**
     * 使用RSA方式发送回调响应
     * @return void
     */
    public function sendNotifyResponseWithRSA()
    {
        $alipayAcNotify = new AlipayAcNotify();
        $alipayAcNotify->sendNotifyResponseWithRSA(array(
            'merchantPrivateKey' => $this->merchantPrivateKey,
        ));
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

    /**
     * 检测请求参数
     * @param $request
     * @return void
     * @throws Exception
     */
    private function checkRequestParam($request)
    {
        if (!isset($request)) {
            throw new Exception("alipayRequest can't null");
        }

        $clientId   = $request->getClientId();
        $httpMehod  = $request->getHttpMethod();
        $path       = $request->getPath();
        $keyVersion = $request->getKeyVersion();

        if (!isset($this->gatewayUrl) || empty(trim($this->gatewayUrl))) {
            throw new Exception("clientId can't null");
        }

        if (!isset($clientId) || empty(trim($clientId))) {
            throw new Exception("clientId can't null");
        }

        if (!isset($httpMehod) || empty(trim($httpMehod))) {
            throw new Exception("httpMehod can't null");
        }

        if (!isset($path) || empty(trim($path))) {
            throw new Exception("path can't null");
        }

        if (strpos($path, '/') != 0) {
            throw new Exception("path must start with /");
        }

        if (isset($keyVersion) && !is_numeric($keyVersion)) {
            throw new Exception("keyVersion must be numeric");
        }
    }


    /**
     * 获取完整的请求地址
     * @param $key
     * @return array|string|string[]
     */
    public function getPath($key)
    {
        return str_replace('{sandbox}', $this->is_sandbox ? 'sandbox/' : '', self::PATH_PREFIX . $key);
    }


    /**
     * 查找文件
     * @param $filename filename
     * @param $directory directory
     * @param string $path
     */
    private function findPath($filename,$directory)
    {
        // 获取当前所有目录
        $dir_arr = scandir($directory);

        $tmp = [];
        foreach($dir_arr as $val){
            if ($val === '.' || $val === '..') {
                continue;
            }

            // 构建文件或目录的完整路径
            $path = $directory . DIRECTORY_SEPARATOR . $val;

            if(is_dir($path)){
                $tmp_path = $this->findPath($filename, $path);

                if(empty($tmp_path)){
                    $tmp = [];
                }else{
                    if(is_file($tmp_path)){
                        if(basename($tmp_path) === $filename.'Request.php'){
                            $tmp = $tmp_path;
                            break;
                        }
                    }
                }
            }else{
                if(is_file($path)){
                    if(basename($path) === $filename.'Request.php'){
                        $tmp = $path;
                        break;
                    }
                }
            }
        }

        return $tmp;
    }


    /**
     * 生成完整的请求url地址
     * @param $path
     * @return string
     */
    private function genRequestUrl($path)
    {
        if (strpos($this->gatewayUrl, "https://") != 0) {
            $this->gatewayUrl = "https://" . $this->gatewayUrl;
        }

        if (substr_compare($this->gatewayUrl, '/', -strlen('/')) === 0) {
            $len = strlen($this->gatewayUrl);
            $this->gatewayUrl = substr($this->gatewayUrl, 0, $len - 1);
        }

        return $this->gatewayUrl . $path;

    }

    /**
     * 执行请求
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function run(array $params)
    {
        $model = isset($params['model']) ? $params['model'] : '';

        echo '<pre>';
        if(is_string($model)){
            $model_path = $this->findPath($model,__DIR__);

            if(empty($model_path)) return 'Invalid params: model';

            list($path_fix,$tmp_path) = explode('src',$model_path);
            $path = explode('.php',$tmp_path);
            $namespace = '\\Yng\\AlipayGlobal' . reset($path);
            $object = new $namespace;

            if(is_object($object)){
                $this->alipayClient = $object;
            }else{
                throw new ErrorException('Invalid params: model');
            }

        }elseif(is_object($model)){
            $this->alipayClient = new $model;
        }else{
            throw new ErrorException('Invalid params: model');
        }
        
        unset($params['model']);

        // 参数校验
        $format_res = $this->alipayClient->formatData($params);
       
        if($format_res['status'] === false){
            return $format_res['msg'];
        }
        $data = $format_res['msg'];
        $url = $this->getPath($data['path']);

        $req_time    = date('Y-m-d\TH:i:sO');// 格式如下:2023-06-28T16:57:40+0800
        $req_body    = json_encode($data);
        $http_method = $this->alipayClient::HTTP_METHOD;
        $signValue   = Signature::sign($http_method, $url, $this->client_id, $req_time, $req_body,$this->merchantPrivateKey);
        $headers     = Tool::buildBaseHeader($req_time,$this->client_id,self::KEY_VERSION,$signValue);

        // 获取完整的请求url地址
        $requestUrl = $this->genRequestUrl($url);

        // var_dump($req_body);die;

        $response = $this->sendRequest($requestUrl, $http_method, $headers, $req_body);
        if (!isset($response) || $response == null) {
            throw new Exception("HttpRpcResult is null.");
        }

        $rsp_body = $response->getRspBody();
        $rsp_time = $response->getRspTime();
        $rsp_sign = $response->getRspSign();

        $alipayRsp = json_decode($rsp_body);

        // var_dump($alipayRsp);die;

        $result = $alipayRsp->result;
        if (!isset($result)) {
            throw new Exception("Response data error,result field is null,rspBody:" . $rsp_body);
        }

        if (!isset($rsp_sign) || trim($rsp_sign) === "" || !isset($rsp_time) || trim($rsp_time) === "") {
            return $alipayRsp;
        }

        $isVerify = Signature::verify($http_method, $url, $this->client_id, $rsp_time, $rsp_body, $rsp_sign,$this->alipayPublicKey);

        if (!$isVerify) {
            throw new ErrorException("Response signature verify fail.");
        }

        return $alipayRsp;
    }

}
