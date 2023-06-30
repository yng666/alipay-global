<?php
namespace Yng\AlipayGlobal\Request\Online\Pay;

use Yng\AlipayGlobal\Tool\Tool;
use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付---创建支付(收银员付款)
 */
class CreatePayRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/pay'; 

    /**
     * 产品类型
     * @var string REQUIRED
     */
    private $productCode = 'CASHIER_PAYMENT';


    /**
     * 支付请求订单编号(自定义,最大64字节)
     * 商家为识别支付请求而分配的唯一ID
     * @var string REQUIRED
     */
    private $paymentRequestId = '';

    /**
     * 订单信息
     * @var array REQUIRED
     */
    private $order = [
        'orderAmount' => [
            'currency' => '', // 货币类型
            'value'    => '', // 金额
        ],
        'referenceOrderId' => '', //在商家端标识订单的唯一ID
        'orderDescription' => '', //订单描述,最大256
        
        // 商品信息
        'goods' => [
            'referenceGoodsId' => '', // 商品唯一编号
            'goodsName'        => '', // 商品名,最大256
            'goodsCategory'    => '', // 商品分类，最大64，可使用/进行分割, eg: Digital Goods/Digital Vouchers/Food and Beverages
        ],

        // 商品价格属性
        'goodsUnitAmount' => [
            'currency' => '', // 货币类型
            'value'    => '', // 金额
        ],

        'goodsQuantity' => '', // 商品数量
        'goodsUrl'      => '', // 商品网址，最大2048

        // 配送信息
        'shipping' => [
            // 收件人的姓名
            'shippingName' => [
                'firstName'  => '', // 名字，最大32
                'middleName' => '', // 中间名，最大32
                'lastName'   => '', // 姓氏，最大32
                'fullName'   => '', // 全名，最大128
            ],
            // 收件人地址
            'shippingAddress' => [
                'region'   => '', // 国家地区简码
                'state'    => '', // 州、国家
                'city'     => '', // 城市
                'address1' => '', // 地址1
                'address2' => '', // 地址2
                'zipCode'  => '', // 邮编
            ],
            'shippingCarrier' => '', // 运输服务商名
            'shippingPhoneNo' => '', // 发货人电话编号(包括分机号)
            'shipToEmail'     => '', // 发送虚拟商品的电子邮件地址，最大64

            // 买家信息
            'buyer' => [
                'referenceBuyerId' => '', // 买家id
                // 买家名
                'buyerName' => [
                    'firstName'  => '', // 名字，最大32
                    'middleName' => '', // 中间名，最大32
                    'lastName'   => '', // 姓氏，最大32
                    'fullName'   => '', // 全名，最大128
                ],
                'buyerPhoneNo' => '', // 买家手机号码
                'buyerEmail'   => '', // 买家邮箱，最大64
            ],

            // 商家信息
            'merchant' => [
                'referenceMerchantId' => '', // 服务或商品的商家的 ID，最大32字符
                'merchantMCC'         => '', // 商家mcc码,具体看文档
                'merchantName'        => '', // 商家名
                'merchantDisplayName' => '', // 要显示的商家名称

                // 商家地址
                'merchantAddress' => [
                    'region'   => '', // 国家地区简码
                    'state'    => '', // 州、国家
                    'city'     => '', // 城市
                    'address1' => '', // 地址1
                    'address2' => '', // 地址2
                    'zipCode'  => '', // 邮编
                ],
                'merchantRegisterDate' => '', // 商家注册日期
            ],

            // 关于订单的配置信息
            'env' =>[
                'terminalType'    => '',// 终端类型 WEB/WAP/APP/MINI_APP
                'osType'          => '',// 操作系统 ANDROID/IOS
                'userAgent'       => '',// 用户代理
                'deviceTokenId'   => '',// 客户端设备标识
                'clientIp'        => '',// 客户端ip
                'websiteLanguage' => '',// 网站语言
                'deviceId'        => '',// 设备标识
                'extendInfo'      => '',// 扩展信息
            ],

            // 扩展信息
            'extendInfo' => [
                'chinaExtraTransInfo' => [
                    'businessType'       => '',// 业务类型
                    'flightNumber'       => '',// 航班号
                    'departureTime'      => '',// 航班起飞时间
                    'hotelName'          => '',// 酒店名称
                    'checkinTime'        => '',// 酒店入住时间
                    'admissionNoticeUrl' => '',// 学校录取通知网址
                    'goodsInfo'          => '',// 商品信息
                    'totalQuantity'      => '',// 一个订单中所有商品的总数,
                ],
            ],
        ],
    ];

    /**
     * 支付金额
     * @param array REQUIRED
     */
    private $paymentAmount = [
        'currency' => '', // 货币类型
        'value'    => '', // 金额
    ];


    /**
     * 付款因素
     * 当付款方法类型的值为 CARD 时，指定此参数
     * @param array
     */
    private $paymentFactor = ['isAuthorization' => null,];// true / false

    /**
     * 付款方式
     * @param array REQUIRED
     */
    private $paymentMethod = [
        'paymentMethodType'     => '',// 付款方式类型
        'paymentMethodId'       => '',// 付款方法ID
        'paymentMethodMetaData' => '',// 支付方法元数据
        'customerId'            => '',// 买家标识
        'customerId'            => '',// 扩展信息
    ];


    /**
     * 付款过期时间
     * @var string e.g.:2019-11-27T12:14:01+08:30
     */
    private $paymentExpiryTime = '';


    /**
     * 付款后重定向地址
     * @var string REQUIRED
     */
    private $paymentRedirectUrl = '';

    /**
     * 付款回调地址
     * @var string REQUIRED
     */
    private $paymentNotifyUrl = '';

    /**
     * 结算货币策略
     * 商家想要结算的货币的ISO货币代码,如果商家注册了多种结算货币，则需要此字段
     * @var array REQUIRED
     */
    private $settlementStrategy = [];
    

    /**
     * 用户归属地区
     * @var string
     */
    private $userRegion = '';


    /**
     * 付款计划信息
     * @var array
     */
    private $creditPayPlan = [
        'installmentNum'   => '',// 分期付款数
        'creditPayFeeType' => '',// 信用支付费用类型
        'feePercentage'    => 0,// 费用百分比
    ];


    /**
     * 支付宝分配的用于标识小程序的唯一ID
     * @var string
     */
    private $appId = '';

    /**
     * 商家经营区域
     * @var string
     */
    private $merchantRegion = '';


    /**
     * 获取已设置的支付请求订单编号
     * @return mixed
     */
    public function getPaymentRequestId(): string
    {
        return $this->paymentRequestId;
    }

    /**
     * 设置支付请求订单编号
     * @param mixed $paymentRequestId
     */
    private function setPaymentRequestId(string $paymentRequestId)
    {
        $this->paymentRequestId = $paymentRequestId;
        return $this;
    }

    /**
     * 获取已设置的订单信息
     * @return mixed
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    /**
     * 设置支付请求订单编号
     * @param mixed $order
     */
    private function setOrder(array $order)
    {
        $this->order = $order;
        return $this;
    }

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
     * 获取付款因素
     * @return mixed
     */
    public function getPaymentFactor(): array
    {
        return $this->paymentFactor;
    }

    /**
     * 设置付款因素
     * @param bool $paymentFactor
     */
    private function setPaymentFactor(bool $paymentFactor)
    {
        $this->paymentFactor['isAuthorization'] = $paymentFactor;
        return $this;
    }


    /**
     * 获取已设置的支付方式
     * @return mixed
     */
    public function getPaymentMethod(): array
    {
        return $this->paymentMethod;
    }

    /**
     * 设置支付方式
     * @param mixed $paymentMethod
     */
    private function setPaymentMethod(array $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * 获取支付过期时间
     * @return mixed
     */
    public function getPaymentExpiryTime(): string
    {
        return $this->paymentExpiryTime;
    }

    /**
     * 设置支付过期时间
     * @param mixed $paymentExpiryTime
     */
    private function setPaymentExpiryTime(string $paymentExpiryTime)
    {
        $this->paymentExpiryTime = $paymentExpiryTime;
        return $this;
    }


    /**
     * 获取已设置的支付后重定向的地址
     * @return mixed
     */
    public function getPaymentRedirectUrl(): string
    {
        return $this->paymentRedirectUrl;
    }

    /**
     * 设置支付后重定向的地址
     * @param mixed $paymentRedirectUrl
     */
    private function setPaymentRedirectUrl(string $paymentRedirectUrl)
    {
        $this->paymentRedirectUrl = $paymentRedirectUrl;
        return $this;
    }
    
    /**
     * 获取已设置的支付后回调地址
     * @return mixed
     */
    public function getPaymentNotifyUrl(): string
    {
        return $this->paymentNotifyUrl;
    }

    /**
     * 设置支付后回调地址
     * @param mixed $paymentNotifyUrl
     */
    private function setPaymentNotifyUrl(string $paymentNotifyUrl)
    {
        $this->paymentNotifyUrl = $paymentNotifyUrl;
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
    private function setSettlementStrategy(array $settlementStrategy)
    {
        $this->settlementStrategy = $settlementStrategy;
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
     * 获取已设置的付款计划
     */
    public function getCreditPayPlan(): array
    {
        return $this->creditPayPlan;
    }

    /**
     * 设置付款计划
     */
    private function setCreditPayPlan(array $creditPayPlan)
    {
        $this->creditPayPlan = $creditPayPlan;
        return $this;
    }

    /**
     * 获取已设置的付款计划
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * 设置付款计划
     */
    private function setAppId(string $appId)
    {
        $this->appId = $appId;
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
     * 组合请求体并验证参数
     * @param array $params
     * @throws Error
     * @return array
     */
    public function formatData(array $params)
    {

        $validator = new Validator('CreatePay');
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

        return ['status' => true,'msg' => $params];
    }


}
