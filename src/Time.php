<?php

declare(strict_types=1);

namespace hulang\tool;

use DateTime;
use DateTimeZone;
use Exception;
use InvalidArgumentException;

/**
 * 最方便的PHP时间助手类, 所有方法都可以传入任意类型的时间日期格式或者时间戳
 * https://github.com/zjkal/time-helper
 * 2023-10-29
 */
class Time
{
    // 常见日期格式
    public static $date_formats = ['Y-m-d', 'm/d/Y', 'd.m.Y', 'd/m/Y', 'Y年m月d日', 'Y年m月', 'Y年m月d号', 'Y/m/d', 'Y.m.d', 'Y.m'];
    // 常见时间格式
    public static $time_formats = ['H', 'H:i', 'H:i:s', 'H点', 'H点i分', 'H点i分s秒', 'H时', 'H时i分', 'H时i分s秒'];
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
    public static function daysAfterTimeStamp($day = 1)
    {
        $nowTime = time();
        $result = $nowTime + self::daysToSecond($day);
        $start = strtotime(date('Y-m-d 00:00:00', $result));
        $end = strtotime(date('Y-m-d 23:59:59', $result));
        //
        return [$start, $end];
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
     * 判断是否为时间戳格式
     * @param int|string $timestamp 要判断的字符串
     * @return mixed|bool 如果是时间戳返回True,否则返回False
     */
    public static function isTimestamp($timestamp)
    {
        $start = strtotime('1970-01-01 00:00:00');
        $end = strtotime('2099-12-31 23:59:59');
        //判断是否为时间戳
        if (!empty($timestamp) && is_numeric($timestamp) && $timestamp <= $end && $timestamp >= $start) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将任意时间类型的参数转为时间戳
     * 请注意 m/d/y 或 d-m-y 格式的日期，如果分隔符是斜线（/），则使用美洲的 m/d/y 格式。如果分隔符是横杠（-）或者点（.），则使用欧洲的 d-m-y 格式。为了避免潜在的错误，您应该尽可能使用 YYYY-MM-DD 格式或者使用 date_create_from_format() 函数。
     * @param int|string $datetime 要转换为时间戳的字符串或数字,如果为空则返回当前时间戳
     * @return mixed|int 时间戳
     */
    public static function toTimestamp($datetime = null)
    {
        if (empty($datetime)) {
            return time();
        }
        $start = strtotime('1970-01-01 00:00:00');
        $end = strtotime('2099-12-31 23:59:59');
        //判断是否为时间戳
        if (is_numeric($datetime) && $datetime <= $end && $datetime >= $start) {
            return intval($datetime);
        } else {
            $timestamp = strtotime($datetime);
            if ($timestamp) {
                return $timestamp;
            } else {
                //强制转化时间格式
                $datetime = self::formatSpecialDateTime($datetime);
                if ($datetime !== false) {
                    return strtotime($datetime);
                }
                throw new InvalidArgumentException('Param datetime must be a timestamp or a string time');
            }
        }
    }

    /**
     * 返回截止到今天晚上零点之前的秒数
     * @return mixed|int 秒数
     */
    public static function secondEndToday()
    {
        [$y, $m, $d] = explode('-', date('Y-m-d'));
        return mktime(23, 59, 59, intval($m), intval($d), intval($y)) - time();
    }

    /**
     * 返回一分钟的秒数,传入参数可以返回数分钟的秒数
     * @param int $minutes 分钟数,默认为1分钟
     * @return mixed|int 秒数
     */
    public static function secondMinute(int $minutes = 1)
    {
        return 60 * $minutes;
    }

    /**
     * 返回一小时的秒数,传入参数可以返回数小时的秒数
     * @param int $hours 小时数,默认为1小时
     * @return mixed|int 秒数
     */
    public static function secondHour(int $hours = 1)
    {
        return 3600 * $hours;
    }

    /**
     * 返回一天的秒数,传入参数可以返回数天的秒数
     * @param int $days 天数,默认为1天
     * @return mixed|int 秒数
     */
    public static function secondDay(int $days = 1)
    {
        return 86400 * $days;
    }

    /**
     * 返回一周的秒数,传入参数可以返回数周的秒数
     * @param int $weeks 周数,默认为1周
     * @return mixed|int 秒数
     */
    public static function secondWeek(int $weeks = 1)
    {
        return 604800 * $weeks;
    }

    /**
     * 讲时间转换为友好显示格式
     * @param int|string $datetime 时间日期的字符串或数字
     * @param string $lang 语言,默认为中文,如果要显示英文传入en即可
     * @return mixed|string 转换后的友好时间格式
     */
    public static function toFriendly($datetime, string $lang = 'zh')
    {
        $time = self::toTimestamp($datetime);

        $birthday = new DateTime();
        $birthday->setTimestamp($time);

        $now = new DateTime();
        $interval = $birthday->diff($now);

        $count = 0;
        $type = '';

        if ($interval->y) {
            $count = $interval->y;
            $type = $lang == 'zh' ? '年' : ' year';
        } elseif ($interval->m) {
            $count = $interval->m;
            $type = $lang == 'zh' ? '月' : ' month';
        } elseif ($interval->d) {
            $count = $interval->d;
            $type = $lang == 'zh' ? '天' : ' day';
        } elseif ($interval->h) {
            $count = $interval->h;
            $type = $lang == 'zh' ? '小时' : ' hour';
        } elseif ($interval->i) {
            $count = $interval->i;
            $type = $lang == 'zh' ? '分钟' : ' minute';
        } elseif ($interval->s) {
            $count = $interval->s;
            $type = $lang == 'zh' ? '秒' : ' second';
        }

        if (empty($type)) {
            return $lang == 'zh' ? '未知' : 'unknown';
        } else {
            return $count . $type . ($lang == 'zh' ? '前' : (($count > 1 ? 's' : '') . ' ago'));
        }
    }

    /**
     * 判断日期是否为今天
     * @param string|int $datetime 时间日期
     * @return mixed|bool 如果是今天则返回True,否则返回False
     */
    public static function isToday($datetime)
    {
        $timestamp = self::toTimestamp($datetime);
        if (date('Y-m-d', $timestamp) == date('Y-m-d')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断日期是否为本周
     * @param string|int $datetime 时间日期
     * @return mixed|bool 如果是本周则返回True,否则返回False
     */
    public static function isThisWeek($datetime)
    {
        $week_start = strtotime(date('Y-m-d 00:00:00', strtotime('this week')));
        $week_end = strtotime(date('Y-m-d 23:59:59', strtotime('last day next week')));
        $timestamp = self::toTimestamp($datetime);
        if ($timestamp >= $week_start && $timestamp <= $week_end) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断日期是否为本月
     * @param string|int $datetime 时间日期
     * @return mixed|bool 如果是本月则返回True,否则返回False
     */
    public static function isThisMonth($datetime)
    {
        $timestamp = self::toTimestamp($datetime);
        if (date('Y-m', $timestamp) == date('Y-m')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断日期是否为今年
     * @param string|int $datetime 时间日期
     * @return mixed|bool 如果是今年则返回True,否则返回False
     */
    public static function isThisYear($datetime)
    {
        $timestamp = self::toTimestamp($datetime);
        if (date('Y', $timestamp) == date('Y')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获得指定日期是星期几(默认为当前时间)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|int 星期几(1-7)
     */
    public static function getWeekDay($datetime = null)
    {
        return intval($datetime ? date('N', self::toTimestamp($datetime)) : date('N'));
    }

    /**
     * 判断指定日期是否为平常日(周一到周五)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|bool 是平常日返回true,否则返回false
     */
    public static function isWeekday($datetime = null)
    {
        return in_array(self::getWeekDay($datetime), [1, 2, 3, 4, 5]);
    }

    /**
     * 判断指定日期是否为周末(周六和周日)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|bool 是周末返回true,否则返回false
     */
    public static function isWeekend($datetime = null)
    {
        return in_array(self::getWeekDay($datetime), [6, 7]);
    }

    /**
     * 返回两个日期相差天数(如果只传入一个日期,则与当天时间比较)
     * @param int|string $datetime 要计算的时间
     * @param int|string $new_datetime 要比较的时间(默认为当前时间)
     * @param bool $is_day 是否包含今天(默认false),如果传入true,则包含今天开始计算
     * @return mixed|int 相差天数
     */
    public static function diffDays($datetime, $new_datetime = null, bool $is_day = false)
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
     * 返回两个日期相差星期数(如果只传入一个日期,则与当天时间比较)
     * @param int|string $datetime 要计算的时间
     * @param int|string $new_datetime 要比较的时间(默认为当前时间)
     * @return mixed|int 相差星期数
     */
    public static function diffWeeks($datetime, $new_datetime = null)
    {
        $datetime = date('Y-m-d', self::toTimestamp($datetime));
        if ($new_datetime) {
            $new_datetime = date('Y-m-d', self::toTimestamp($new_datetime));
        } else {
            $new_datetime = date('Y-m-d');
        }

        return intval(date_diff(date_create($datetime), date_create($new_datetime))->days / 7);
    }

    /**
     * 返回两个日期相差月数(如果只传入一个日期,则与当天时间比较)
     * @param int|string $datetime 要计算的时间
     * @param int|string $new_datetime 要比较的时间(默认为当前时间)
     * @return mixed|int 相差月数
     */
    public static function diffMonths($datetime, $new_datetime = null)
    {
        $datetime = date('Y-m-d', self::toTimestamp($datetime));
        if ($new_datetime) {
            $new_datetime = date('Y-m-d', self::toTimestamp($new_datetime));
        } else {
            $new_datetime = date('Y-m-d');
        }

        $diff = date_diff(date_create($datetime), date_create($new_datetime));
        return $diff->y * 12 + $diff->m;
    }

    /**
     * 返回两个日期相差年数(如果只传入一个日期,则与当前时间比较)
     * @param int|string $datetime 要计算的时间
     * @param int|string $new_datetime 要比较的时间(默认为当前时间)
     * @return mixed|int 相差年数
     */
    public static function diffYears($datetime, $new_datetime = null)
    {
        $datetime = date('Y-m-d', self::toTimestamp($datetime));
        if ($new_datetime) {
            $new_datetime = date('Y-m-d', self::toTimestamp($new_datetime));
        } else {
            $new_datetime = date('Y-m-d');
        }

        return date_diff(date_create($datetime), date_create($new_datetime))->y;
    }

    /**
     * 返回N分钟前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $minute 分钟数(默认为1分钟)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前分钟0秒的时间戳
     * @return mixed|int 时间戳
     */
    public static function beforeMinute(int $minute = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('-%d minute', $minute))->getTimestamp();
        return $round ? strtotime(date('Y-m-d H:i:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N分钟后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $minute 分钟数(默认为1分钟)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前分钟0秒的时间戳
     * @return mixed|int 时间戳
     */
    public static function afterMinute(int $minute = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('+%d minute', $minute))->getTimestamp();
        return $round ? strtotime(date('Y-m-d H:i:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N小时前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $hour 小时数(默认为1小时)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前小时0分钟的时间戳
     * @return mixed|int 时间戳
     */
    public static function beforeHour(int $hour = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('-%d hour', $hour))->getTimestamp();
        return $round ? strtotime(date('Y-m-d H:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N小时后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $hour 小时数(默认为1小时)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前小时0分钟的时间戳
     * @return mixed|int 时间戳
     */
    public static function afterHour(int $hour = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('+%d hour', $hour))->getTimestamp();
        return $round ? strtotime(date('Y-m-d H:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N天前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $day 天数(默认为1天)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function beforeDay(int $day = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('-%d day', $day))->getTimestamp();
        return $round ? strtotime(date('Y-m-d 00:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N天后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $day 天数(默认为1天)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $is_day 是否包含今天(默认false),如果传入true,则包含今天开始计算
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function afterDay(int $day = 1, $datetime = null, bool $is_day = false, bool $round = false)
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
     * 返回N星期前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $week 星期数(默认为1星期)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|int 时间戳
     */
    public static function beforeWeek(int $week = 1, $datetime = null)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        return $date->modify(sprintf('-%d week', $week))->getTimestamp();
    }

    /**
     * 返回N星期后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $week 星期数(默认为1星期)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|int 时间戳
     */
    public static function afterWeek(int $week = 1, $datetime = null)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        return $date->modify(sprintf('+%d week', $week))->getTimestamp();
    }

    /**
     * 返回N月前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $month 月数(默认为1个月)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期1号0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function beforeMonth(int $month = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('-%d month', $month))->getTimestamp();
        return $round ? strtotime(date('Y-m-1 00:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N月后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $month 月数(默认为1个月)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期1号0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function afterMonth(int $month = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('+%d month', $month))->getTimestamp();
        return $round ? strtotime(date('Y-m-1 00:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N年前的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $year 年数(默认为1年)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期1月1号0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function beforeYear(int $year = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('-%d year', $year))->getTimestamp();
        return $round ? strtotime(date('Y-1-1 00:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 返回N年后的时间戳,传入第二个参数,则从该时间开始计算
     * @param int $year 年数(默认为1年)
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @param bool $round 是否取整(默认false),如果传入true,则返回当前日期1月1号0点的时间戳
     * @return mixed|int 时间戳
     */
    public static function afterYear(int $year = 1, $datetime = null, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        $timestamp = $date->modify(sprintf('+%d year', $year))->getTimestamp();
        return $round ? strtotime(date('Y-1-1 00:00:00', $timestamp)) : $timestamp;
    }

    /**
     * 获得秒级/毫秒级/微秒级/纳秒级时间戳
     * @param int $level 默认0,获得秒级时间戳. 1.毫秒级时间戳; 2.微秒级时间戳; 3.纳米级时间戳
     * @return mixed|int 时间戳
     */
    public static function getTimestamp(int $level = 0)
    {
        if ($level === 0) {
            return time();
        }
        [$msc, $sec] = explode(' ', microtime());
        if ($level === 1) {
            return intval(sprintf('%.0f', (floatval($msc) + floatval($sec)) * 1000));
        } elseif ($level === 2) {
            return intval(sprintf('%.0f', (floatval($msc) + floatval($sec)) * 1000 * 1000));
        } else {
            return intval(sprintf('%.0f', (floatval($msc) + floatval($sec)) * 1000 * 1000 * 1000));
        }
    }

    /**
     * 获得毫秒级的时间戳
     * @return mixed|int 毫秒级时间戳
     */
    public static function getMilliTimestamp()
    {
        return self::getTimestamp(1);
    }

    /**
     * 获得微秒级的时间戳
     * @return mixed|int 微秒级时间戳
     */
    public static function getMicroTimestamp()
    {
        return self::getTimestamp(2);
    }

    /**
     * 获得纳秒级的时间戳
     * @return mixed|int 纳秒级时间戳
     */
    public static function getNanoTimestamp()
    {
        return self::getTimestamp(3);
    }

    /**
     * 将任意格式的时间转换为指定格式
     * @param string $format 格式化字符串
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return false|string 格式化后的时间字符串
     */
    public static function format(string $format = 'Y-m-d H:i:s', $datetime = null)
    {
        return date($format, self::toTimestamp($datetime));
    }

    /**
     * 判断该日期是否为闰年
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|bool 闰年返回true,否则返回false
     */
    public static function isLeapYear($datetime = null)
    {
        return date('L', self::toTimestamp($datetime)) == 1;
    }

    /**
     * 判断该日期的当年有多少天
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|int 该年的天数
     */
    public static function daysInYear($datetime = null)
    {
        return self::isLeapYear($datetime) ? 366 : 365;
    }

    /**
     * 判断该日期的当月有多少天
     * @param int|string $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return mixed|int 该月的天数
     */
    public static function daysInMonth($datetime = null)
    {
        return intval(date('t', self::toTimestamp($datetime)));
    }
    /**
     * 不同时区的时间转换
     * @param string $toTimezone 目标时区
     * @param string|null $fromTimezone 原时区(默认为当前PHP运行环境所设置的时区)
     * @param int|string  $datetime 任意格式的时间字符串或时间戳(默认为当前时间)
     * @param string $format 格式化字符串
     * @return string
     * @throws mixed|string|Exception
     */
    public static function timezoneFormat(string $toTimezone, string $fromTimezone = null, $datetime = 'now', string $format = 'Y-m-d H:i:s')
    {
        if (self::isTimestamp($datetime)) {
            $date = new DateTime();
            $date->setTimestamp($datetime);
            $date->setTimezone(new DateTimeZone('UTC'));
        } else {
            if ($fromTimezone === null) {
                $fromTimezone = date_default_timezone_get();
            }
            $date = new DateTime($datetime, new DateTimeZone($fromTimezone));
        }
        $date->setTimezone(new DateTimeZone($toTimezone));
        return $date->format($format);
    }

    /**
     * 比较两个时间的大小,如果第二个参数为空,则与当前时间比较
     * @param $datetime1
     * @param $datetime2
     * @return mixed|int 第一个时间大于第二个时间则返回1,小于则返回-1,相等时则返回0
     */
    public static function compare($datetime1, $datetime2 = null)
    {
        $timestamp1 = self::toTimestamp($datetime1);
        $timestamp2 = self::toTimestamp($datetime2);
        if ($timestamp1 > $timestamp2) {
            return 1;
        } elseif ($timestamp1 < $timestamp2) {
            return -1;
        } else {
            return 0;
        }
    }
    /**
     * 格式化特殊日期时间
     * @param string $datetime
     * @param string|null $format 参数为空则根据日期时间自动格式化为 Y-m-d 或 Y-m-d H:i:s
     * @return mixed|bool|string
     */
    public static function formatSpecialDateTime(string $datetime, string $format = null)
    {
        [$date, $time] = explode(' ', $datetime);
        if (!$date) {
            return false;
        }
        //获取时间的格式
        $time_format_str = '';
        if ($time) {
            foreach (self::$time_formats as $time_format) {
                if (date_create_from_format($time_format, $time) !== false) {
                    $time_format_str = $time_format;
                    break;
                }
            }
        }
        foreach (self::$date_formats as $date_format) {
            //获取日期的格式
            if (date_create_from_format($date_format, $date) !== false) {
                $datetime_format = ($time_format_str) ? "$date_format $time_format_str" : $date_format;
                //获取日期时间对象
                $datetime_obj = date_create_from_format($datetime_format, $datetime);
                if ($datetime_obj !== false) {
                    if ($format) {
                        return $datetime_obj->format($format);
                    }
                    return $datetime_obj->format('Y-m-d' . ($time_format_str ? ' H:i:s' : ''));
                }
            }
        }
        return false;
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
        //
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
