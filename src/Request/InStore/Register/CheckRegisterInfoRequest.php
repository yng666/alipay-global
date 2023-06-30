<?php
namespace Yng\AlipayGlobal\Request\Register;

use Yng\AlipayGlobal\Validate\Validator;

/**
 * 查询二级商户注册信息
 */
class CheckRegisterInfoRequest
{
    // 定义请求方法类型
    const HTTP_METHOD = 'POST';

    /**
     * 请求地址
     * @var string
     */
    private $path = 'v1/merchants/inquiryRegistrationInfo';


    /**
     * 注册商家标识
     * @var string REQUIRED
     */
    private $referenceMerchantId = '';


    /**
     * 与商家关联的商店ID
     * @var array
     */
    private $referenceStoreId = [];



    /**
     * 获取已设置的注册商家标识
     * @return mixed
     */
    public function getReferenceMerchantId(): string
    {
        return $this->referenceMerchantId;
    }

    /**
     * 设置注册商家标识
     * @param mixed $referenceMerchantId
     */
    private function setReferenceMerchantId(string $referenceMerchantId)
    {
        $this->referenceMerchantId = $referenceMerchantId;
        return $this;
    }

    /**
     * 获取已设置的与商家关联的商店ID
     * @return mixed
     */
    public function getReferenceStoreId(): array
    {
        return $this->referenceStoreId;
    }

    /**
     * 设置与商家关联的商店ID
     * @param mixed $referenceStoreId
     */
    private function setReferenceStoreId(array $referenceStoreId)
    {
        $this->referenceStoreId = $referenceStoreId;
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
        $validator = new Validator('CheckRegisterInfo');
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