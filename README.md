
**PHP工具类库**

<p align="center"> 
  您是第  <img src="https://profile-counter.glitch.me/github:hulang:php-tool/count.svg" />位访问者
</p>

#### 环境

- php >=8.0.0
- ext-zlib

##### 助手类
- 数组转各种`Tree`
- 时间
- 文件及文件夹
- 字符串
- 手机号码归属地
- 银行号归属地
- Zip压缩和解压

##### 安装

```
composer require hulang/php-tool
```

##### TreeHelper `数组转各种Tree`
```php
/**
 * 数组转树形处理类
 * @see \hulang\tool\TreeHelper
 * @package hulang\tool\TreeHelper
 * @mixin \hulang\tool\TreeHelper
 * @method static mixed|array getSubTree($data, $parent = 'pid', $son = 'id', $pid = 0, $lv_name = 'lv', $lv = 0) 子孙树按等级显示
 * @method static mixed|array getSubTreeList($data, $parent = 'pid', $son = 'id', $pid = 0, $child = 'child') 子孙树列表
 * @method static mixed|array getOneMergeTree($data, $html_name = 'html', $html = '├─', $pid = 0, $lv_name = 'level', $lv = 0, $parent = 'pid', $son = 'id') 组合一维数组
 * @method static mixed|array getMultidMergeTree($data, $parent = 'pid', $son = 'id', $pid = 0, $name = 'child') 组合多维数组
 * @method static mixed|array getTree($data, $parent = 'pid', $son = 'id', $sort_type = 0, $name = 'child') 合并成父子树
 * @method static mixed|array getParents($data, $id = 0, $parent = 'pid', $son = 'id') 传递子分类的id返回所有的父级分类数据
 * @method static mixed|array getParentsIds($data, $id, $parent = 'pid', $son = 'id') 传递子分类的id返回所有的父级分类ID
 * @method static mixed|array getChildsId($data, $pid, $parent = 'pid', $son = 'id') 传递父级id返回所有子级id
 * @method static mixed|array getChilds($data, $pid, $parent = 'pid', $son = 'id') 传递父级id返回所有子级分类数据
 */
```

##### FileHelper `文件及文件夹`
```php
/**
 * 文件及文件夹帮助类
 * @see \hulang\tool\FileHelper
 * @package hulang\tool
 * @mixin \hulang\tool\FileHelper
 * @method static mixed|SplFileInfo getFileAttr($filename = '') 获取文件属性
 * @method static mixed|string getFileMd5($filename = '') 计算文件的MD5值
 * @method static mixed|string getFileSha1($filename = '') 计算文件的 SHA-1 哈希值
 * @method static mixed|bool mkDir($dir = '') 创建目录
 * @method static mixed|string readFile($filename = '') 读取文件内容
 * @method static mixed|bool writeFile($filename = '', $writetext = '', $mode = LOCK_EX) 写文件
 * @method static mixed|bool delFile($filename = '') 删除指定的文件
 * @method static mixed|bool delDir($dirName = '') 删除指定的目录及其内容
 * @method static mixed|int copyDir($source, $toDir, $force = true) 目录拷贝,返回被拷贝的文件数
 * @method static mixed|array getFolder($path = '', $exclude = []) 得到指定目录里的信息
 * @method static mixed|int getDirSize($dir) 统计文件夹大小
 * @method static mixed|int emptyDir($dir) 检测是否为空文件夹
 * @method static mixed|string getFileSizeFormat($byte = 0) 文件大小格式
 * @method static mixed|int getRoundPow($num = 0, $precision = 2) 辅助函数,该函数用来取舍小数点位数的,四舍五入
 * @method static mixed|string getFileExt($fileName) 获取文件扩展名
 * @method static mixed|string getConvertEncoding($string) 转换字符编码
 */
```

##### TimeRun `时间`
```php
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
 */
```

##### StrHelper `字符串`
```php
/**
 * 字符串操作类
 * @see \hulang\tool\StrHelper
 * @package hulang\tool\StrHelper
 * @mixin \hulang\tool\StrHelper
 * @method static mixed|bool Contains(string $haystack, $needles) 检查字符串中是否包含某些字符串
 * @method static mixed|bool EndsWith(string $haystack, $needles) 检查字符串是否以某些字符串结尾
 * @method static mixed|bool StartsWith(string $haystack, $needles) 检查字符串是否以某些字符串开头
 * @method static mixed|string Random(int $length = 6, int $type = null, string $addChars = '') 生成指定长度的随机字母数字组合的字符串
 * @method static mixed|string Lower(string $value) 字符串转小写
 * @method static mixed|string Upper(string $value) 字符串转大写
 * @method static mixed|int Length(string $value) 获取字符串的长度
 * @method static mixed|string SubStr(string $string, int $start, int $length = null) 截取字符串
 * @method static mixed|string Snake(string $value, string $delimiter = '_') 驼峰转下划线
 * @method static mixed|string Camel(string $value) 下划线转驼峰(首字母小写)
 * @method static mixed|string Studly(string $value) 下划线转驼峰(首字母大写)
 * @method static mixed|string Title(string $value) 转为首字母大写的标题格式
 * @method static mixed|string uuid() 生成 UUID 编码
 * @method static mixed|string uniqidDate(int $size = 16, string $prefix = '') 生成日期编码
 * @method static mixed|string uniqidNumber(int $size = 12, string $prefix = '') 生成数字编码
 * @method static mixed|string text2utf8(string $text, string $target = 'UTF-8') 文本转码
 * @method static mixed|string encrypt($data, string $skey) 数据解密处理
 * @method static mixed|string decrypt(string $data, string $skey) 数据加密处理
 * @method static mixed|string enSafe64(string $text) Base64Url 安全编码
 * @method static mixed|string deSafe64(string $text) Base64Url 安全解码
 * @method static mixed|string enzip($data) 压缩数据对象
 * @method static mixed|string dezip(string $string) 解压数据对象
 */
```

##### PhoneHelper `手机号码归属地`
```php
/**
 * 手机号码归属地
 * @see \hulang\tool\PhoneHelper
 * @package hulang\tool\PhoneHelper
 * @mixin \hulang\tool\PhoneHelper
 * @method static mixed|array getData($mobile_number = '', $separator = '') 手机号码归属地
 */
```

##### BankHelper `银行号归属地查询`
```php
/**
 * 银行号归属地查询
 * @see \hulang\tool\BankHelper
 * @package hulang\tool\BankHelper
 * @mixin \hulang\tool\BankHelper
 * @method static mixed|array getData($card_number = '') 银行号归属地
 */
```

##### ZipHelper `Zip压缩和解压`
```php
/**
 * Php Zip压缩和解压
 * @see \hulang\tool\ZipHelper
 * @package hulang\tool\ZipHelper
 * @mixin \hulang\tool\ZipHelper
 * @method static mixed|string|bool|Exception compress($dir = '') 压缩|备份[目录]
 * @method static mixed|string|bool|Exception unzip($package, $dir = '') 解压
 */
```
