<?php
namespace Yng\AlipayGlobal\Validate;

use Closure;
use Yng\AlipayGlobal\Exception\ValidateException;

/**
 * 数据验证类
 * @package Yng
 */
class Validator
{
    /**
     * 自定义验证类型
     * @var array
     */
    protected $type = [];

    /**
     * 验证类型别名
     * @var array
     */
    protected $alias = [
        '>' => 'gt', '>=' => 'egt', '<' => 'lt', '<=' => 'elt', '=' => 'eq', 'same' => 'eq',
    ];

    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [];

    /**
     * 验证提示信息
     * @var array
     */
    protected $message = [];

    /**
     * 验证字段描述
     * @var array
     */
    protected $field = [];

    /**
     * 默认规则提示
     * @var array
     */
    protected $typeMsg = [
        'require'     => ':attribute field is required',
        'must'        => ':attribute must',
        'string'      => ':attribute must be string',
        'number'      => ':attribute must be numeric',
        'integer'     => ':attribute must be integer',
        'float'       => ':attribute must be float',
        'boolean'     => ':attribute must be bool',
        'array'       => ':attribute must be a array',
        'accepted'    => ':attribute must be yes,on or 1',
        'date'        => ':attribute not a valid datetime',
        'url'         => ':attribute not a valid url',
        'ip'          => ':attribute not a valid ip',
        'dateFormat'  => ':attribute must be dateFormat of :rule',
        'in'          => ':attribute must be in :rule',
        'notIn'       => ':attribute be notin :rule',
        'between'     => ':attribute size must be between :1 - :2',
        'notBetween'  => ':attribute not between :1 - :2',
        'length'      => 'size of :attribute must be :rule',
        'max'         => 'Illegal parameter::attribute maximum length is :rule',
        'min'         => 'Illegal parameter::attribute minimum length is :rule',
        'egt'         => ':attribute must greater than or equal :rule',
        'gt'          => ':attribute must greater than :rule',
        'elt'         => ':attribute must less than or equal :rule',
        'lt'          => ':attribute must less than :rule',
        'eq'          => ':attribute must equal :rule',
        'unique'      => ':attribute has exists',
        'regex'       => ':attribute not conform to the rules',
        'when'        => ':attribute require',// 当a=1时b不能为空等
        'isset'       => ':attribute require',// 当设置a值时不能为空等
        'requireWithout' => 'The :attribute field and :rule field cannot be empty at the same time',
    ];

    /**
     * 当前验证场景
     * @var string
     */
    protected $currentScene;

    /**
     * 内置正则验证规则
     * @var array
     */
    protected $defaultRegex = [
        'alpha'       => '/^[A-Za-z]+$/',
        'alphaNum'    => '/^[A-Za-z0-9]+$/',
        'alphaDash'   => '/^[A-Za-z0-9\-\_]+$/',
        'chs'         => '/^[\x{4e00}-\x{9fa5}\x{9fa6}-\x{9fef}\x{3400}-\x{4db5}\x{20000}-\x{2ebe0}]+$/u',
        'chsAlpha'    => '/^[\x{4e00}-\x{9fa5}\x{9fa6}-\x{9fef}\x{3400}-\x{4db5}\x{20000}-\x{2ebe0}a-zA-Z]+$/u',
        'chsAlphaNum' => '/^[\x{4e00}-\x{9fa5}\x{9fa6}-\x{9fef}\x{3400}-\x{4db5}\x{20000}-\x{2ebe0}a-zA-Z0-9]+$/u',
        'chsDash'     => '/^[\x{4e00}-\x{9fa5}\x{9fa6}-\x{9fef}\x{3400}-\x{4db5}\x{20000}-\x{2ebe0}a-zA-Z0-9\_\-]+$/u',
        'mobile'      => '/^1[3-9]\d{9}$/',
        'idCard'      => '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/',
        'zip'         => '/\d{6}/',
    ];

    /**
     * Filter_var 规则
     * @var array
     */
    protected $filter = [
        'email'   => FILTER_VALIDATE_EMAIL,
        'ip'      => [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6],
        'integer' => FILTER_VALIDATE_INT,
        'url'     => FILTER_VALIDATE_URL,
        'macAddr' => FILTER_VALIDATE_MAC,
        'float'   => FILTER_VALIDATE_FLOAT,
    ];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [];

