<?php
namespace Yng\AlipayGlobal\Request\InStore\Pay;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 店内支付---创建支付(支付码方式)
 */
class PayPaymentCodeRequest
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
    private $productCode = 'IN_STORE_PAYMENT';

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
                
            // 商品价格属性
            'goodsUnitAmount' => [
                'currency' => '', // 货币类型
                'value'    => '', // 金额
            ],

            'goodsQuantity' => '', // 商品数量
            'goodsUrl'      => '', // 商品网址，最大2048
        ],


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
                'region' => '', // 国家地区简码
                'state' => '', // 州、国家
                'city' => '', // 城市
                'address1' => '', // 地址1
                'address2' => '', // 地址2
                'zipCode' => '', // 邮编
            ],
            'shippingCarrier' => '', // 运输服务商名
            'shippingPhoneNo' => '', // 发货人电话编号(包括分机号)

            // 买家信息
            'buyer' => [
                'referenceBuyerId' => '', // 买家id
                // 买家名
                'buyerName'        => [
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
                    'region' => '', // 国家地区简码
                    'state' => '', // 州、国家
                    'city' => '', // 城市
                    'address1' => '', // 地址1
                    'address2' => '', // 地址2
                    'zipCode' => '', // 邮编
                ],
                'merchantRegisterDate' => '', // 商家注册日期
                
                // 商店信息
                'store' => [
                    'referenceStoreId' => '', //由拥有商店的商家分配的唯一商店 ID
                    'storeName' => '', //商店名称
                    'storeMCC' => '', //商店MCC
                    'storeDisplayName' => '', //商店显示名称

                    // 商店地址
                    'storeAddress' => [
                        'region' => '', // 国家地区简码
                        'state' => '', // 州、国家
                        'city' => '', // 城市
                        'address1' => '', // 地址1
                        'address2' => '', // 地址2
                        'zipCode' => '', // 邮编
                    ], //
                ],
            ],

            // 关于订单的配置信息
            'env' =>[
                'terminalType'             => '',// 终端类型 WEB/WAP/APP/MINI_APP
                'osType'                   => '',// 操作系统 ANDROID/IOS
                'userAgent'                => '',// 用户代理
                'deviceTokenId'            => '',// 客户端设备标识
                'clientIp'                 => '',// 客户端ip
                'cookieId'                 => '',// cookieId
                'storeTerminalId'          => '',// 存储终端 ID
                'storeTerminalRequestTime' => '',// 存储终端发送请求的时间
                'extendInfo'               => '',// 扩展信息
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
     * 付款回调地址
     * @var string REQUIRED
     */
    private $paymentNotifyUrl = '';

    /**
     * 付款因素
     * @param array
     */
    private $paymentFactor = ['inStorePaymentScenario' => 'PaymentCode'];


    /**
     * 结算货币策略
     * 商家想要结算的货币的ISO货币代码,如果商家注册了多种结算货币，则需要此字段
     * @var array REQUIRED
     */
    private $settlementStrategy = ['settlementCurrency' => ''];
    

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
    private function setPaymentRequestId(array $paymentRequestId)
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
    private function setPaymentNotifyUrl(array $paymentNotifyUrl)
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
    private function setSettlementStrategy(string $settlementStrategy)
    {
        $this->settlementStrategy['settlementCurrency'] = $settlementStrategy;
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

        if(isset($params['paymentMethod'])){
            unset($params['paymentMethod']['paymentMethodType']);
            $params['paymentMethod'] = array_merge($this->paymentMethod,$params['paymentMethod']);
        }

        $validator = new Validator('PayPaymentCode');
        if($validator->check($params) === false){
            return ['status' => false,'msg' => $validator->getError()];
        }

        foreach($params as $key => $val){
            if(property_exists($this, $key)){
                $method = 'set' . ucfirst($key);

                $this->$method($val);
            }
        }

        $params['path']        = $this->path;
        $params['productCode'] = $this->productCode;

        return ['status' => true,'msg' => $params];
    }



}
