<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 手机号码归属地
 * @see \hulang\tool\PhoneHelper
 * @package hulang\tool\PhoneHelper
 * @mixin \hulang\tool\PhoneHelper
 * @method static mixed|array getData($mobile_number = '', $separator = '') 手机号码归属地
 */
class PhoneHelper
{
    /**
     * 根据手机号码获取归属地信息
     * 本函数通过手机号码查询其归属地,并返回相关地址信息
     * 如果手机号码为空或不合法,则返回空数组
     *
     * @param string $mobile_number 手机号码
     * @param string $separator 地址元素之间的分隔符
     * @return mixed|array 返回包含归属地信息的数组,如果手机号码无效则返回空数组
     */
    public static function getData($mobile_number = '', $separator = '')
    {
        // 初始化结果数组
        $result = [];
        // 检查手机号码是否为空
        if (!empty($mobile_number)) {
            // 创建Phone对象用于查询
            $obj = new Phone;
            // 设置手机号码和分隔符
            $obj->setPhone($mobile_number, $separator);
            // 获取归属地信息
            $dt = $obj->getRegion();
            // 如果省和市相同,合并显示为省+运营商
            if ($dt['province'] == $dt['city']) {
                $dt['address'] = join($separator, [$dt['province'], $dt['sp']]);
            }
            // 将归属地信息存入结果数组
            $result = $dt;
        }
        // 返回结果数组
        return $result;
    }
}
