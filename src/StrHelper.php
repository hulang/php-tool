<?php

declare(strict_types=1);

namespace hulang\tool;

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
class StrHelper extends Str
{
}
