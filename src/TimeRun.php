<?php

declare(strict_types=1);

namespace hulang\tool;

use DateTime;

/**
 * 时间助手类
 * @see \hulang\tool\TimeRun
 * @package hulang\tool\TimeRun
 * @mixin \hulang\tool\TimeRun
 * @method static mixed|array daysAgo($day = 1) 返回几天前的时间戳
 * @method static mixed|array daysAfter($day = 1) 返回几天后的时间戳
 * @method static mixed|array today() 返回今日开始和结束的时间戳
 * @method static mixed|array yesterday() 返回昨日开始和结束的时间戳
 * @method static mixed|array week() 返回本周开始和结束的时间戳
 * @method static mixed|array lastWeek() 返回上周开始和结束的时间戳
 * @method static mixed|array month() 返回本月开始和结束的时间戳
 * @method static mixed|array lastMonth() 返回上个月开始和结束的时间戳
 * @method static mixed|array year() 返回今年开始和结束的时间戳
 * @method static mixed|array lastYear() 返回去年开始和结束的时间戳
 * @method static mixed|array dayToNow($day = 1, $now = 1) 获取几天前零点到现在/昨日结束的时间戳
 * @method static mixed|array getDaysAfterTimeStamp($day = 1) 返回几天后的开始和结束的时间戳
 * @method static mixed|int daysToSecond($day = 1) 天数转换成秒数
 * @method static mixed|int weekToSecond($week = 1) 周数转换成秒数
 * @method static mixed|array getTimeDiff($begin_time, $end_time) 获取两个时间|天数/小时数/分钟数/秒数
 * @method static mixed|int getDiffDays($datetime, $new_datetime = null, bool $is_day = false) 返回两个日期相差天数(如果只传入一个日期,则与当天时间比较)
 * @method static mixed|int getAfterDay(int $day = 1, $datetime = null, bool $is_day = false, bool $round = false) 返回N天后的时间戳,传入第二个参数,则从该时间开始计算
 * @method static mixed|array getByTimestamp($datetime) 根据|时间字符串或时间戳|返回传递的开始时间和结束时间
 * @method static mixed|array getBetweenTwoDates($start, $end, $format = 'Y-m-d', $type = 0) 获取两个日期之间的所有日期
 * @method static mixed|array getYearMonthStamp($y = 0, $m = 0) 获取指定年份和月份的起始和结束时间戳
 */
class TimeRun extends TimeHelper
{
    /**
     * 将天数转换为秒数
     * 
     * 此静态方法用于将给定的天数转换为相应的总秒数
     * 它基于一个标准的昼夜周期为24小时,每小时60分钟,每分钟60秒
     * 
     * @param int $day 天数,默认为1天
     * @return mixed|int 返回转换后的总秒数
     */
    public static function daysToSecond($day = 1)
    {
        // 计算天数对应的总秒数
        return $day * 86400;
    }

    /**
     * 将周数转换为秒数
     *
     * 此方法通过乘以7天/周和一天的秒数(86400秒)来计算给定周数的总秒数
     * 它接受一个可选参数$week,默认值为1,用于指定需要转换的周数
     *
     * @param int $week 周数,默认为1
     * @return mixed|int 转换后的总秒数
     */
    public static function weekToSecond($week = 1)
    {
        // 调用类中的daysToSecond方法,这里假设这个方法存在并返回一天的秒数
        // 然后乘以7(一周的天数)和$week(指定的周数)来计算总秒数
        return self::daysToSecond() * 7 * $week;
    }

