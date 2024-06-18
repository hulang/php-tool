<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 时间助手类
 * @see \hulang\tool\TimeRun
 * @package hulang\tool\TimeRun
 * @mixin \hulang\tool\TimeRun
 * @method static mixed|array today() 返回今日开始和结束的时间戳
 * @method static mixed|array yesterday() 返回昨日开始和结束的时间戳
 * @method static mixed|array week() 返回本周开始和结束的时间戳
 * @method static mixed|array lastWeek() 返回上周开始和结束的时间戳
 * @method static mixed|array month() 返回本月开始和结束的时间戳
 * @method static mixed|array lastMonth() 返回上个月开始和结束的时间戳
 * @method static mixed|array year() 返回今年开始和结束的时间戳
 * @method static mixed|array lastYear() 返回去年开始和结束的时间戳
 * @method static mixed|array dayToNow($day = 1, $now = 1) 获取几天前零点到现在/昨日结束的时间戳
 * @method static mixed|array daysAgo($day = 1) 返回几天前的时间戳
 * @method static mixed|array daysAfter($day = 1) 返回几天后的时间戳
 * @method static mixed|array getDaysAfterTimeStamp($day = 1) 返回几天后的开始和结束的时间戳
 * @method static mixed|int daysToSecond($day = 1) 天数转换成秒数
 * @method static mixed|int weekToSecond($week = 1) 周数转换成秒数
 * @method static mixed|array getTimeDiff($begin_time, $end_time) 获取两个时间|天数/小时数/分钟数/秒数
 * @method static mixed|int getDiffDays($datetime, $new_datetime = null, bool $is_day = false) 返回两个日期相差天数(如果只传入一个日期,则与当天时间比较)
 * @method static mixed|int getAfterDay(int $day = 1, $datetime = null, bool $is_day = false, bool $round = false) 返回N天后的时间戳,传入第二个参数,则从该时间开始计算
 * @method static mixed|array getByTimestamp($datetime) 根据|时间字符串或时间戳|返回传递的开始时间和结束时间
 * @method static mixed|array getBetweenTwoDates($start, $end, $format = 'Y-m-d', $type = 0) 获取两个日期之间的所有日期
 */
class TimeRun extends Time
{
}
