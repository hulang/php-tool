<?php

declare(strict_types=1);

namespace hulang\tool\build;

trait DateArr
{
    /**
     * 生成一个日期数组
     * @param $from
     * @param $to
     * @param string $step
     * @param string $outputFormat
     * @return array
     */
    public static function pf_date_indexed($from, $to, $step = '+1 day', $outputFormat = 'Y-m-d')
    {
        $dates = array();
        $current = strtotime($from);
        $last = strtotime($to);
        while ($current <= $last) {
            $dates[] = date($outputFormat, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    /**
     * 产生一个关联数组
     * @param $from
     * @param $to
     * @param null $default
     * @param string $step
     * @param string $outputFormat
     * @return array
     */
    public static function pf_date_assoc($from, $to, $default = null, $step = '+1 day', $outputFormat = 'Y-m-d')
    {
        $dates = self::pf_date_indexed($from, $to, $step, $outputFormat);
        return array_fill_keys($dates, $default);
    }
}
