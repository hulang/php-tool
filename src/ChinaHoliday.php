<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 这个类专门用于判断国内的节假日,比如:某天是否为工作日/节假日
 * https://github.com/zjkal/time-helper
 */
class ChinaHoliday
{
    /**
     * 工作日中的休息日(节日)
     * @return array
     */
    private static $holiday = [
        '2020' => ['0101', '0124', '0127', '0128', '0129', '0130', '0406', '0501', '0504', '0505', '0625', '0626', '1001', '1002', '1005', '1006', '1007', '1008'],
        '2021' => ['0101', '0211', '0212', '0215', '0216', '0217', '0405', '0503', '0504', '0505', '0614', '0920', '0921', '1001', '1004', '1005', '1006', '1007'],
        '2022' => ['0103', '0131', '0201', '0202', '0203', '0204', '0404', '0405', '0502', '0503', '0504', '0603', '0912', '1003', '1004', '1005', '1006', '1007'],
        '2023' => ['0102', '0123', '0124', '0125', '0126', '0127', '0405', '0501', '0502', '0503', '0622', '0623', '0929', '1002', '1003', '1004', '1005', '1006']
    ];
    /**
     * 休息日中的工作日(调休日)
     * @return array
     */
    private static $workday = [
        '2020' => ['0119', '0201', '0426', '0509', '0628', '0927', '1010'],
        '2021' => ['0207', '0220', '0425', '0508', '0918', '0926', '1009'],
        '2022' => ['0129', '0130', '0402', '0424', '0507', '1008', '1009'],
        '2023' => ['0128', '0129', '0423', '0506', '0625', '1007', '1008']
    ];

    /**
     * 是否为国内的工作日
     * @param string|int $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return bool 是返回True,否则返回False
     */
    public static function isWorkday($datetime = null): bool
    {
        $y = TimeHelper::format('Y', $datetime);
        $md = TimeHelper::format('md', $datetime);
        //为平常日 且 (该年份不存在节日 或 该日期不是节日)
        $condition1 = TimeHelper::isWeekday($datetime) && (!array_key_exists($y, self::$holiday) || !in_array($md, self::$holiday[$y]));
        //为周末 且 该年份存在调休日 且 该日期是调休日
        $condition2 = TimeHelper::isWeekend($datetime) && array_key_exists($y, self::$workday) && in_array($md, self::$workday[$y]);
        return $condition1 || $condition2;
    }

    /**
     * 是否为国内的节假日
     * @param string|int $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return bool 是返回True,否则返回False
     */
    public static function isHoliday($datetime = null): bool
    {
        return !self::isWorkday($datetime);
    }
}
