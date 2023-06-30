<?php
namespace Yng\AlipayGlobal\Request\Online\Auth;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 在线支付---授权---申请令牌请求
 * @method object setGrantType(string $grantType) 设置授权类型
 */
class ApplyTokenRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/authorizations/applyToken';

    /**
     * 授权类型
     * AUTHORIZATION_CODE：如果要获取访问令牌，请使用此值。调用该接口成功后，可以获得访问令牌。
     * REFRESH_TOKEN：每个访问令牌都有一个由访问令牌到期时间指定的到期时间。当访问令牌即将过期并且你想要获取新的访问令牌时，请使用此值。成功调用此 API 后，可以获取新的访问令牌。
     * @var string REQUIRED
     */
    private $grantType = '';

    /**
     * 客户使用钱包所属
     * 常用有: ALIPAY_CN / ALIPAY_HK / TRUEMONEY / TNG / GCASH / DANA / KAKAOPAY / EASYPAISA / BKASH
     * 具体值参考(https://global.alipay.com/docs/ac/ref/payment_method)表格中的国家对应Enum value值
     * @var string REQUIRED
     */
    private $customerBelongsTo = '';

    /**
     * 授权码
     * 当grantType为AUTHORIZATION_CODE时填该字段
     * 授权返回跳转的url中获取
     * @var string
     */
    private $authCode = '';

    /**
     * 刷新token
     * 当grantType为REFRESH_TOKEN时填该字段
     * @var string
     */
    private $refreshToken = '';


    /**
     * 获取已设置的授权的类型
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * 设置授权类型
     * @param string $grantType
     */
    private function setGrantType(string $grantType)
    {
        $this->grantType = $grantType;
        return $this;
    }

    /**
     * 获取用户使用钱包归属
     * @return string
     */
    public function getCustomerBelongsTo(): string
    {
        return $this->customerBelongsTo;
    }

    /**
     * 设置用户使用钱包归属
     * @param string $customerBelongsTo
     */
    private function setCustomerBelongsTo(string $customerBelongsTo)
    {
        $this->customerBelongsTo = $customerBelongsTo;
        return $this;
    }

    /**
     * 获取授权码
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * 设置授权码
     * @param string $authCode
     */
    private function setAuthCode(string $authCode)
    {
        $this->authCode = $authCode;
        return $this;
    }

    /**
     * 获取已设置的刷新token值
     * @return mixed
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * 设置刷新token值
     * @param string $refreshToken
     */
    private function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
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
        $validator = new Validator('ApplyToken');
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