    /**
     * 获取今日开始和结束的时间戳
     * 
     * 本函数用于返回当前日期的开始和结束时间戳,这对于计算某个特定日期内的所有数据非常有用
     * 比如统计今日的访问量或者今日的销售数据等
     *
     * @return mixed|array 一个包含今日开始和结束时间戳的数组,第一个元素为开始时间戳,第二个元素为结束时间戳
     */
    public static function today()
    {
        // 将当前日期的年、月、日分离开来
        $arr = explode('-', date('Y-m-d'));
        // 将年、月、日转换为整数
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $d = intval($arr[2]);
        // 计算今日开始的时间戳,即凌晨0点0分0秒
        $begin = mktime(0, 0, 0, $m, $d, $y);
        // 计算今日结束的时间戳,即今天晚上23点59分59秒
        $end = mktime(23, 59, 59, $m, $d, $y);
        // 返回今日开始和结束的时间戳数组
        return [$begin, $end];
    }

    /**
     * 获取昨日的开始和结束时间戳
     * 
     * 本函数用于计算昨日的开始和结束时间戳,以方便对昨日的时间数据进行统计或筛选
     * 开始时间戳定义为昨日的零点,结束时间戳定义为昨日的23:59:59
     * 
     * @return mixed|array 包含昨日开始和结束时间戳的数组
     */
    public static function yesterday()
    {
        // 计算昨日的日期,即当前日期减去1天
        $yesterday = date('d') - 1;
        // 根据昨日的日期计算昨日的开始时间戳
        // mktime(0, 0, 0, intval(date('m')), $yesterday, intval(date('Y')));
        // 参数解释：00:00:00的开始时间,月份,日期,年份
        $begin = mktime(0, 0, 0, intval(date('m')), $yesterday, intval(date('Y')));
        // 根据昨日的日期计算昨日的结束时间戳
        // mktime(23, 59, 59, intval(date('m')), $yesterday, intval(date('Y')));
        // 参数解释：23:59:59的结束时间,月份,日期,年份
        $end = mktime(23, 59, 59, intval(date('m')), $yesterday, intval(date('Y')));
        // 返回昨日的开始和结束时间戳组成的数组
        return [$begin, $end];
    }

    /**
     * 获取本周的开始和结束时间戳
     * 
     * 本函数用于确定当前周的开始和结束时间戳
     * 周的开始被定义为本周第一天的午夜(0点),结束时间为本周最后一天的23点59分59秒
     * 注意,周的开始 day-of-week 是基于 ISO 8601 标准,在这个标准中,周日是一周的最后一天(值为0),周六是一周的第一天(值为6)
     * 
     * @return mixed|array 一个包含两个元素的数组,第一个元素是本周开始的时间戳,第二个元素是本周结束的时间戳
     */
    public static function week()
    {
        // 使用 date() 函数获取当前日期的年、月、日和本周的星期几(0表示周日,6表示周六)
        $arr = explode('-', date('Y-m-d-w'));
        // 分别提取年、月、日和星期几的值
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $d = intval($arr[2]);
        $w = intval($arr[3]);
        // 如果本周的星期几是0(表示周日),则修正为7,以保持一致性
        // 修正周日的问题
        if ($w == 0) {
            $w = 7;
        }
        // 计算本周开始的时间戳,即从本周第一天的午夜(0点)开始
        $begin = mktime(0, 0, 0, $m, $d - $w + 1, $y);
        // 计算本周结束的时间戳,即到本周最后一天的23点59分59秒
        $end = mktime(23, 59, 59, $m, $d - $w + 7, $y);
        // 返回包含本周开始和结束时间戳的数组
        return [$begin, $end];
    }

