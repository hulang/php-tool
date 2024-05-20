<?php

declare(strict_types=1);

namespace hulang\tool;

use DateTime;

/**
 * 时间助手类
 */
class Time extends TimeHelper
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function today()
    {
        $arr = explode('-', date('Y-m-d'));
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $d = intval($arr[2]);
        $begin = mktime(0, 0, 0, $m, $d, $y);
        $end = mktime(23, 59, 59, $m, $d, $y);
        return [$begin, $end];
    }
    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        $begin = mktime(0, 0, 0, intval(date('m')), $yesterday, intval(date('Y')));
        $end = mktime(23, 59, 59, intval(date('m')), $yesterday, intval(date('Y')));
        return [$begin, $end];
    }
    /**
     * 返回本周开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function week()
    {
        $arr = explode('-', date('Y-m-d-w'));
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $d = intval($arr[2]);
        $w = intval($arr[3]);
        // 修正周日的问题
        if ($w == 0) {
            $w = 7;
        }
        $begin = mktime(0, 0, 0, $m, $d - $w + 1, $y);
        $end = mktime(23, 59, 59, $m, $d - $w + 7, $y);
        return [$begin, $end];
    }
    /**
     * 返回上周开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        $begin = strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp)));
        $end = strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1;
        return [$begin, $end];
    }
    /**
     * 返回本月开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function month()
    {
        $arr = explode('-', date('Y-m-t'));
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $t = intval($arr[2]);
        $begin = mktime(0, 0, 0, $m, 1, $y);
        $end = mktime(23, 59, 59, $m, $t, $y);
        return [$begin, $end];
    }
    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function lastMonth()
    {
        $y = intval(date('Y'));
        $m = intval(date('m'));
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, intval(date('t', $begin)), $y);
        return [$begin, $end];
    }
    /**
     * 返回今年开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function year()
    {
        $y = intval(date('Y'));
        $begin = mktime(0, 0, 0, 1, 1, $y);
        $end = mktime(23, 59, 59, 12, 31, $y);
        return [$begin, $end];
    }
    /**
     * 返回去年开始和结束的时间戳
     *
     * @return mixed|array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        $begin = mktime(0, 0, 0, 1, 1, $year);
        $end = mktime(23, 59, 59, 12, 31, $year);
        return [$begin, $end];
    }
    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param int $now 返回现在或者昨天结束时间戳
     * @return mixed|array
     */
    public static function dayToNow($day = 1, $now = 1)
    {
        $end = time();
        if ($now == 1) {
            [$foo, $end] = self::yesterday();
        }
        $begin = mktime(0, 0, 0, intval(date('m')), date('d') - $day, intval(date('Y')));
        return [$begin, $end];
    }
    /**
     * 返回几天前的时间戳
     *
     * @param int $day 天数
     * @return mixed|int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }
    /**
     * 返回几天后的时间戳
     *
     * @param int $day 天数
     * @return mixed|int
     */
    public static function daysAfter($day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }
    /**
     * 返回几天后的开始和结束的时间戳
     *
     * @param int $day 天数
     * @return mixed|array
     */
    public static function getDaysAfterTimeStamp($day = 1)
    {
        $nowTime = time();
        $result = $nowTime + self::daysToSecond($day);
        $start = strtotime(date('Y-m-d 00:00:00', $result));
        $end = strtotime(date('Y-m-d 23:59:59', $result));
        $arr = [$start, $end];
        return $arr;
    }
    /**
     * 天数转换成秒数
     *
     * @param int $day 天数
     * @return mixed|int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * 86400;
    }
    /**
     * 周数转换成秒数
     *
     * @param int $week 周期
     * @return mixed|int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

    /**
     * 获取两个时间|天数/小时数/分钟数/秒数
     * @param string $begin_time 开始时间
     * @param string $end_time 结束时间
     * @return mixed|array
     */
    public static function getTimeDiff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        // 计算天数
        $timediff = $endtime - $starttime;
        $days = intval($timediff / 86400);
        // 计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        // 计算分钟数
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        // 计算秒数
        $secs = $remain % 60;
        $result = [
            'day' => $days,
            'hour' => $hours,
            'min' => $mins,
            'sec' => $secs
        ];
        return $result;
    }

    /**
     * 返回两个日期相差天数(如果只传入一个日期,则与当天时间比较)
     * @param int|string $datetime 要计算的时间
     * @param int|string $new_datetime 要比较的时间(默认为当前时间)
     * @param bool $is_day 是否包含今天(默认false),如果传入true,则包含今天开始计算
     * @return mixed|int 相差天数
     */
    public static function getDiffDays($datetime, $new_datetime = null, bool $is_day = false)
    {
        $datetime = date('Y-m-d', self::toTimestamp($datetime));
        if ($new_datetime) {
            $new_datetime = date('Y-m-d', self::toTimestamp($new_datetime));
        } else {
            $new_datetime = date('Y-m-d');
        }
        $result = date_diff(date_create($datetime), date_create($new_datetime))->days;
        if ($is_day) {
            $result = $result + 1;
        }
        return $result;
    }

    /**
     * 返回N天后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $day 天数(默认为1天)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $is_day 是否包含今天(默认false),如果传入true,则包含今天开始计算
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function getAfterDay(int $day = 1, $datetime = null, bool $is_day = false, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        if ($is_day) {
            $day = $day - 1;
        }
        $result = $date->modify(sprintf('+%d day', $day))->getTimestamp();
        if ($round) {
            $result = strtotime(date('Y-m-d 00:00:00', $result));
        }
        return $result;
    }
    /**
     * 根据|时间字符串或时间戳|返回传递的开始时间和结束时间
     * @param string $datetime 任意格式时间字符串或时间戳
     * @return mixed|array
     */
    public static function getByTimestamp($datetime)
    {
        $timestamp = self::toTimestamp($datetime);
        $start = strtotime(date('Y-m-d 00:00:00', $timestamp));
        $end = strtotime(date('Y-m-d 23:59:59', $timestamp));
        return [$start, $end];
    }
    /**
     * 获取两个日期之间的所有日期
     * @param string $start 开始时间|任意格式时间字符串或时间戳
     * @param string $end 结束时间|任意格式时间字符串或时间戳
     * @param string $format 参数为空则根据日期时间自动格式化为 Y-m-d 或 Y-m-d H:i:s
     * @param int $type 返回类型,0:Y-m-d,非0返回:时间戳
     * @return mixed|array
     */
    public static function getBetweenTwoDates($start, $end, $format = 'Y-m-d', $type = 0)
    {
        $list = [];
        $dt_start = self::toTimestamp($start);
        $dt_end = self::toTimestamp($end);
        while ($dt_start <= $dt_end) {
            if ($type == 0) {
                $list[] = date($format, $dt_start);
            } else {
                $list[] = strtotime(date($format, $dt_start));
            }
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $list;
    }
}
