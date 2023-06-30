<?php
namespace Yng\AlipayGlobal\Request\Online\Pay;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付--付款请求(收银员付款)
 * 捕获 API 用于从用户账户中获取授权付款的资金，然后将指定的付款金额转入商家账户
 */
class CapturePayRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/capture'; 

    /**
     * 商家分配的唯一ID
     * @var string REQUIRED
     */
    private $captureRequestId = '';


    /**
     * 付款编号
     * @var string REQUIRED
     */
    private $paymentId = '';

    /**
     * 付款金额
     * @var array REQUIRED
     */
    private $captureAmount = [
        'currency' => '',
        'value'    => 0,
    ];


    /**
     * 商家分配的用于标识捕获请求的唯一ID
     * @return mixed
     */
    public function getCaptureRequestId(): string
    {
        return $this->captureRequestId;
    }

    /**
     * 设置订单请求id
     * @param string $captureRequestId
     */
    private function setCaptureRequestId(string $captureRequestId)
    {
        $this->captureRequestId = $captureRequestId;
        return $this;
    }

    /**
     * 获取付款编号
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * 设置付款编号
     * @param string $paymentId
     */
    private function setPaymentId(string $paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * 获取付款金额
     * @return mixed
     */
    public function getCaptureAmount(): array
    {
        return $this->captureAmount;
    }

    /**
     * 设置付款金额
     * @param mixed $captureAmount
     */
    private function setCaptureAmount($captureAmount)
    {
        $this->captureAmount = $captureAmount;
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
        $validator = new Validator('CapturePay');
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
