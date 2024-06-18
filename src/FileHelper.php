<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 文件及文件夹处理类
 * @see \hulang\tool\FileHelper
 * @package hulang\tool\FileHelper
 * @mixin \hulang\tool\FileHelper
 * @method static mixed|bool mkDir($dir = '') 创建目录
 * @method static mixed|SplFileInfo getFileAttr($filename = '') 获取文件属性
 * @method static mixed|string readFile($filename = '') 读取文件内容
 * @method static mixed|bool writeFile($filename = '', $writetext = '', $mode = LOCK_EX) 写文件
 * @method static mixed|bool delFile($filename = '') 删除文件
 * @method static mixed|bool delDir($dirName = '') 删除目录
 * @method static mixed|int copyDir($source, $toDir, $force = true) 目录拷贝,返回被拷贝的文件数
 * @method static mixed|array getFolder($path = '') 得到指定目录里的信息
 * @method static mixed|int getDirSize($dir) 统计文件夹大小
 * @method static mixed|int emptyDir($dir) 检测是否为空文件夹
 * @method static mixed|string getFileSizeFormat($byte = 0) 文件大小格式
 * @method static mixed|int getRoundPow($num = 0, $precision = 2) 辅助函数,该函数用来取舍小数点位数的,四舍五入
 * @method static mixed|string getFileExt($fileName) 获取文件扩展名
 * @method static mixed|string getConvertEncoding($string) 转换字符编码
 */
class FileHelper extends File
{
}