    /**
     * 获取上周的开始和结束时间戳
     * 
     * 本函数用于确定上周的的时间范围,以时间戳形式返回上周的开始和结束时间
     * 开始时间是上周的周一的0点,结束时间是上周的周日的23点59分59秒
     * 
     * @return mixed|array 包含两个元素的数组,第一个元素是上周一的开始时间戳,第二个元素是上周日的结束时间戳
     */
    public static function lastWeek()
    {
        // 获取当前时间的时间戳
        $timestamp = time();
        // 计算上周一的开始时间戳
        $begin = strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp)));
        // 计算上周日的结束时间戳,加24*3600-1是为了包含周日的23:59:59
        $end = strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1;
        // 返回上周的时间范围
        return [$begin, $end];
    }

    /**
     * 获取本月的开始和结束时间戳
     * 
     * 本函数用于返回当前月份的开始和结束时间戳,以便进行月份相关的数据统计或区间操作
     * 通过explode函数解析当前日期字符串(格式为Y-m-t)获取年、月、日信息,进而利用mktime函数构造开始和结束时间戳
     * 
     * @return mixed|array 开始和结束时间戳的数组,第一个元素为本月开始时间戳,第二个元素为本月结束时间戳
     */
    public static function month()
    {
        // 使用date函数获取当前日期的字符串表示,格式为"年-月-最后一天"
        $arr = explode('-', date('Y-m-t'));
        // 分别提取年、月、日信息
        $y = intval($arr[0]);
        $m = intval($arr[1]);
        $t = intval($arr[2]);
        // 使用mktime函数构造本月开始时间戳
        $begin = mktime(0, 0, 0, $m, 1, $y);
        // 使用mktime函数构造本月结束时间戳
        $end = mktime(23, 59, 59, $m, $t, $y);
        // 返回开始和结束时间戳的数组
        return [$begin, $end];
    }

    /**
     * 获取上个月的开始和结束时间戳
     * 
     * 本函数用于计算上一个月的起始和结束时间戳,这对于需要对上个月的数据进行统计或筛选的应用场景非常有用
     * 例如,在一个统计系统中,可以使用这个函数来获取上个月的数据范围
     * 
     * @return mixed|array 一个包含两个元素的数组,第一个元素是上个月开始的时间戳,第二个元素是上个月结束的时间戳
     */
    public static function lastMonth()
    {
        // 获取当前年份
        $y = intval(date('Y'));
        // 获取当前月份
        $m = intval(date('m'));
        // 计算上个月的开始时间戳
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        // 计算上个月的结束时间戳,使用date('t')获取上个月的天数
        $end = mktime(23, 59, 59, $m - 1, intval(date('t', $begin)), $y);
        // 返回上个月的开始和结束时间戳数组
        return [$begin, $end];
    }

    /**
     * 获取今年的开始和结束时间戳
     * 
     * 本函数用于返回当前年的开始和结束时间戳,以方便对今年的时间进行计算和比较
     * 开始时间戳定义为今年的1月1日0点0分0秒,结束时间戳定义为今年的12月31日23点59分59秒
     * 
     * @return mixed|array 一个包含今年开始和结束时间戳的数组,第一个元素为开始时间戳,第二个元素为结束时间戳
     */
    public static function year()
    {
        // 获取当前年的年份
        $y = intval(date('Y'));
        // 计算今年的开始时间戳,即1月1日0点0分0秒
        $begin = mktime(0, 0, 0, 1, 1, $y);
        // 计算今年的结束时间戳,即12月31日23点59分59秒
        $end = mktime(23, 59, 59, 12, 31, $y);
        // 返回开始和结束时间戳的数组
        return [$begin, $end];
    }

    /**
     * 获取去年的开始和结束时间戳
     * 
     * 本函数用于计算去年的起始和结束时间戳,这对于需要进行时间范围计算的场景非常有用
     * 比如统计去年的销售数据或者分析去年的用户行为等
     * 
     * @return mixed|array 一个包含去年开始和结束时间戳的数组,第一个元素为去年的开始时间戳,第二个元素为去年的结束时间戳
     */
    public static function lastYear()
    {
        // 计算去年的年份
        $year = date('Y') - 1;
        // 计算去年的第一天时间戳
        $begin = mktime(0, 0, 0, 1, 1, $year);
        // 计算去年的最后一天时间戳
        $end = mktime(23, 59, 59, 12, 31, $year);
        // 返回去年开始和结束的时间戳数组
        return [$begin, $end];
    }

    /**
     * 获取过去指定天数的起始和结束时间戳
     * 
     * 本函数用于计算从过去某天的零点到当前时间(或过去某天的23:59:59)的时间戳范围
     * 主要用于统计过去某段时间内的数据,如日志分析、数据统计等场景
     * 
     * @param int $day 指定的天数,默认为1,表示计算从昨天开始到现在的時間范围
     * @param int $now 控制返回的时间范围,1表示返回从昨天开始到现在的時間范围,其他值表示返回过去$day天的整天范围
     * @return mixed|array 如果$now为1,返回一个包含开始和结束时间戳的数组;否则,仅返回开始时间戳
     */
    public static function dayToNow($day = 1, $now = 1)
    {
        // 获取当前时间的时间戳,用于后续计算结束时间
        $end = time();
        // 如果$now参数为1,说明需要计算从昨天开始到现在的時間范围,调用yesterday方法获取昨天的结束时间
        if ($now == 1) {
            [$foo, $end] = self::yesterday();
        }
        // 计算过去$day天的零点时间戳,即起始时间
        $begin = mktime(0, 0, 0, intval(date('m')), date('d') - $day, intval(date('Y')));
        // 根据$now参数的值,决定返回值的格式
        return [$begin, $end];
    }

    /**
     * 计算几天前的UNIX时间戳
     * 
     * 该方法用于获取当前时间戳减去指定天数后的时间戳,即几天前的时间戳
     * 主要用于时间相关的计算和时间点的获取
     *
     * @param int $day 天数,指定要计算的天数前的时间戳.默认为1,表示计算昨天的时间戳
     * @return mixed|int 返回计算得到的几天前的UNIX时间戳
     */
    public static function daysAgo($day = 1)
    {
        // 获取当前时间的时间戳
        $nowTime = time();
        // 计算几天前的秒数,并从当前时间戳中减去得到几天前的时间戳
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 计算并返回当前时间戳之后指定天数的时间戳
     * 
     * 本函数用于根据给定的天数,计算出当前时间戳之后的那个时间戳
     * 这对于需要对日期进行运算,比如预订系统中计算出预订到期时间等场景非常有用
     * 
     * @param int $day 天数,表示要计算的天数,默认为1天
     *                 可以是正数,表示未来的时间;也可以是负数,表示过去的时间
     * @return mixed|int 返回计算得到的时间戳.
     *                   如果输入的$day参数非法(不是整数),则可能返回错误类型
     */
    public static function daysAfter($day = 1)
    {
        // 获取当前时间的时间戳
        $nowTime = time();
        // 计算指定天数后的时间戳,并返回
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 根据指定的天数获取未来某天的开始和结束时间戳
     * 
     * 此方法用于计算从当前时间起,指定天数后的某天的整日范围
     * 它返回一个包含开始和结束时间戳的数组,方便进行时间范围内的操作
     * 
     * @param int $day 指定的天数,默认为1,表示明天的整日范围
     * @return mixed|array 返回一个包含开始和结束时间戳的数组
     */
    public static function getDaysAfterTimeStamp($day = 1)
    {
        // 获取当前时间的时间戳
        $nowTime = time();
        // 计算指定天数后的时间戳
        $result = $nowTime + self::daysToSecond($day);
        // 根据结果时间戳获取当天的开始时间戳
        $start = strtotime(date('Y-m-d 00:00:00', $result));
        // 根据结果时间戳获取当天的结束时间戳
        $end = strtotime(date('Y-m-d 23:59:59', $result));
        // 将开始和结束时间戳组合成数组返回
        $arr = [$start, $end];
        return $arr;
    }

    /**
     * 计算两个时间之间的差异
     * 
     * 本函数用于计算两个时间点之间的差异,返回一个包含天、小时、分钟和秒的数组
     * 时间格式应为可被PHP解析的时间字符串,例如'Y-m-d H:i:s'
     * 
     * @param string $begin_time 开始时间
     * @param string $end_time 结束时间
     * @return mixed|array 返回一个包含天、小时、分钟和秒的数组
     */
    public static function getTimeDiff($begin_time, $end_time)
    {
        // 确保开始时间早于结束时间
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        // 计算两个时间点之间的总秒数差异
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
        // 返回一个包含天、小时、分钟和秒的数组
        $result = [
            'day' => $days,
            'hour' => $hours,
            'min' => $mins,
            'sec' => $secs
        ];
        return $result;
    }

    /**
     * 计算两个日期之间的天数差
     * 如果只提供一个日期,则计算该日期与当前日期的差值
     * 
     * @param int|string $datetime 第一个日期,可以是时间戳或符合日期格式的字符串
     * @param int|string $new_datetime 第二个日期,可以是时间戳或符合日期格式的字符串.如果不提供,则默认为当前日期
     * @param bool $is_day 是否包含当天.如果为true,则计算结果会将当天计入差值中
     * 
     * @return mixed|int 返回两个日期之间的天数差.如果$datetime大于$new_datetime,则返回的差值为负数.
     */
    public static function getDiffDays($datetime, $new_datetime = null, bool $is_day = false)
    {
        // 将输入的日期转换为标准日期格式(Y-m-d)
        $datetime = date('Y-m-d', self::toTimestamp($datetime));
        // 如果提供了第二个日期,则将其也转换为标准日期格式
        if ($new_datetime) {
            $new_datetime = date('Y-m-d', self::toTimestamp($new_datetime));
        } else {
            // 如果没有提供第二个日期,则使用当前日期
            $new_datetime = date('Y-m-d');
        }
        // 使用date_diff函数计算两个日期之间的差值,并获取天数差
        $result = date_diff(date_create($datetime), date_create($new_datetime))->days;
        // 如果需要包含当天,则将天数差加1
        if ($is_day) {
            $result = $result + 1;
        }
        // 返回计算结果
        return $result;
    }

    /**
     * 计算指定天数后的日期时间戳
     * 
     * 本函数用于根据给定的天数,从指定的时间点(或当前时间)计算未来的日期时间戳
     * 支持对计算结果进行取整到日的处理,并可选择是否包含当天
     * 
     * @param int $day 天数,表示要计算的未来天数,默认为1天
     * @param int|string $datetime 可选参数,表示起始时间的时间戳或日期字符串,默认为当前时间
     * @param bool $is_day 是否包含当天,如果为true,则计算结果会包含当天,否则不包含,默认为false
     * @param bool $round 是否对结果进行取整到日,如果为true,则结果会被取整到当天的0点0分0秒,默认为false
     * @return mixed|int 返回计算后的日期时间戳
     */
    public static function getAfterDay(int $day = 1, $datetime = null, bool $is_day = false, bool $round = false)
    {
        $date = new DateTime();
        if ($datetime !== null) {
            // 如果提供了起始时间,则将其转换为时间戳并设置为当前日期时间.
            $date->setTimestamp(self::toTimestamp($datetime));
        }
        if ($is_day) {
            // 如果需要包含当天,则减少一天的计算以达到包含当天的效果.
            $day = $day - 1;
        }
        // 修改日期时间为指定天数后,并获取其时间戳.
        $result = $date->modify(sprintf('+%d day', $day))->getTimestamp();
        if ($round) {
            // 如果需要取整到日,則将时间戳转换为该日的开始时间戳.
            $result = strtotime(date('Y-m-d 00:00:00', $result));
        }
        return $result;
    }

    /**
     * 根据时间字符串或时间戳获取当天的开始和结束时间戳
     * 
     * 本方法接受一个时间字符串或时间戳作为输入,然后转换为当天的开始和结束时间戳
     * 这对于需要对一天的数据进行操作时非常有用,比如计算一天的总流量或统计一天内的订单数
     * 
     * @param string $datetime 时间字符串或时间戳.可以是任何格式的时间字符串,或者是一个Unix时间戳
     * @return mixed|array 返回一个包含两个元素的数组,第一个元素是当天的开始时间戳,第二个元素是当天的结束时间戳
     */
    public static function getByTimestamp($datetime)
    {
        // 将输入的时间字符串或时间戳转换为Unix时间戳
        $timestamp = self::toTimestamp($datetime);
        // 计算当天的开始时间戳,即从当天的0点开始
        $start = strtotime(date('Y-m-d 00:00:00', $timestamp));
        // 计算当天的结束时间戳,即到当天的23点59分59秒结束
        $end = strtotime(date('Y-m-d 23:59:59', $timestamp));
        // 返回当天的开始和结束时间戳组成的数组
        return [$start, $end];
    }

    /**
     * 获取两个日期之间的所有日期
     * 
     * 本函数用于生成两个指定日期之间(包含起始和结束日期)的所有日期
     * 日期可以是任意格式的字符串或时间戳
     * 通过设置参数,可以控制返回日期的格式和类型(字符串或时间戳)
     * 
     * @param string $start 开始日期,可以是任意格式的日期字符串或时间戳
     * @param string $end 结束日期,可以是任意格式的日期字符串或时间戳
     * @param string $format 返回日期的格式,默认为'Y-m-d'.只有当返回类型为字符串时才生效
     * @param int $type 返回日期的类型,0表示返回字符串格式的日期,非0表示返回时间戳格式的日期
     * @return mixed|array 返回一个包含所有日期的数组.日期的格式由$format和$type参数决定
     */
    public static function getBetweenTwoDates($start, $end, $format = 'Y-m-d', $type = 0)
    {
        // 将开始日期转换为时间戳
        $dt_start = self::toTimestamp($start);
        // 将结束日期转换为时间戳
        $dt_end = self::toTimestamp($end);
        // 86400表示间隔为一天的秒数
        $date_range = range($dt_start, $dt_end, 86400);
        // 将时间戳转换为日期字符串
        $list = array_map(function ($timestamp) use ($format, $type) {
            // 循环处理每一天,直到结束日期
            if ($type == 0) {
                // 如果要求返回字符串格式的日期
                return date($format, $timestamp);
            } else {
                // 如果要求返回时间戳格式的日期
                return strtotime(date($format, $timestamp));
            }
        }, $date_range);
        // 返回包含所有日期的数组
        return $list;
    }

    /**
     * 获取指定年份和月份的起始和结束时间戳
     * 如果未指定年份和月份,则默认为当前年份和月份
     * 
     * @param int $y 年份,默认为0,表示当前年份
     * @param int $m 月份,默认为0,表示当前月份
     * @return mixed|array 包含指定月份起始和结束时间戳的数组
     */
    public static function getYearMonthStamp($y = 0, $m = 0)
    {
        // 如果未指定年份,则使用当前年份
        if ($y == 0) {
            $y = date('Y');
        }
        // 如果未指定月份,则使用当前月份
        if ($m == 0) {
            $m = date('m');
        }
        // 将月份格式化为两位数,例如1月变为01
        $m = sprintf('%02d', intval($m));
        // 将年份格式化为四位数,例如22变为0022
        $y = str_pad((string) intval($y), 4, '0', STR_PAD_RIGHT);
        // 检查月份是否在1到12的范围内,如果不在,则默认为1月
        if ($m > 12 || $m < 1) {
            $m = 1;
        }
        // 构造指定月份的第一天的日期字符串
        $firstday = strtotime($y . $m . '01000000');
        $firstdaystr = date('Y-m-01', $firstday);
        // 计算指定月份的最后一天的日期字符串
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime($firstdaystr . ' +1 month -1 day')));
        // 返回指定月份的起始和结束时间戳
        return [$firstday, $lastday];
    }
}
