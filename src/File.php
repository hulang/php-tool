<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 文件及文件夹处理类
 * @see \hulang\tool\File
 * @package hulang\tool\File
 * @mixin \hulang\tool\File
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
class File
{
    /**
     * 创建目录
     * @param string $dir 目录名
     * @return mixed|bool
     */
    public static function mkDir($dir = '')
    {
        $result = false;
        // 创建一个新目录,权限设置为 0755
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
    /**
     * 获取文件属性
     * @param string $filename 文件名
     * @return mixed|SplFileInfo
     */
    public static function getFileAttr($filename = ''): \SplFileInfo
    {
        $obj = '';
        if (!empty($filename) && is_file($filename)) {
            $obj = new \SplFileInfo($filename);
        }
        return $obj;
    }
    /**
     * 读取文件内容
     * @param string $filename 文件名
     * @return mixed|string
     */
    public static function readFile($filename = '')
    {
        $content = '';
        if (!empty($filename) && is_file($filename)) {
            $content = file_get_contents($filename);
        }
        return $content;
    }
    /**
     * 写文件
     * @param string $filename 文件名
     * @param string $writetext 文件内容
     * @param string $mode 写入文件模式
     * @return mixed|bool
     */
    public static function writeFile($filename = '', $writetext = '', $mode = LOCK_EX)
    {
        if (!empty($filename) && !empty($writetext)) {
            $fileArr = pathinfo($filename);
            self::mkDir($fileArr['dirname']);
            $size = file_put_contents($filename, $writetext, $mode);
            if ($size > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * 删除文件
     * @param string $filename 文件名
     * @return mixed|bool
     */
    public static function delFile($filename = '')
    {
        if (file_exists($filename)) {
            unlink($filename);
            return true;
        } else {
            return false;
        }
    }
    /**
     * 删除目录
     * @param string $dirName 原目录
     * @return mixed|bool
     */
    public static function delDir($dirName = '')
    {
        if (!file_exists($dirName)) {
            return false;
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirName, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        return rmdir($dirName);
    }
    /**
     * 目录拷贝,返回被拷贝的文件数
     * @param $source string 源文件,填写绝对路径
     * @param $toDir string 目标路径,填写绝对路径
     * @param $force bool 开启会每次强制覆盖原文件,false不进行覆盖,存在文件不做处理
     * @return int 拷贝的文件数
     */
    public static function copyDir($source, $toDir, $force = true)
    {
        static $counter = 0;
        $paths = array_filter(scandir($source), function ($file) {
            return !in_array($file, ['.', '..']);
        });
        foreach ($paths as $path) {
            // 要拷贝的源文件的完整路径
            $sourceFullPath = $source . DIRECTORY_SEPARATOR . $path;
            // 要拷贝到的文件的路径
            $destFullPath = $toDir . DIRECTORY_SEPARATOR . $path;
            // 拷贝的目标地址如果是不是文件夹,那么说明文件夹不存在,那么首先创建文件夹
            if (is_dir($sourceFullPath)) {
                if (!is_dir($destFullPath)) {
                    mkdir($destFullPath);
                    chmod($destFullPath, 0755);
                }
                // 递归copy
                static::copyDir($sourceFullPath, $destFullPath, $force);
                continue;
            }
            // 不开启强制覆盖的话如果已经存在文件了那么直接跳过,不进行处理
            if (!$force && file_exists($destFullPath)) {
                continue;
            }
            // 每次copy成功文件计数器+1
            if (copy($sourceFullPath, $destFullPath)) {
                $counter++;
            }
        }
        return $counter;
    }
    /**
     * 得到指定目录里的信息
     * @param string $path 原目录
     * @return mixed|array
     */
    public static function getFolder($path = '')
    {
        if (!is_dir($path)) {
            return null;
        }
        $path = rtrim($path, '/') . '/';
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        $list = [];
        foreach ($glob as $k => $file) {
            $dir_arr = [];
            $dir_arr['name'] = self::getConvertEncoding($file->getFilename());
            if ($file->isDir()) {
                $dir_arr['type'] = 'dir';
                $dir_arr['size'] = self::getFileSizeFormat(self::getDirSize($file->getPathname()));
                $dir_arr['ext'] = '';
            } else {
                $dir_arr['type'] = 'file';
                $dir_arr['size'] = self::getFileSizeFormat($file->getSize());
                $dir_arr['ext'] = $file->getExtension();
            }
            $dir_arr['path_name'] = $file->getPathname();
            $dir_arr['atime'] = $file->getATime();
            $dir_arr['mtime'] = $file->getMTime();
            $dir_arr['ctime'] = $file->getCTime();
            $dir_arr['is_readable'] = $file->isReadable();
            $dir_arr['is_writeable'] = $file->isWritable();
            $dir_arr['base_name'] = $file->getBasename();
            $dir_arr['group'] = $file->getGroup();
            $dir_arr['inode'] = $file->getInode();
            $dir_arr['owner'] = $file->getOwner();
            $dir_arr['path'] = $file->getPath();
            $dir_arr['perms'] = $file->getPerms();
            $dir_arr['tp'] = $file->getType();
            $dir_arr['is_executable'] = $file->isExecutable();
            $dir_arr['is_file'] = $file->isFile();
            $dir_arr['is_link'] = $file->isLink();
            $list[$k] = $dir_arr;
        }
        $list == 1 ? sort($list) : rsort($list);
        return $list;
    }
    /**
     * 统计文件夹大小
     * @param string $dir 目录名
     * @return mixed|int
     */
    public static function getDirSize($dir)
    {
        $size = 0;
        $directoryIterator = new \DirectoryIterator($dir);
        foreach ($directoryIterator as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $filename = $fileInfo->getPathname();
                if ($fileInfo->isDir()) {
                    $size += self::getDirSize($filename);
                } else {
                    $size += filesize($filename);
                }
            }
        }
        return $size;
    }
    /**
     * 检测是否为空文件夹
     * @param string $dir 目录名
     * @return mixed|int
     */
    public static function emptyDir($dir)
    {
        $result = ($files = @scandir($dir)) && count($files) <= 2;
        return $result;
    }
    /**
     * 文件大小格式
     * @param int $byte 大小
     * @return mixed|string
     */
    public static function getFileSizeFormat($byte = 0)
    {
        $unit = '';
        if ($byte < 1024) {
            $unit = 'B';
        } else if ($byte < 10240) {
            $byte = self::getRoundPow($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 102400) {
            $byte = self::getRoundPow($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 1048576) {
            $byte = self::getRoundPow($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 10485760) {
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 104857600) {
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 1073741824) {
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else {
            $byte = self::getRoundPow($byte / 1073741824, 2);
            $unit = 'GB';
        }
        $byte .= $unit;
        return $byte;
    }
    /**
     * 辅助函数,该函数用来取舍小数点位数的,四舍五入
     * @param int $num 大小
     * @param int $precision 位数
     * @return mixed|int
     */
    public static function getRoundPow($num = 0, $precision = 2)
    {
        $sh = pow(10, $precision);
        return (round($num * $sh) / $sh);
    }
    /**
     * 获取文件扩展名
     * @param string $fileName 文件名
     * @return mixed|string
     */
    public static function getFileExt($fileName)
    {
        $result = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return $result;
    }
    /**
     * 转换字符编码
     * @param string $string 字符串
     * @return mixed|string
     */
    public static function getConvertEncoding($string)
    {
        // 根据系统进行配置
        $encode = stristr(PHP_OS, 'WIN') ? 'GBK' : 'UTF-8';
        $string = iconv($encode, 'UTF-8', $string);
        return $string;
    }
}
