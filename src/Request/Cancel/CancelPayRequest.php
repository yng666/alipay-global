<?php
namespace Yng\AlipayGlobal\Request\Cancel;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 取消订单
 */
class CancelPayRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    public $path = 'v1/payments/cancel'; 

    /**
     * 付款编号(两参数不能全为空)
     * @var string
     */
    public $paymentId = '';

    /**
     * 付款请求编号(两参数不能全为空)
     * @var string
     */
    public $paymentRequestId = '';

    /**
     * 获取付款编号
     * @return mixed
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId(string $paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * 获取付款请求编号
     * @return mixed
     */
    public function getPaymentRequestId(): string
    {
        return $this->paymentRequestId;
    }

    /**
     * 设置付款请求编号
     * @param mixed $paymentRequestId
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
        $validator = new Validator('CancelPay');
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
