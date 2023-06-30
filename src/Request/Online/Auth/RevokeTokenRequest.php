<?php
namespace Yng\AlipayGlobal\Request\Online\Auth;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 支付宝授权撤销令牌请求
 */
class RevokeTokenRequest
{   
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/authorizations/revoke';

    /**
     * 授权token
     * @var string REQUIRED
     */
    public $accessToken = '';


    /**
     * 获取token
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * 设置token
     * @param mixed $accessToken
     */
    private function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
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
        $validator = new Validator('RevokeToken');
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
