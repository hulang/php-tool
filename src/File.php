<?php

declare(strict_types=1);

namespace hulang\tool;

/*
** 文件及文件夹处理类
*/

class File
{
    /**
     * 创建目录
     * @param string $dir 目录名
     * @return mixed|bool true 成功/false 失败
     */
    public static function mkDir($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (mkdir($dir, 0700) == false) {
                return false;
            }
            return true;
        }
        return true;
    }
    /**
     * 读取文件内容
     * @param string $filename 文件名
     * @return mixed|string 文件内容
     */
    public static function readFile($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @($content = file_get_contents($filename));
        } else {
            if (@($fp = fopen($filename, 'r'))) {
                @($content = fread($fp, filesize($filename)));
                @fclose($fp);
            }
        }
        return $content;
    }
    /**
     * 写文件
     * @param string $filename 文件名
     * @param string $writetext 文件内容
     * @param string $openmod 打开方式
     * @return mixed|bool true 成功/false 失败
     */
    public static function writeFile($filename, $writetext, $openmod = 'w')
    {
        if (@($fp = fopen($filename, $openmod))) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }
    /**
     * 删除文件
     * @param string $filename 文件名
     * @return mixed|bool true 成功/false 失败
     */
    public static function delFile($filename)
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
     * @return mixed|bool true 成功/false 失败
     */
    public static function delDir($dirName)
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
     * @return mixed|bool true 成功/false 失败
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
            $dir_arr['name'] = self::convertEncoding($file->getFilename());
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
     * @return mixed|int 文件夹大小(单位 B)
     */
    public static function getDirSize($dir)
    {
        $dirlist = opendir($dir);
        $dirsize = 0;
        while (false !== ($folderorfile = readdir($dirlist))) {
            if ($folderorfile != '.' && $folderorfile != '..') {
                $new_dir = $dir . '/' . $folderorfile;
                if (is_dir($new_dir)) {
                    $dirsize += self::getDirSize($new_dir);
                } else {
                    $dirsize += filesize($new_dir);
                }
            }
        }
        closedir($dirlist);
        return $dirsize;
    }
    /**
     * 检测是否为空文件夹
     * @param string $dir 目录名
     * @return mixed true 空/fasle 不为空
     */
    public static function emptyDir($dir)
    {
        return ($files = @scandir($dir)) && count($files) <= 2;
    }
    /**
     * 文件大小格式
     * @param $byte int 大小
     * @return mixed|int
     */
    public static function getFileSizeFormat($byte)
    {
        if ($byte < 1024) {
            $unit = 'B';
        } else if ($byte < 10240) {
            $byte = self::roundDp($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 102400) {
            $byte = self::roundDp($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 1048576) {
            $byte = self::roundDp($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 10485760) {
            $byte = self::roundDp($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 104857600) {
            $byte = self::roundDp($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 1073741824) {
            $byte = self::roundDp($byte / 1048576, 2);
            $unit = 'MB';
        } else {
            $byte = self::roundDp($byte / 1073741824, 2);
            $unit = 'GB';
        }
        $byte .= $unit;
        return $byte;
    }
    /**
     * 辅助函数 round_up(),该函数用来取舍小数点位数的,四舍五入
     * @return mixed|int
     */
    public static function roundDp($num, $dp)
    {
        $sh = pow(10, $dp);
        return (round($num * $sh) / $sh);
    }
    /**
     * 获取文件扩展名
     * @param string $fileName 文件名
     * @return mixed|string 扩展名
     */
    public static function getFileExt($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }
    /**
     * 转换字符编码
     * @param string $string 字符串
     * @return mixed|string
     */
    public static function convertEncoding($string)
    {
        //根据系统进行配置
        $encode = stristr(PHP_OS, 'WIN') ? 'GBK' : 'UTF-8';
        $string = iconv($encode, 'UTF-8', $string);
        return $string;
    }
}
