<?php
namespace Yng\AlipayGlobal\Request\Online\Auth;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 支付宝授权咨询请求
 * 使用此接口获取用户授权。调用成功后，您可以获取授权 URL，并将用户重定向到授权 URL 以同意授权
 * 在用户同意授权后，钱包将用户重定向回使用authRedirectUrl，authCode和authState值重建的重定向URL
 */
class AuthConsultRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/authorizations/consult';


    /**
     * 客户使用钱包所属
     * 常用有: ALIPAY_CN / ALIPAY_HK / TRUEMONEY / TNG / GCASH / DANA / KAKAOPAY / EASYPAISA / BKASH
     * 具体值参考(https://global.alipay.com/docs/ac/ref/payment_method)表格中的国家对应Enum value值
     * @var string REQUIRED
     */
    private $customerBelongsTo = '';

    /**
     * 用户授予资源访问权限的二级商户的唯一ID
     * @var string
     */
    private $authClientId = '';

    /**
     * 用户同意授权后重定向到的重定向 URL,此值由商家提供
     * @var string REQUIRED
     */
    private $authRedirectUrl = '';

    /**
     * 授权范围
     * BASE_USER_INFO：表示可以获取唯一的用户 ID。
     * USER_INFO：表示可以获取完整的用户信息，例如用户名、头像和其他用户信息。
     * AGREEMENT_PAY：表示用户同意授权自动扣款，以便商家可以使用访问令牌自动从用户帐户中扣除资金
     * @var array REQUIRED
     */
    private $scopes = [];

    /**
     * 商家生成的唯一ID
     * @var string REQUIRED
     */
    private $authState = '';

    /**
     * 商户服务适用的终端类型
     * WEB：客户端终端类型是通过 Web 浏览器打开的网站。
     * WAP：客户端终端类型是通过移动浏览器打开的 HTML 页面。
     * APP：客户端终端类型为移动应用程序。
     * MINI_APP：商户端的终端类型为手机上的小程序。
     * @var string REQUIRED
     */
    private $terminalType = '';

    /**
     * 操作系统类型
     * 有效值为：IOS，ANDROID
     * 当terminalType为 MINI_APP、APP、WAP时，该字段为必填项
     * @var string
     */
    private $osType = '';

    /**
     * 操作系统版本
     * terminalType取值为 APP，WAP 时必填
     * @var string
     */
    private $osVersion = '';

    /**
     * 商家经营区域
     * @var string
     */
    private $merchantRegion = '';

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
     * 获取用户授权访问权限的二级商户的唯一ID
     * @return mixed
     */
    public function getAuthClientId(): string
    {
        return $this->authClientId;
    }

    /**
     * 设置用户授权访问权限的二级商户的唯一ID
     * 如果您是二级商户的收单行，请指定此字段
     * @param mixed $authClientId
     */
    private function setAuthClientId($authClientId)
    {
        $this->authClientId = $authClientId;
        return $this;
    }

    /**
     * 获取授权后重定向地址
     * @return string
     */
    public function getAuthRedirectUrl(): string
    {
        return $this->authRedirectUrl;
    }

    /**
     * 设置授权后重定向地址
     * @param string $authRedirectUrl
     */
    private function setAuthRedirectUrl(string $authRedirectUrl)
    {
        $this->authRedirectUrl = $authRedirectUrl;
        return $this;
    }

    /**
     * 获取授权范围
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * 设置授权范围
     * @param array $scopes
     */
    private function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * 获取授权码
     * @return string
     */
    public function getAuthState(): string
    {
        return $this->authState;
    }

    /**
     * @param string $authState
     */
    private function setAuthState($authState)
    {
        $this->authState = $authState;
        return $this;
    }

    /**
     * 获取终端类型
     * @return string
     */
    public function getTerminalType(): string
    {
        return $this->terminalType;
    }

    /**
     * 设置终端类型
     * @param string $terminalType REQUIRED
     */
    private function setTerminalType(string $terminalType)
    {
        $this->terminalType = $terminalType;
        return $this;
    }

    /**
     * 获取客户客户端系统类型
     * @return string
     */
    public function getOsType(): string
    {
        return $this->osType;
    }

    /**
     * 设置客户客户端系统类型
     * @param mixed $osType
     */
    private function setOsType(string $osType)
    {
        $this->osType = $osType;
        return $this;
    }

    /**
     * 获取操作系统版本
     * @return mixed
     */
    public function getOsVersion(): string
    {
        return $this->osVersion;
    }

    /**
     * 设置操作系统版本
     * @param mixed $osVersion
     */
    private function setOsVersion($osVersion)
    {
        $this->osVersion = $osVersion;
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
     * @return mixed
     */
    public function formatData(array $params)
    {

        $validator = new Validator('AuthConsult');
        if($validator->check($params) === false){
            return ['status' => false,'msg' => $validator->getError()];
        }

        if(isset($params['scopes']) && is_string($params['scopes'])){
            $scopes = $params['scopes'];
            unset($params['scopes']);
            $params['scopes'][] = $scopes;
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
