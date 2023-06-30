<?php
namespace Yng\AlipayGlobal\Request\Online\Pay;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付---将信息传输给海关
 */
class DeclareRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/customs/declare'; 

    /**
     * 申报请求标识
     * 商家为标识申报请求而分配的唯一 ID
     * @var string REQUIRED
     */
    private $declarationRequestId = '';

    /**
     * 付款编号
     * 支付宝为原始付款支付分配的唯一ID
     * @var string REQUIRED
     */
    private $paymentId = '';

    /**
     * 申报金额
     * @param array REQUIRED
     */
    private $declarationAmount = [
        'currency' => 'CNY', // 货币类型,默认CNY
        'value'    => 0, // 金额
    ];

    /**
     * 海关信息
     * @param string REQUIRED
     */
    private $customs = [
        'customsCode' => '', //海关代码
        'region'      => 'CN', //地区,官方仅支持中国海关
    ];


    /**
     * 商家海关信息
     * @var array REQUIRED
     */
    private $merchantCustomsInfo = [
        'merchantCustomsCode' => '', //商家海关代码
        'merchantCustomsName' => '', //商家海关名称,海关系统中的商家备案名称
    ];

    /**
     * 商家可以决定是否拆分订单进行申报
     * @var boolean REQUIRED
     */
    private $splitOrder = false;

    /**
     * 商家分配的子订单 ID
     * @var boolean REQUIRED
     */
    private $suborderId = '';


    /**
     * 买家信息
     * @var array
     */
    private $buyerCertificate = [
        'certificateType' => '', //买家身份证明类型
        'certificateNo' => '', //买方的识别号
        'holderName' => [
        'firstName'  => '', // 名字，最大32
            'middleName' => '', // 中间名，最大32
            'lastName'   => '', // 姓氏，最大32
            'fullName'   => '', // 全名，最大128
        ],
    ];

    /**
     * 获取已设置申报请求订单编号
     * @return mixed
     */
    public function getDeclarationRequestId(): string
    {
        return $this->declarationRequestId;
    }

    /**
     * 设置申报请求订单编号
     * @param mixed $declarationRequestId
     */
    private function setDeclarationRequestId(array $declarationRequestId)
    {
        $this->declarationRequestId = $declarationRequestId;
        return $this;
    }


    /**
     * 获取已设置的请求订单编号
     * @return mixed
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * 设置请求订单编号
     * @param mixed $paymentId
     */
    private function setPaymentId(array $paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }


    /**
     * 获取已设置的申报金额
     * @return mixed
     */
    public function getDeclarationAmount(): array
    {
        return $this->declarationAmount;
    }

    /**
     * 设置申报金额
     * @param mixed $declarationAmount
     */
    private function setDeclarationAmount(array $declarationAmount)
    {
        $this->declarationAmount = $declarationAmount;
        return $this;
    }


    /**
     * 获取已设置的海关信息
     * @return mixed
     */
    public function getCustoms(): array
    {
        return $this->customs;
    }

    /**
     * 设置海关信息
     * @param array $customs
     */
    private function setCustoms(array $customs)
    {
        $this->customs = $customs;
        return $this;
    }

    /**
     * 获取已设置的海关信息
     * @return array
     */
    public function getMerchantCustomsInfo(): array
    {
        return $this->merchantCustomsInfo;
    }

    /**
     * 设置海关信息
     * @param array $merchantCustomsInfo
     */
    private function setMerchantCustomsInfo(array $merchantCustomsInfo)
    {
        $this->merchantCustomsInfo = $merchantCustomsInfo;
        return $this;
    }


    /**
     * 获取退款回调地址
     * @return mixed
     */
    public function getSplitOrder(): bool
    {
        return $this->splitOrder;
    }

    /**
     * 设置退款回调地址
     * @param mixed $splitOrder
     */
    private function setSplitOrder(bool $splitOrder)
    {
        $this->splitOrder = $splitOrder;
        return $this;
    }


    /**
     * 获取商家分配的子订单 ID
     * @return string
     */
    public function getSuborderId(): string
    {
        return $this->suborderId;
    }

    /**
     * 设置商家分配的子订单 ID
     * @param string $suborderId
     */
    private function setSuborderId(string $suborderId)
    {
        $this->suborderId = $suborderId;
        return $this;
    }


    /**
     * 获取买家信息
     * @return string
     */
    public function getBuyerCertificate(): array
    {
        return $this->buyerCertificate;
    }

    /**
     * 设置买家信息
     * @param array $buyerCertificate
     */
    private function setBuyerCertificate(array $buyerCertificate)
    {
        $this->buyerCertificate = $buyerCertificate;
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
        $validator = new Validator('Declare');
        if($validator->check($params) === false){
            return ['status' => false,'msg' => $validator->getError()];
        }

        foreach($params as $key => $val){
            if(property_exists($this, $key)){
                $method = 'set' . ucfirst($key);

                $this->$method($val);
            }
        }

        $params['path'] = $this->path;

        return ['status' => true,'msg' => $params];
    }


}
