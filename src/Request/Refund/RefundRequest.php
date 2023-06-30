<?php
namespace Yng\AlipayGlobal\Request\Refund;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付---退款
 */
class RefundRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/refund'; 


    /**
     * 退款请求编号
     * 商家为标识退款请求而分配的唯一 ID
     * @var string REQUIRED
     */
    private $refundRequestId = '';


    /**
     * 付款编号
     * 支付宝为原始付款退款分配的唯一ID
     * @var string REQUIRED
     */
    private $paymentId = '';

    /**
     * 支付金额
     * @param array REQUIRED
     */
    private $refundAmount = [
        'currency' => '', // 货币类型
        'value'    => '', // 金额
    ];


    /**
     * 退款原因
     * @param string
     */
    private $refundReason = '';


    /**
     * 退款回调地址
     * @var string REQUIRED
     */
    private $refundNotifyUrl = '';


    /**
     * 获取已设置的退款请求订单编号
     * @return string
     */
    public function getRefundRequestId(): string
    {
        return $this->refundRequestId;
    }

    /**
     * 设置退款请求订单编号
     * @param string $refundRequestId
     */
    private function setRefundRequestId(string $refundRequestId)
    {
        $this->refundRequestId = $refundRequestId;
        return $this;
    }


    /**
     * 获取已设置的退款请求订单编号
     * @return mixed
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * 设置退款请求订单编号
     * @param mixed $paymentId
     */
    private function setPaymentId(string $paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }


    /**
     * 获取已设置的退款金额
     * @return mixed
     */
    public function getRefundAmount(): array
    {
        return $this->refundAmount;
    }

    /**
     * 设置退款金额
     * @param mixed $refundAmount
     */
    private function setRefundAmount(array $refundAmount)
    {
        $this->refundAmount = $refundAmount;
        return $this;
    }



    /**
     * 获取已设置的退款原因
     * @return mixed
     */
    public function getRefundReason(): string
    {
        return $this->refundReason;
    }

    /**
     * 设置退款原因
     * @param string $refundReason
     */
    private function setRefundReason(string $refundReason)
    {
        $this->refundReason = $refundReason;
        return $this;
    }

    /**
     * 获取退款回调地址
     * @return mixed
     */
    public function getRefundNotifyUrl(): string
    {
        return $this->refundNotifyUrl;
    }

    /**
     * 设置退款回调地址
     * @param mixed $refundNotifyUrl
     */
    private function setRefundNotifyUrl(string $refundNotifyUrl)
    {
        $this->refundNotifyUrl = $refundNotifyUrl;
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

        $validator = new Validator('Refund');
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
