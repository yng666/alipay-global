<?php
namespace Yng\AlipayGlobal\Request\Register;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 注册二级商户
 */
class RegisterMerchantRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/merchants/registration';


    /**
     * 标识注册请求的唯一ID
     * @var string REQUIRED
     */
    private $registrationRequestId = '';

    /**
     * 注册回调地址
     * @var string
     */
    private $registrationNotifyUrl = '';

    /**
     * 额外数据
     * @var array REQUIRED
     */
    private $passThroughInfo = [];

    /**
     * 商家信息
     * @var array REQUIRED
     */
    private $merchantInfo = [
        'referenceMerchantId' => '', // 服务或商品的商家的 ID，最大32字符
        'merchantMCC'         => '', // 商家mcc码,具体看文档
        'merchantDisplayName' => '', // 要显示的商家名称

        // 商家地址
        'merchantAddress' => [
            'region'   => '', // 国家地区简码
            'state'    => '', // 州、国家
            'city'     => '', // 城市
            'address1' => '', // 地址1
            'address2' => '', // 地址2
            'zipCode'  => '', // 邮编
            'label'    => '',//标签
        ],

        // 注册详情
        'registrationDetail' => [
            'legalName' => '', // 法定名称

            // 附件
            'attachments' => [

            ],
            
            // 公司联系方式
            'contactInfo' => [
                'contactNo'   => '', // 联系人号码
                'contactType' => '', // 联系人类型 MOBILE_PHONE / TELEPHONE / EMAIL
            ],
            'registrationType' => '', // 注册类型 ENTERPRISE_REGISTRATION_NO / INDEPENDENT_CONTRACTOR_LICENSE_NO / OTHER_IDENTIFICATION_NO / US_FEDERAL_EIN
            'registrationNo' => '', // 注册号

            // 注册地址
            'registrationAddress' => [
                'region'   => '', // 国家地区简码
                'state'    => '', // 州、国家
                'city'     => '', // 城市
                'address1' => '', // 地址1
                'address2' => '', // 地址2
                'zipCode'  => '', // 邮编
                'label'    => '',//标签
            ],
            'businessType' => '', // 业务类型
            'registrationEffectiveDate' => '', // 注册生效日期
            'registrationExpireDate' => '', // 注册到期日期
        ],

        // 网站
        'websites' => [
            'name' => '', // 网站名
            'url'  => '', // 网站地址
            'desc' => '', // 网站描述
        ],

        // 商标
        'logo' => [
            'logoName' => '', // 商标名
            'logoUrl' => '', // 商标链接
        ],
    ];

    /**
     * 产品代码
     * @var string REQUIRED
     */
    private $productCodes = 'IN_STORE_PAYMENT';

    /**
     * 商店信息
     * @var array
     */
    private $storeInfo = [
        'referenceStoreId' => '',// 二级商户关联的收单行提供的商店 ID
        'storeName' => '',// 商店名称
        'storeMCC'  => '',// 商店MCC
        'feeTier'   => '',// 费用等级 01/02/03/04/05

        // 商店地址
        'storeAddress' => [
            'region'   => '', // 国家地区简码
            'state'    => '', // 州、国家
            'city'     => '', // 城市
            'address1' => '', // 地址1
            'address2' => '', // 地址2
            'zipCode'  => '', // 邮编
            'label'    => '',//标签
        ],

        // 商店联系方式
        'storeContacts' => [
            'fullName'  => '', // 商店联系人的全名
            'contactNo' => '', // 商店联系人的全名
            'identificationId' => '', // 商店联系人的 ID
        ],
    ];


    /**
     * 获取已设置的标识注册请求的唯一ID
     * @return mixed
     */
    public function getRegistrationRequestId(): string
    {
        return $this->registrationRequestId;
    }

    /**
     * 设置标识注册请求的唯一ID
     * @param mixed $registrationRequestId
     */
    private function setRegistrationRequestId(string $registrationRequestId)
    {
        $this->registrationRequestId = $registrationRequestId;
        return $this;
    }


    /**
     * 获取已设置的注册回调地址
     * @return mixed
     */
    public function getRegistrationNotifyUrl(): string
    {
        return $this->registrationNotifyUrl;
    }

    /**
     * 设置注册回调地址
     * @param mixed $registrationNotifyUrl
     */
    private function setRegistrationNotifyUrl(string $registrationNotifyUrl)
    {
        $this->registrationNotifyUrl = $registrationNotifyUrl;
        return $this;
    }


    /**
     * 获取已设置的额外数据
     * @return mixed
     */
    public function getPassThroughInfo(): array
    {
        return $this->passThroughInfo;
    }

    /**
     * 设置额外数据
     * @param mixed $passThroughInfo
     */
    private function setPassThroughInfo(array $passThroughInfo)
    {
        $this->passThroughInfo = $passThroughInfo;
        return $this;
    }
    

    /**
     * 获取已设置的商家信息
     * @return mixed
     */
    public function getMerchantInfo(): array
    {
        return $this->merchantInfo;
    }

    /**
     * 设置商家信息
     * @param mixed $merchantInfo
     */
    private function setMerchantInfo(array $merchantInfo)
    {
        $this->merchantInfo = $merchantInfo;
        return $this;
    }

    /**
     * 获取已设置的产品代码
     * @return mixed
     */
    public function getProductCodes(): string
    {
        return $this->productCodes;
    }

    
    /**
     * 获取已设置的商店信息
     * @return mixed
     */
    public function getStoreInfo(): array
    {
        return $this->storeInfo;
    }

    /**
     * 设置商店信息
     * @param mixed $storeInfo
     */
    private function setStoreInfo(array $storeInfo)
    {
        $this->storeInfo = $storeInfo;
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
        $validator = new Validator('RegisterMerchant');
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