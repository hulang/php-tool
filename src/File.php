<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 文件及文件夹处理类
 * @see \hulang\tool
 * @package hulang\tool
 * @mixin \hulang\tool
 * @method static mixed|bool mkDir($dir = '') 创建目录
 * @method static mixed|SplFileInfo getFileAttr($filename = '') 获取文件属性
 * @method static mixed|string readFile($filename = '') 读取文件内容
 * @method static mixed|bool writeFile($filename = '', $writetext = '', $mode = LOCK_EX) 写文件
 * @method static mixed|bool delFile($filename = '') 删除文件
 * @method static mixed|bool delDir($dirName = '') 删除目录
 * @method static mixed|bool copyDir($surDir, $toDir) 复制目录
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
    public static function getFileAttr($filename = '')
    {
        $content = '';
        if (!empty($filename) && is_file($filename)) {
            $content = new \SplFileInfo($filename);
        }
        return $content;
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
        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::delDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }
    /**
     * 复制目录
     * @param string $surDir 原目录
     * @param string $toDir 目标目录
     * @return mixed|bool
     */
    public static function copyDir($surDir, $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }
        if (!file_exists($toDir)) {
            self::mkDir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copyDir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
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