    /**
     * 验证失败错误信息
     * @var string|array
     */
    protected $error = [];

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batch = false;

    /**
     * 验证失败是否抛出异常
     * @var bool
     */
    protected $failException = false;

    /**
     * 配置
     * @var array
     */
    protected $config = [];

    /**
     * 构造方法
     * @access public
     */
    public function __construct(string $configFile)
    {
        if (empty($configFile)) {
            throw new ValidateException('The verification scenario cannot be empty');
        }

        $file = __DIR__ . DIRECTORY_SEPARATOR . $configFile . 'Validator.php';


        if(!is_file($file)) {
            throw new ValidateException('Invalid scenario');
        }

        $this->config = include $file;
        $this->rule = $this->config['rules'];
        $this->message = isset($this->config['message']) ? $this->config['message'] : '' ;
    }

    /**
     * 设置提示信息
     * @access public
     * @param array $message 错误信息
     * @return Validate
     */
    public function message(array $message)
    {
        $this->message = array_merge($this->message, $message);

        return $this;
    }


    /**
     * 设置验证失败后是否抛出异常
     * @access protected
     * @param bool $fail 是否抛出异常
     * @return $this
     */
    public function failException(bool $fail = true)
    {
        $this->failException = $fail;

        return $this;
    }


    /**
     * 数据自动验证
     * @access public
     * @param array $data  数据
     * @param array $rules 验证规则
     * @return bool
     */
    public function check(array $data, array $rules = []): bool
    {
        $this->error = [];

        if ($this->currentScene) {
            $this->getScene($this->currentScene);
        }

        if (empty($rules)) {
            // 读取验证规则
            $rules = $this->rule;
        }


        foreach ($rules as $key => $rule) {
            // field => 'rule1|rule2...' field => ['rule1','rule2',...]
            if (strpos($key, '|')) {
                // 字段|描述 用于指定属性名称
                [$key, $title] = explode('|', $key);
            } else {
                $title = $this->field[$key] ?? $key;
            }


            // 获取数据 支持二维数组
            $value = $this->getDataValue($data, $key);

            // echo '<pre>';var_dump($title,$key,$value,$rule);

            // 字段验证
            if ($rule instanceof Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
            } else {
                $result = $this->checkItem($key, $value, $rule, $data, $title);
            }

            if (true !== $result) {
                // 没有返回true 则表示验证失败
                if ($this->failException) {
                    throw new ValidateException($result);
                } else {
                    $this->error = $result;
                    return false;
                }
            }
        }

        if (!empty($this->error)) {
            if ($this->failException) {
                throw new ValidateException($this->error);
            }
            return false;
        }

        return true;
    }


    /**
     * 验证单个字段规则
     * @access protected
     * @param string $field 字段名
     * @param mixed  $value 字段值
     * @param mixed  $rules 验证规则
     * @param array  $data  数据
     * @param string $title 字段描述
     * @param array  $msg   提示信息
     * @return mixed
     */
    protected function checkItem(string $field, $value, $rules, $data, string $title = '', array $msg = [])
    {
        // 支持多规则验证 require|in:a,b,c|... 或者 ['require','in'=>'a,b,c',...]
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (empty($rules)) {
            return true;
        }

        $i = 0;
        foreach ($rules as $key => $rule) {
            if ($rule instanceof Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
                $info   = is_numeric($key) ? '' : $key;
            } else {
                // 判断验证类型
                [$type, $rule, $info] = $this->getValidateType($key, $rule);

                if (isset($this->type[$type])) {
                    $result = call_user_func_array($this->type[$type], [$value, $rule, $data, $field, $title]);
                } elseif ('must' == $info || 0 === strpos($info, 'require') || (!is_null($value) && '' !== $value)) {
                    $result = call_user_func_array([$this, $type], [$value, $rule, $data, $field, $title]);
                } else {
                    $result = true;
                }
            }

            if (false === $result) {
                // 验证失败 返回错误信息
                if (!empty($msg[$i])) {
                    $message = $msg[$i];
                } else {
                    $message = $this->getRuleMsg($field, $title, $info, $rule);
                }

                return $message;
            } elseif (true !== $result) {
                // 返回自定义错误信息

                if (is_string($result) && false !== strpos($result, ':')) {

                    $result = str_replace(':attribute', $title, $result);

                    if (strpos($result, ':rule') && is_scalar($rule)) {
                        $result = str_replace(':rule', (string) $rule, $result);
                    }
                }

                return $result;
            }
            $i++;
        }

        return $result ?? true;
    }

