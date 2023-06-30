# PHP版支付宝国际版SDK

基于支付宝国际版[PHP SDK](https://github.com/alipay/global-open-sdk-php)
官方SDK主要展示如何访问支付宝网关，并无授权、自动借记等完整功能，因此我简单的优化部分接口，原接口保留，进一步实现了支付宝全球A+的标准接口，更加简单实用，如有不正确的还请提issue!

## 安装
```Shell
composer require yng/alipay-global
```

###### 文档

[货币单位](https://global.alipay.com/docs/ac/ref/cc#ONkIe)

[MCC](https://global.alipay.com/docs/ac/ref/mcccodes)

[支付方式](https://global.alipay.com/docs/ac/ref/payment_method)表格中国家对应Enum value值

[支付宝帮助](https://icsmada.alipay.com/enterprise/global/klgList)

[所有api数据字典(会有部分未更新)](https://global.alipay.com/docs/ac/amsapi/datadic)

#### 配置问题
支付宝有沙盒工具可以测试，但是它们有版本区分，***互不兼容***，为了对应官方全球支付包所以这里使用国际版的沙盒，国内版就请参考[国内版文档](https://global.alipay.com/docs/ac/legacy/legacydoc)

[国际版](https://global.alipay.com/open/console/developer/app/detail)

[国内版](https://isandbox.alipaydev.com/user/intlAccountDetails.htm)

---

>merchantPrivateKey

merchantPrivateKey 商户私钥: 工具生成, 请自行保存好, 去掉头和尾, 只要中间那行代码

>alipayPublicKey

alipayPublicKey 支付宝公钥: 沙盒首页 -> Integration Settings -> 找到Integration Information下面有个配置public key的按钮, 点击然后使用官方提供的工具生成公钥秘钥, 把工具生成的公钥复制到网页对应位置,提交后会有两个公钥, 一个是商户公钥一个支付宝公钥, 支付宝公钥是根据你提供的公钥生成的，所以配置里的支付宝公钥就是这里来的, 请自行保存好, 去掉头和尾, 只要中间那行代码

>auth_client_id

auth_client_id 二级商家id: 如果是二级商户,需要填写此字段

>client_id

client_id 客户端id: 沙盒首页的Client ID

---


###### 生成RSA2工具

[windows](https://gw.alipayobjects.com/os/basement_prod/941d177c-094b-4cd9-a1c0-7c00aaeda66e.zip)

[mac](https://gw.alipayobjects.com/os/bmw-prod/f5c52119-8105-449f-baf5-0a9ae0390d01.zip)


---


###### 测试工具

[文档地址](https://global.alipay.com/docs/ac/ref/testwallet)

[Android](https://mdn.alipayobjects.com/portal_x5lg3u/afts/file/A*z0umSp729gAAAAAAAAAAAAAAAQAAAQ)

>IOS安装教程:
1. 从App Store安装TestFlight。
2. 使用 iPhone 浏览器打开此链接：https://testflight.apple.com/join/r1wEDEAS
3. 单击“开始测试”开始测试。
4. 在测试飞行中安装IAP_Wallet。


测试账号

| 账号 | 密码 |
| :-- |  :-- |
| amsmerchant01@163.com | Alipay123 |
| amsmerchant02@163.com | Alipay123 |
| amsmerchant03@163.com | Alipay123 |

---


###### DEMO示例
只展示常用的授权，支付使用示例，所展示的示例里的参数全是基础必填参，可选参里的必填参请自行参考文档。

官方sdk或多或少存留着已删除的一些api或字段,请参考[地址](https://global.alipay.com/docs/ac/general/release-notes)

***支付宝官方原接口保留,如想使用官方php-sdk-api请自行参考[github官方文档](https://github.com/alipay/global-open-sdk-php)***


###### 所有model方法列表

| 方法名 | 描述 |
| :--   | :--  |
| AuthConsult         | 授权 |
| ApplyToken          | 申请token或刷新token |
| RevokeToken         | 撤销token令牌 |
| ConsultPay          | 在线支付--查询支持支付的可选项 |
| CreatePay           | 在线支付--创建支付订单 |
| CapturePay          | 在线支付--付款请求 |
| PayByDebit          | 在线支付--自动借记 |
| PayEntryCode        | 店内支付--EntryCode模式 |
| PayOrderCode        | 店内支付--OrderCode模式 |
| PayPaymentCode      | 店内支付--PaymentCode模式 |
| Declare             | 海关申报 |
| CheckDeclareStatus  | 查询海关申报状态 |
| Refund              | 退款(通用) |
| CheckRefundStatus   | 查询退款状态 |
| RegisterMerchant    | 注册二级商家 |
| CheckRegisterStatus | 查询注册二级商家状态 |
| CheckRegisterInfo   | 查询注册二级商家信息 |


###### 授权
```php
use Yng\AlipayGlobal\AliPayGlobal;
use Yng\AlipayGlobal\Tool\Tool;

$auth_state = Tool::CreateAuthState();
$alipayGlobal = new AliPayGlobal(array(
    'client_id'          => 'SANDBOX_123456789123456', // Client ID
    'endpoint_area'      => 'ASIA', // 地区选择: NORTH_AMERIA / ASIA / EUROPE / GLOBAL
    'merchantPrivateKey' => '', // 商户私钥
    'alipayPublicKey'    => '', // 支付宝公钥
    'is_sandbox'         => true, // 是否打开沙盒环境
));

$res = $alipayGlobal->run([
    'model'             => 'AuthConsult',// 默认值
    'customerBelongsTo' => 'ALIPAY_CN',
    'authRedirectUrl'   => 'http://www.example.com/',
    'scopes'            => 'BASE_USER_INFO',
    'authState'         => $auth_state,
    'terminalType'      => 'WAP',
    'osType'            => 'ANDROID',
]);
```

###### 获取token或者刷新token
```php
$res = $alipayGlobal->run([
    'model'             => 'ApplyToken',// 默认值
    'customerBelongsTo' => 'ALIPAY_CN',
    'authRedirectUrl'   => 'http://www.example.com/',
    'scopes'            => 'BASE_USER_INFO',
    'authState'         => $auth_state,
    'terminalType'      => 'WAP',
    'osType'            => 'ANDROID',
]);
```


###### 撤销授权令牌
```php
$res = $alipayGlobal2->run([
    'model'       => 'RevokeToken',// 默认值
    'accessToken' => 'xxxxxxxxx',
]);
```

###### 在线支付---创建支付
```php
$order_id = 'DEMOPAY_'.date('YmdHis').rand(10000,99999);// 也可以使用工具包里的 Tool::CreatePaymentRequestId() 
$res = $alipayGlobal->run([
    'model'       => 'CreatePay',
    'paymentRequestId' => $order_id,// 自定义订单id

    // 订单信息
    'order' => [
        'orderAmount' => [
            'currency' => 'CNY',
            'value' => '200',
        ],
        'referenceOrderId' => $order_id,// 标识商家端订单的唯一ID,用于投诉，建议和订单id一样，也可自定义
        'orderDescription' => 'DEMO 描述',// 订单描述
        'env' => [
            'terminalType' => 'WEB',// 终端类型
        ],
    ],

    // 付款金额，与订单信息里的保持一致
    'paymentAmount' => [
        'currency' => 'CNY',
        'value' => '200',
    ],

    // 支付方式
    'paymentMethod' => [
        'paymentMethodType' => 'ALIPAY_CN',
    ],
    'paymentRedirectUrl' => 'http://www.example1.com/',// 支付后重定向地址
    'paymentNotifyUrl' => 'http://www.example2.com/',// 支付后的异步回调地址

    // 结算策略/结算货币 
    'settlementStrategy' => [
        'settlementCurrency' => 'CNY',
    ]
]);

if($res && in_array($res['result']['resultStatus'],['U','S'])){
    $normalUrl = $res['normalUrl'];// 创建后跳转地址
}else{
    return $res['result']['resultMessage'];// 创建支付订单失败原因
}
```


###### 取消支付
```php
$res = $alipayGlobal->run([
    'model'            => 'CancelPay',
    'paymentId'        => 'xxxxx',// 支付宝返回的订单id，二选一，同时填写paymentId级别优先
    'paymentRequestId' => 'xxxxx',// 自定义订单id，二选一，同时填写paymentId级别优先
]);
```

###### 查询订单状态
```php
$res = $alipayGlobal->run([
    'model'            => 'CheckPayStatus',
    'paymentId'        => 'xxxxx',// 支付宝返回的订单id，二选一，同时填写paymentId级别优先
    'paymentRequestId' => 'xxxxx',// 自定义订单id，二选一，同时填写paymentId级别优先
]);
```

###### 在线支付--自动借记
```php
$order_id = 'DEMOPAY_'.date('YmdHis').rand(10000,99999);// 也可以使用工具包里的 Tool::CreatePaymentRequestId() 
$res = $alipayGlobal->run([
    'model' => 'PayByDebit',
    'paymentRequestId' => $order_id,// 自定义订单id

    // 订单信息
    'order' => [
        'orderAmount' => [
            'currency' => 'CNY',
            'value'    => '300',
        ],
        'referenceOrderId' => $order_id,// 标识商家端订单的唯一ID,用于投诉
        'orderDescription' => 'DEMO 描述',// 订单描述
    ],

    // 付款金额，与订单信息里的保持一致
    'paymentAmount' => [
        'currency' => 'CNY',
        'value'    => '300',
    ],

    // 支付方式
    'paymentMethod' => [
        'paymentMethodType' => 'ALIPAY_CN',
        'paymentMethodId'   => 'access_token:xxxx',// applyToken申请的access token值
    ],

    // 结算策略/结算货币 
    'settlementStrategy' => ['settlementCurrency' => 'CNY'],
]);
```


###### 店内支付---Entry Code
另外两种模式可能在order参数里的不一样，具体参考[文档](https://global.alipay.com/docs/ac/ams/upm)
```php
$order_id = 'PayEntryCode_'.date('YmdHis').rand(10000,99999);// 也可以使用工具包里的 Tool::CreatePaymentRequestId()
$res = $alipayGlobal->run([
    'model' => 'PayEntryCode',
    'paymentRequestId' => $order_id,// 自定义订单id

    // 订单信息
    'order' => [
        'orderAmount' => [
            'currency' => 'CNY',
            'value'    => '200',
        ],
        'referenceOrderId' => $order_id,// 标识商家端订单的唯一ID,用于投诉
        'orderDescription' => 'PayEntryCode demo 描述',// 订单描述

        'merchant' => [
            'referenceMerchantId' => 'xxx',// 服务或商品的商家的 ID
            'merchantMCC'  => 'xxx',// 商家mcc码,具体看文档
            'merchantName' => 'xxx',// 商家名

            // 商店信息
            'store' => [
                'referenceStoreId' => 'xxx',// 由拥有商店的商家分配的唯一商店 ID
                'storeName' => 'xxx',// 商店名
                'storeMCC'  => 'xxx',// 商店mcc码
            ],
        ],

        'env' => [
            'userAgent' => 'Mozilla/5.0 (Linux; Android 9; LYA-AL00 Build/HUAWEILYA-AL00L; wv)  AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0  Chrome/70.0.3538.110  Mobile Safari/537.36 NebulaSDK/1.8.100112 Nebula  AlipayDefined(nt:WIFI,ws:360|0|3.0) AliApp(APHK/2.6.0.160)  AlipayClientHK/2.6.0.160 Language/zh-HK useStatusBar/true isConcaveScreen/true AlipayClient/10.1.32.600 Alipay Language/zh-Hans',// 此字段用于通过包含用户使用的钱包的标识符来指示用户身份，用户的请求头，这只是个demo，获取到的是怎么样就怎么样，无格式要求
        ],
    ],

    // 付款金额，与订单信息里的保持一致
    'paymentAmount' => [
        'currency' => 'CNY',
        'value'    => '200',
    ],

    // 支付方式
    'paymentMethod' => [
        'paymentMethodType' => 'CONNECT_WALLET',// 支付方式,EntryCode模式默认
    ],
    'paymentNotifyUrl' => 'http://www.example.com/notifyurl',// 支付后的异步回调地址

    // 结算策略/结算货币，因为是CONNECT_WALLET，此字段必填
    'settlementStrategy' => [
        'settlementCurrency' => 'CNY',
    ]
]);
```


###### 退款
```php
$order_id = 'REFUND_DEMOPAY_'.date('YmdHis').rand(10000,99999);
$res = $alipayGlobal->run([
    'model'           => 'Refund',
    'refundRequestId' => $order_id,// 自定义订单id
    'paymentId'       => 'xxxx',// 支付宝返回的订单id
    // 退款金额
    'refundAmount' => [
        'currency' => 'CNY',
        'value' => '100',
    ],
]);
```

###### 查询退款状态
```php
$res = $alipayGlobal->run([
    'model'           => 'CheckRefundStatus',
    'refundRequestId' => 'xxxx',// 自定义订单id，二选一，同时填写refundId级别优先
    'refundId'        => 'xxxx',// 支付宝返回的退款订单id，二选一，同时填写refundId级别优先
]);
```

###### FAQ
1. [设置支付密码](https://global.alipay.com/merchant/portal/security/config?_route=QK)

2. 设置支付密码是为了拿到[md5 key或RSA2公钥](https://globalprod.alipay.com/mhome/security-upgrade), 官方建议使用RSA2方式签名, 新版无RSA, 只有RSA2

3. 正式环境的就需要将配置里的进行替换, 秘钥公钥都不需要头部和尾部, 只需中间部分

4. 沙盒配置的一定要使用沙盒的信息, 正式环境也一样, 不然会报签名失败或其他错误

5. 在沙盒首页 -> 左侧边栏Development Tools, 只有部分api可测, 暂无验签工具, 需自行调试, [参考](https://global.alipay.com/docs/ac/ams/digital_signature) https://global.alipay.com/docs/ac/ams/digital_signature

6. 错误码看官方文档API最下面部分[https://global.alipay.com/docs/ac/ams/api](https://global.alipay.com/docs/ac/ams/api)

7. 如果报错信息为 ***Response signature verify fail.*** 说明你的商户私钥和支付宝公钥不匹配导致的，所以一定要配对，如第4点所说。