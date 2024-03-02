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
     * @return mixed|array
     */
    public static function CreateDateArray($from, $to, $step = '+1 day', $outputFormat = 'Y-m-d')
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
     * @return mixed|array
     */
    public static function CreateCorrelationArray($from, $to, $default = null, $step = '+1 day', $outputFormat = 'Y-m-d')
    {
        $dates = self::CreateDateArray($from, $to, $step, $outputFormat);
        return array_fill_keys($dates, $default);
    }
}
