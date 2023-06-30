<?php
namespace Yng\AlipayGlobal\Request\Check;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 获取支付状态
 */
class OnlineCheckPayStatusRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/inquiryPayment';

    /**
     * 商家分配的唯一ID
     * @var string REQUIRED
     */
    private $paymentRequestId = '';

    /**
     * 付款编号
     * @var string REQUIRED
     */
    private $paymentId = '';

    /**
     * 获取支付编号
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * 设置支付编号
     * @param string $paymentId
     */
    public function setPaymentId(string $paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * 获取支付请求编号
     * @return string
     */
    public function getPaymentRequestId(): string
    {
        return $this->paymentRequestId;
    }

    /**
     * 设置支付请求编号
     * @param string $paymentRequestId
     */
    public function setPaymentRequestId(string $paymentRequestId)
    {
        $this->paymentRequestId = $paymentRequestId;
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
        $validator = new Validator('CheckPayStatus');
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
