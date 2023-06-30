<?php
namespace Yng\AlipayGlobal\Request\Refund;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 查询退款状态
 */
class CheckRefundStatusRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/payments/inquiryRefund'; 

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
    private $refundId = '';

    /**
     * 获取已设置的退款请求订单编号
     * @return mixed
     */
    public function getRefundRequestId(): string
    {
        return $this->refundRequestId;
    }

    /**
     * 设置退款请求订单编号
     * @param mixed $refundRequestId
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
    public function getRefundId(): string
    {
        return $this->refundId;
    }

    /**
     * 设置退款请求订单编号
     * @param mixed $refundId
     */
    private function setRefundId(string $refundId)
    {
        $this->refundId = $refundId;
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

        $validator = new Validator('CheckRefundStatus');
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
