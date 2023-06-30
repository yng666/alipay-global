<?php
namespace Yng\AlipayGlobal\Request\Online\Pay;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付---查询申报状态
 */
class CheckDeclareStatusRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/customs/inquiryDeclarationRequests'; 

    /**
     * 退款请求编号
     * 商家为标识商家分配的唯一 ID，一次性最多10个
     * @var array REQUIRED
     */
    private $declarationRequestIds = [];

    /**
     * 获取已设置的请求订单编号
     * @return mixed
     */
    public function getDeclarationRequestIds(): array
    {
        return $this->declarationRequestIds;
    }

    /**
     * 设置退款请求订单编号
     * @param mixed $declarationRequestIds
     */
    private function setDeclarationRequestIds(array $declarationRequestIds)
    {
        $this->declarationRequestIds = $declarationRequestIds;
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
        $validator = new Validator('CheckDeclareStatus');
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