    /**
     * 获取当前验证类型及规则
     * @access public
     * @param mixed $key
     * @param mixed $rule
     * @return array
     */
    protected function getValidateType($key, $rule): array
    {
        // 判断验证类型
        if (!is_numeric($key)) {
            if (isset($this->alias[$key])) {
                // 判断别名
                $key = $this->alias[$key];
            }
            return [$key, $rule, $key];
        }

        if (strpos($rule, ':')) {
            [$type, $rule] = explode(':', $rule, 2);
            if (isset($this->alias[$type])) {
                // 判断别名
                $type = $this->alias[$type];
            }
            $info = $type;
        } elseif (method_exists($this, $rule)) {
            $type = $rule;
            $info = $rule;
            $rule = '';
        } else {
            $type = 'is';
            $info = $rule;
        }

        return [$type, $rule, $info];
    }

    /**
     * 验证是否大于等于某个值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function egt($value, $rule, array $data = []): bool
    {
        return $value >= $this->getDataValue($data, $rule);
    }

    /**
     * 验证是否大于某个值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function gt($value, $rule, array $data = []): bool
    {
        return $value > $this->getDataValue($data, $rule);
    }

    /**
     * 验证是否小于等于某个值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function elt($value, $rule, array $data = []): bool
    {
        return $value <= $this->getDataValue($data, $rule);
    }

    /**
     * 验证是否小于某个值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function lt($value, $rule, array $data = []): bool
    {
        return $value < $this->getDataValue($data, $rule);
    }

    /**
     * 验证是否等于某个值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function eq($value, $rule): bool
    {
        return $value == $rule;
    }

    /**
     * 必须验证
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function must($value, $rule = null): bool
    {
        return !empty($value) || '0' == $value;
    }

    /**
     * 验证字段值是否为有效格式
     * @access public
     * @param mixed  $value 字段值
     * @param string $rule  验证规则
     * @param array  $data  数据
     * @return bool
     */
    public function is($value,  $rule,  $data = []): bool
    {
        switch (lcfirst($rule)) {
            case 'require':
                // 必须
                // if(is_string($value)) {
                    $result = !empty($value) || '0' == $value;
                // }elseif(is_array($value)) {
                //     foreach($value as $val) {
                //         $result = !empty($val) || '0' == $val;
                //     }
                // }else{
                //     $result = false;
                // }

                break;
            case 'accepted':
                // 接受
                $result = in_array($value, ['1', 'on', 'yes']);
                break;
            case 'date':
                // 是否是一个有效日期
                $result = false !== strtotime($value);
                break;
            case 'boolean':
            case 'bool':
                // 是否为布尔值
                $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
                break;
            case 'number':
                $result = ctype_digit((string) $value);
                break;
            case 'array':
                // 是否为数组
                $result = is_array($value);
                break;
            case 'string':
                // 是否为字符串
                $result = is_string($value);
                break;
            default:
                if (isset($this->type[$rule])) {
                    // 注册的验证规则
                    $result = call_user_func_array($this->type[$rule], [$value]);
                } elseif (function_exists('ctype_' . $rule)) {
                    
                    // ctype验证规则
                    $ctypeFun = 'ctype_' . $rule;
                    $result   = $ctypeFun($value);
                } elseif (isset($this->filter[$rule])) {

                    // Filter_var验证规则
                    $result = $this->filter($value, $this->filter[$rule]);
                } else {
                    // 正则验证
                    $result = $this->regex($value, $rule);
                    

                }
        }

        return $result;
    }


    /**
     * 验证是否有效IP
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则 ipv4 ipv6
     * @return bool
     */
    public function ip($value, string $rule = 'ipv4'): bool
    {
        if (!in_array($rule, ['ipv4', 'ipv6'])) {
            $rule = 'ipv4';
        }

        return $this->filter($value, [FILTER_VALIDATE_IP, 'ipv6' == $rule ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4]);
    }

