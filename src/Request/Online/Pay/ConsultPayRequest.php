<?php
namespace Yng\AlipayGlobal\Request\Online\Pay;

use Yng\AlipayGlobal\Tool\Tool;
use Yng\AlipayGlobal\Validate\Validator;

/**
 * 咨询请求
 * 返回有关不同付款方式及其相应金额、国家/地区、货币、规则和配置的信息
 */
class ConsultPayRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/consult';

    /**
     * 产品类型
     * @var string REQUIRED
     */
    private $productCode = 'CASHIER_PAYMENT';

    /**
     * 支付金额
     * @param array REQUIRED
     */
    private $paymentAmount = [
        'currency' => '', // 货币类型
        'value'    => '', // 金额
    ];

    /**
     * 用户归属地区
     * @var string
     */
    private $userRegion = '';

    /**
     * 商家经营区域
     * @var string
     */
    private $merchantRegion = '';


    /**
     * 结算货币策略
     * 商家想要结算的货币的ISO货币代码,如果商家注册了多种结算货币，则需要此字段
     * @var array
     */
    private $settlementStrategy = ['settlementCurrency' => ''];
    

    /**
     * 返回特定地区的可用支付方式
     * @var array 支付方式的国家或地区的地区代码列表
     */
    private $allowedPaymentMethodRegions = [];


    /**
     * 关于订单的配置信息
     * @var array REQUIRED
     */
    private $env = [
        'terminalType'  => '',//终端类型 WEB/WAP/APP/MINI_APP
        'osType'        => '',//操作系统 ANDROID/IOS
        'userAgent'     => '',//用户代理
        'deviceTokenId' => '',//客户端设备标识
        'clientIp'      => '',//客户端ip
        'cookieId'      => '',//客户端cookieId
    ];
    

    /**
     * 获取已设置的支付金额
     * @return mixed
     */
    public function getPaymentAmount(): array
    {
        return $this->paymentAmount;
    }

    /**
     * 设置支付金额
     * @param mixed $paymentAmount
     */
    private function setPaymentAmount(array $paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }


    /**
     * 获取用户归属地
     * @return mixed
     */
    public function getUserRegion(): string
    {
        return $this->userRegion;
    }

    /**
     * 设置用户归属地
     * @param mixed $userRegion
     */
    private function setUserRegion($userRegion)
    {
        $this->userRegion = $userRegion;
        return $this;
    }


    /**
     * 获取商户归属地区
     */
    public function getMerchantRegion(): string
    {
        return $this->merchantRegion;
    }

    /**
     * 设置商户归属地区
     */
    private function setMerchantRegion(string $merchantRegion)
    {
        $this->merchantRegion = $merchantRegion;
        return $this;
    }

    /**
     * 获取结算货币策略
     */
    public function getSettlementStrategy(): array
    {
        return $this->settlementStrategy;
    }

    /**
     * 设置结算货币策略
     */
    private function setSettlementStrategy(string $settlementStrategy)
    {
        $this->settlementStrategy['settlementCurrency'] = $settlementStrategy;
        return $this;
    }

    /**
     * 获取支付方式的国家或地区代码
     */
    public function getAllowedPaymentMethodRegions(): array
    {
        return $this->allowedPaymentMethodRegions;
    }

    /**
     * 设置支付方式的国家或地区代码
     */
    private function setAllowedPaymentMethodRegions(array $allowedPaymentMethodRegions)
    {
        $this->allowedPaymentMethodRegions = $allowedPaymentMethodRegions;
        return $this;
    }


    /**
     * 获取支付方式的国家或地区代码
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * 设置支付方式的国家或地区代码
     */
    private function setEnv(array $env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * 组合请求体并验证参数
     * @param array $params
     * @throws Error
     * @return array
     */
    public function formatData(array $params)
    {
        // var_dump($params);die;
        $validator = new Validator('ConsultPay');
        if($validator->check($params) === false){
            return ['status' => false,'msg' => $validator->getError()];
        }

        foreach($params as $key => $val){
            if(property_exists($this, $key)){
                $method = 'set' . ucfirst($key);

                $this->$method($val);
            }
        }

        $params['path']          = $this->path;
        $params['productCode']   = $this->productCode;
        
        // $params['paymentAmount'] = json_encode($params['paymentAmount']);
        // $params['env']           = json_encode($params['env']);

        if(isset($parmas['settlementStrategy'])){
            $params['settlementStrategy'] = json_encode($params['settlementStrategy'],JSON_UNESCAPED_UNICODE);
        }

        // var_dump($params);die;
        return ['status' => true,'msg' => $params];
    }

}
