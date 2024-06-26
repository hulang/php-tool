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
     * 手机号码归属地
     * @param string $mobile_number 手机号码
     * @param string $separator 分隔符
     * @return mixed|array
     */
    public static function getData($mobile_number = '', $separator = '')
    {
        $result = [];
        if (!empty($mobile_number)) {
            $obj = new Phone;
            $obj->setPhone($mobile_number, $separator);
            $dt = $obj->getRegion();
            if ($dt['province'] == $dt['city']) {
                $dt['address'] = join($separator, [$dt['province'], $dt['sp']]);
            }
            $result = $dt;
        }
        return $result;
    }
}