    /**
     * 验证时间和日期是否符合指定格式
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function dateFormat($value, $rule): bool
    {
        $info = date_parse_from_format($rule, $value);
        return 0 == $info['warning_count'] && 0 == $info['error_count'];
    }

    /**
     * 使用filter_var方式验证
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function filter($value, $rule): bool
    {
        if (is_string($rule) && strpos($rule, ',')) {
            [$rule, $param] = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = $rule[1] ?? 0;
            $rule  = $rule[0];
        } else {
            $param = 0;
        }

        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }

    /**
     * 验证某个字段等于某个值的时候必须
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function requireIf($value, $rule, array $data = []): bool
    {
        [$field, $val] = explode(',', $rule);

        if ($this->getDataValue($data, $field) == $val) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * 当字段为指定值时，另外一个字段必须有值
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function requireWhen($value, $rule, array $data = []): bool
    {
        
        $rule_arr = explode(',', $rule);// 获取指定值
        $field    = current($rule_arr);// 获取指定的字段
        unset($rule_arr[$field]);

        // 获取字段对应的值
        $field_data = $this->getDataValue($data, $field);

        if (!empty($field_data)) {
            if(in_array($field_data,$rule_arr)){
                return !empty($value) || '0' == $value;
            }
        }

        return false;
    }


    /**
     * 通过回调方法验证某个字段是否必须
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function requireCallback($value, $rule, array $data = []): bool
    {
        $result = call_user_func_array([$this, $rule], [$value, $data]);

        if ($result) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * 验证某个字段有值的情况下必须
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function requireWith($value, $rule, array $data = []): bool
    {
        $val = $this->getDataValue($data, $rule);

        if (!empty($val)) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * 验证某个字段没有值的情况下必须
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function requireWithout($value, $rule, array $data = []): bool
    {
        $val = $this->getDataValue($data, $rule);

        if (empty($val)) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * 验证是否在范围内
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function in($value, $rule): bool
    {
        $rule = is_array($rule) ? $rule : explode(',', $rule);
        if(is_array($value)) {
            foreach($value as $key => $val) {
                return in_array($val, $rule);
            }
        }
        return in_array($value, $rule);
    }

    /**
     * 验证是否不在某个范围
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function notIn($value, $rule): bool
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    /**
     * between验证数据
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function between($value, $rule): bool
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        [$min, $max] = $rule;

        return $value >= $min && $value <= $max;
    }

    /**
     * 使用notbetween验证数据
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function notBetween($value, $rule): bool
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        [$min, $max] = $rule;

        return $value < $min || $value > $max;
    }

    /**
     * 验证数据长度
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function length($value, $rule): bool
    {
        if (is_array($value)) {
            $length = count($value);
        } else {
            $length = mb_strlen((string) $value);
        }

        if (is_string($rule) && strpos($rule, ',')) {
            // 长度区间
            [$min, $max] = explode(',', $rule);
            return $length >= $min && $length <= $max;
        }

        // 指定长度
        return $length == $rule;
    }

    /**
     * 验证数据最大长度
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function max($value, $rule): bool
    {
        if (is_array($value)) {
            $length = count($value);
        } else {
            $length = mb_strlen((string) $value);
        }

        return $length <= $rule;
    }

    /**
     * 验证数据最小长度
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function min($value, $rule): bool
    {
        if (is_array($value)) {
            $length = count($value);
        } else {
            $length = mb_strlen((string) $value);
        }

        return $length >= $rule;
    }

    /**
     * 验证日期
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function after($value, $rule, array $data = []): bool
    {
        return strtotime($value) >= strtotime($rule);
    }

    /**
     * 验证日期
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function before($value, $rule, array $data = []): bool
    {
        return strtotime($value) <= strtotime($rule);
    }

    /**
     * 验证日期
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function afterWith($value, $rule, array $data = []): bool
    {
        $rule = $this->getDataValue($data, $rule);
        return !is_null($rule) && strtotime($value) >= strtotime($rule);
    }

    /**
     * 验证日期
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @param array $data  数据
     * @return bool
     */
    public function beforeWith($value, $rule, array $data = []): bool
    {
        $rule = $this->getDataValue($data, $rule);
        return !is_null($rule) && strtotime($value) <= strtotime($rule);
    }

    /**
     * 验证有效期
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function expire($value, $rule): bool
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        [$start, $end] = $rule;

        if (!is_numeric($start)) {
            $start = strtotime($start);
        }

        if (!is_numeric($end)) {
            $end = strtotime($end);
        }

        return time() >= $start && time() <= $end;
    }

    /**
     * 验证IP许可
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function allowIp($value, $rule): bool
    {
        return in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    /**
     * 验证IP禁用
     * @access public
     * @param mixed $value 字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    public function denyIp($value, $rule): bool
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    /**
     * 获取错误信息
     * @return array|string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取数据值
     * @access protected
     * @param array  $data 数据
     * @param string $key  数据标识 支持二维
     * @return mixed
     */
    protected function getDataValue(array $data, $key)
    {
        if (is_numeric($key)) {
            $value = $key;
        } elseif (is_string($key) && strpos($key, '.')) {
            // 支持多维数组验证
            foreach (explode('.', $key) as $key) {
                if (!isset($data[$key])) {
                    $value = null;
                    break;
                }
                $value = $data = $data[$key];
            }
        } else {
            $value = $data[$key] ?? null;
        }

        return $value;
    }

    /**
     * 获取验证规则的错误提示信息
     * @access protected
     * @param string $attribute 字段英文名
     * @param string $title     字段描述名
     * @param string $type      验证规则名称
     * @param mixed  $rule      验证规则数据
     * @return string|array
     */
    protected function getRuleMsg(string $attribute, string $title, string $type, $rule)
    {
        if (isset($this->message[$attribute . '.' . $type])) {
            $msg = $this->message[$attribute . '.' . $type];
        } elseif (isset($this->message[$attribute][$type])) {
            $msg = $this->message[$attribute][$type];
        } elseif (isset($this->message[$attribute])) {
            $msg = $this->message[$attribute];
        } elseif (isset($this->typeMsg[$type])) {
            $msg = $this->typeMsg[$type];
        } elseif (0 === strpos($type, 'require')) {
            $msg = $this->typeMsg['require'];
        } else {
            $msg = $title . 'not conform to the rules';
        }

        if (is_array($msg)) {
            return $this->errorMsgIsArray($msg, $rule, $title);
        }

        return $this->parseErrorMsg($msg, $rule, $title);
    }

    /**
     * 获取验证规则的错误提示信息
     * @access protected
     * @param string $msg   错误信息
     * @param mixed  $rule  验证规则数据
     * @param string $title 字段描述名
     * @return string|array
     */
    protected function parseErrorMsg(string $msg, $rule, string $title)
    {
        if (0 === strpos($msg, '{%')) {
            $msg = substr($msg, 2, -1);
        }

        if (is_array($msg)) {
            return $this->errorMsgIsArray($msg, $rule, $title);
        }

        // rule若是数组则转为字符串
        if (is_array($rule)) {
            $rule = implode(',', $rule);
        }

        if (is_scalar($rule) && false !== strpos($msg, ':')) {
            // 变量替换
            if (is_string($rule) && strpos($rule, ',')) {
                $array = array_pad(explode(',', $rule), 3, '');
            } else {
                $array = array_pad([], 3, '');
            }

            $msg = str_replace(
                [':attribute', ':1', ':2', ':3'],
                [$title, $array[0], $array[1], $array[2]],
                $msg
            );

            if (strpos($msg, ':rule')) {
                $msg = str_replace(':rule', (string) $rule, $msg);
            }
        }

        return $msg;
    }

    /**
     * 错误信息数组处理
     * @access protected
     * @param array $msg   错误信息
     * @param mixed  $rule  验证规则数据
     * @param string $title 字段描述名
     * @return array
     */
    protected function errorMsgIsArray(array $msg, $rule, string $title)
    {
        foreach ($msg as $key => $val) {
            if (is_string($val)) {
                $msg[$key] = $this->parseErrorMsg($val, $rule, $title);
            }
        }
        return $msg;
    }

    /**
     * 动态方法 直接调用is方法进行验证
     * @access public
     * @param string $method 方法名
     * @param array  $args   调用参数
     * @return bool
     */
    public function __call($method, $args)
    {
        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_push($args, lcfirst($method));

        return call_user_func_array([$this, 'is'], $args);
    }
}
