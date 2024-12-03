<?php

declare(strict_types=1);

namespace hulang\tool;

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
class FileHelper extends FileMime
{
    /**
     * 获取文件属性
     * 
     * 本方法用于获取指定文件的属性信息
     * 如果文件存在且非空,则返回一个包含文件信息的对象
     * 如果文件不存在或为空字符串,则返回空对象
     * 
     * @param string $filename 文件名,可以为空.如果为空,则方法不会尝试获取文件信息
     * @return mixed|\SplFileInfo 返回一个包含文件信息的对象,如果文件不存在或参数为空,则返回空对象
     */
    public static function getFileAttr($filename = ''): \SplFileInfo
    {
        $obj = '';
        // 当文件名不为空且指定的文件存在时,创建一个\SplFileInfo对象来获取文件信息
        if (!empty($filename) && is_file($filename)) {
            $obj = new \SplFileInfo($filename);
        }
        return $obj;
    }

    /**
     * 计算文件的MD5值
     * 
     * 此函数用于计算给定文件的MD5值,MD5是一种散列算法,可以生成一个唯一的128位(16字节)散列值
     * 通常用于检查文件的完整性
     * 
     * @param string $filename 要计算MD5值的文件路径如果文件路径为空或不是有效文件,则返回空字符串
     * @return string 文件的MD5值如果文件不存在或参数不正确,则返回空字符串
     */
    public static function getFileMd5($filename = '')
    {
        $result = '';
        // 检查文件路径是否非空且为有效文件
        if (!empty($filename) && is_file($filename)) {
            // 使用md5_file函数直接获取文件的MD5值
            $result = md5_file($filename);
        }
        // 返回文件的MD5值或空字符串
        return $result;
    }

    /**
     * 计算文件的 SHA-1 哈希值
     * 
     * 此方法用于计算给定文件的 SHA-1 哈希值
     * 它首先检查文件是否存在,然后使用 PHP 的内置函数 sha1_file 来计算哈希值
     * 
     * @param string $filename 要计算哈希值的文件路径.如果文件名为空或不是有效文件,则返回空字符串
     * @return string 文件的 SHA-1 哈希值字符串.如果文件不存在或 $filename 参数无效,则返回空字符串
     */
    public static function getFileSha1($filename = '')
    {
        $result = '';
        // 检查文件是否存在且不为空
        if (!empty($filename) && is_file($filename)) {
            $result = sha1_file($filename);
        }
        return $result;
    }

    /**
     * 创建目录
     * 
     * 该方法用于静态方式创建一个目录
     * 如果目录已存在,方法将不进行任何操作
     * 如果目录不存在且成功创建,则返回true;否则返回false
     * 
     * @param string $dir 目录名.默认为空字符串,表示使用方法调用时的路径
     * @return mixed|bool 方法执行结果.成功创建目录返回true,否则返回false
     */
    public static function mkDir($dir = '')
    {
        $result = false;
        // 检查目录是否存在,如果不存在则尝试创建
        if (!is_dir($dir)) {
            // 尝试递归创建目录,权限设置为0755
            if (mkdir($dir, 0755, true)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * 读取文件内容
     * 
     * 该方法用于静态方式读取指定文件的内容
     * 如果未指定文件名或文件不存在,则返回空字符串
     * 
     * @param string $filename 文件名,可以为空.如果为空,则方法将不执行任何操作并返回空字符串
     * @return mixed|string 返回读取的文件内容作为字符串,如果文件名为空或文件不存在,则返回空字符串
     */
    public static function readFile($filename = '')
    {
        $content = '';
        // 当文件名不为空且确实是现有文件时,尝试读取文件内容
        if (!empty($filename) && is_file($filename)) {
            $content = file_get_contents($filename);
        }
        return $content;
    }

    /**
     * 写文件
     * 
     * 该函数用于将指定的文字写入到指定的文件中
     * 如果文件不存在,会尝试创建文件及其目录
     * 函数返回写入操作的成功与否
     * 
     * @param string $filename 文件名,可以包含路径.如果文件名为空,写入操作将失败
     * @param string $writetext 要写入文件的文本内容.如果内容为空,写入操作将失败
     * @param mixed|string|int $mode 写入文件的模式,默认为LOCK_EX,可参考PHP文件操作模式的文档
     * @return mixed|bool 如果写入成功,返回true;如果写入失败或参数不满足条件,返回false
     */
    public static function writeFile($filename = '', $writetext = '', $mode = LOCK_EX)
    {
        // 检查文件名和内容是否为空,如果为空则直接返回false
        if (!empty($filename) && !empty($writetext)) {
            // 使用pathinfo获取文件的目录信息,为创建目录做准备
            $fileArr = pathinfo($filename);
            // 调用mkDir方法尝试创建文件所在的目录
            self::mkDir($fileArr['dirname']);
            // 使用file_put_contents将文本内容写入文件,返回写入的字节数
            $size = file_put_contents($filename, $writetext, $mode);
            // 如果成功写入字节大于0,则返回true,表示写入成功
            if ($size > 0) {
                return true;
            } else {
                // 如果写入的字节为0,表示写入失败,返回false
                return false;
            }
        } else {
            // 如果文件名或内容为空,直接返回false
            return false;
        }
    }

    /**
     * 删除指定的文件
     * 
     * 该方法用于检查给定文件是否存在,如果存在则将其删除
     * 删除文件是一个常见的操作,可能用于清理临时文件或当文件不再需要时
     * 
     * @param string $filename 要删除的文件的路径.如果未提供路径,则默认为空字符串
     * @return mixed|bool 如果文件成功删除,返回true;如果文件不存在或删除失败,返回false
     */
    public static function delFile($filename = '')
    {
        // 检查文件是否存在,如果存在则尝试删除
        if (file_exists($filename)) {
            unlink($filename);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除指定的目录及其内容
     * 
     * 该方法用于递归删除指定路径下的所有文件和子目录
     * 首先检查目录是否存在,然后利用RecursiveIteratorIterator和RecursiveDirectoryIterator遍历目录
     * 逐个删除文件和子目录,最后删除空目录本身
     * 
     * @param string $dirName 需要删除的目录路径
     * @return mixed|bool 返回布尔值表示删除操作的成功与否,如果目录不存在则返回false
     */
    public static function delDir($dirName = '')
    {
        // 检查目录是否存在,如果不存在则直接返回false
        if (!file_exists($dirName)) {
            return false;
        }
        // 使用RecursiveIteratorIterator和RecursiveDirectoryIterator遍历目录
        // CHILD_FIRST选项使得先删除文件再删除子目录
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirName, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        // 遍历每个文件或子目录,如果是目录则调用rmdir删除,如果是文件则调用unlink删除
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        // 删除空目录本身,并返回操作结果
        return rmdir($dirName);
    }

    /**
     * 复制目录及其内容到目标目录
     * 
     * 该方法递归地复制指定源目录到目标目录
     * 每个文件和子目录都将被复制,如果目标目录不存在,则会创建它
     * 可以通过设置$force参数来控制是否覆盖
     * 已存在的文件
     * 
     * @param string $source 源目录的绝对路径
     * @param string $toDir 目标目录的绝对路径
     * @param bool $force 如果为true,则覆盖已存在的文件;如果为false,则不覆盖
     * @return int 返回成功复制的文件数量
     */
    public static function copyDir($source, $toDir, $force = true)
    {
        // 用于跟踪复制的文件数量
        static $counter = 0;
        // 获取源目录中的文件和子目录列表,排除'.'和'..'
        $paths = array_filter(scandir($source), function ($file) {
            return !in_array($file, ['.', '..']);
        });
        // 遍历源目录中的每个文件和子目录
        foreach ($paths as $path) {
            // 构建源文件或子目录的完整路径
            $sourceFullPath = $source . DIRECTORY_SEPARATOR . $path;
            // 构建目标文件或子目录的完整路径
            $destFullPath = $toDir . DIRECTORY_SEPARATOR . $path;
            // 如果当前项是子目录
            // 拷贝的目标地址如果是不是文件夹,那么说明文件夹不存在,那么首先创建文件夹
            if (is_dir($sourceFullPath)) {
                // 如果目标子目录不存在,则创建它
                if (!is_dir($destFullPath)) {
                    mkdir($destFullPath);
                    chmod($destFullPath, 0755);
                }
                // 递归地复制子目录
                static::copyDir($sourceFullPath, $destFullPath, $force);
                // 继续处理下一个文件或子目录
                continue;
            }
            // 如果不强制覆盖且目标文件已存在,则跳过当前文件
            // 不开启强制覆盖的话如果已经存在文件了那么直接跳过,不进行处理
            if (!$force && file_exists($destFullPath)) {
                continue;
            }
            // 成功复制文件后增加计数器
            if (copy($sourceFullPath, $destFullPath)) {
                $counter++;
            }
        }
        // 返回成功复制的文件数量
        return $counter;
    }

    /**
     * 获取指定路径下的目录信息
     * 
     * 该方法用于获取给定路径下的所有文件和目录的详细信息
     * 它通过迭代指定路径中的每个文件和目录来实现,对每个文件和目录
     * 收集信息如名称、类型、大小、访问时间等,并以数组形式返回
     * 
     * @param string $path 要查询的目录路径,默认为空,表示当前目录
     * @param array $exclude 需要排除的文件或目录名称,默认为空数组
     *                       如果指定了排除的文件或目录,则不会返回这些文件或目录的信息
     * @return array 如果路径是有效的目录,则返回包含文件和目录信息的数组
     *               如果路径无效或不是目录,则返回 null
     */
    public static function getFolder($path = '', $exclude = [])
    {
        // 检查路径是否为有效目录
        if (!is_dir($path)) {
            return null;
        }
        // 处理路径,确保其以斜杠结尾,并转换为真实路径
        $path = rtrim($path, '/') . '/';
        $path = realpath($path);
        // 使用FilesystemIterator遍历目录,将文件名作为键
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        // 排除一些特殊文件,如'.'和'..'
        $excludeCharacters = ['.', '..'];
        $excludeCharacters = array_merge($excludeCharacters, $exclude);
        // 用于存储目录信息的数组
        $list = [];
        foreach ($glob as $k => $file) {
            $arr = [];
            // 排除一些特殊文件
            if (in_array($file->getFilename(), $excludeCharacters)) {
                continue;
            }
            // 判断文件是目录还是普通文件,并获取相应的信息
            $arr['type'] = $file->getType();
            if ($file->isDir()) {
                $arr['size'] = self::getFileSizeFormat(self::getDirSize($file->getPathname()));
            } else {
                $arr['size'] = self::getFileSizeFormat($file->getSize());
            }
            $arr['ext'] = $file->getExtension();
            // 文件名的转换编码
            $arr['name'] = self::getConvertEncoding($file->getFilename());
            // 收集文件的其他信息
            $arr['path_name'] = $file->getPathname();
            $arr['atime'] = $file->getATime();
            $arr['mtime'] = $file->getMTime();
            $arr['ctime'] = $file->getCTime();
            $arr['is_readable'] = $file->isReadable();
            $arr['is_writeable'] = $file->isWritable();
            $arr['base_name'] = $file->getBasename();
            $arr['group'] = $file->getGroup();
            $arr['inode'] = $file->getInode();
            $arr['owner'] = $file->getOwner();
            $arr['path'] = $file->getPath();
            $arr['perms'] = $file->getPerms();
            $arr['is_executable'] = $file->isExecutable();
            $arr['is_file'] = $file->isFile();
            $arr['is_link'] = $file->isLink();
            $arr['SplFileInfo'] = new \SplFileInfo($file->getFilename());
            // 将文件或目录信息添加到结果数组
            $list[$k] = $arr;
        }
        // 根据文件大小对结果进行排序,如果只有一个元素则按名称排序
        $list == 1 ? sort($list) : rsort($list);
        // 返回包含文件和目录信息的数组
        return $list;
    }

    /**
     * 统计给定目录的大小
     * 
     * 该方法通过递归遍历指定目录下的所有文件和子目录,计算它们的大小并返回总大小
     * 大小以字节为单位
     * 
     * @param string $dir 需要统计大小的目录路径
     * @return mixed|int 返回目录的大小,如果无法访问目录则返回false
     */
    public static function getDirSize($dir)
    {
        // 初始化大小变量
        $size = 0;
        // 创建DirectoryIterator实例,用于遍历目录
        $directoryIterator = new \DirectoryIterator($dir);
        // 遍历目录中的每个文件和子目录
        foreach ($directoryIterator as $fileInfo) {
            // 忽略当前目录(. )和父目录(..)
            if (!$fileInfo->isDot()) {
                $filename = $fileInfo->getPathname();
                // 如果是目录,则递归计算该目录的大小
                if ($fileInfo->isDir()) {
                    $size += self::getDirSize($filename);
                } else {
                    // 如果是文件,则累加该文件的大小
                    $size += filesize($filename);
                }
            }
        }
        // 返回目录的总大小
        return $size;
    }

    /**
     * 检查指定的目录是否为空
     * 
     * 该方法通过扫描目录中的文件数量来判断目录是否为空或只包含根目录的两个默认项(. 和 ..)
     * 如果目录包含除.和..之外的任何其他文件或子目录,则被认为是非空的
     * 
     * @param string $dir 需要检查的目录路径
     * @return bool 如果目录为空或只包含.和..,则返回true;否则返回false
     */
    public static function emptyDir($dir)
    {
        // 使用scandir函数扫描目录,并检查结果是否仅包含默认的两个项
        $files = scandir($dir);
        $result = count($files) == 2;
        return $result;
    }

    /**
     * 将字节单位的文件大小转换为更易读的格式
     * @param int $byte 文件大小,默认为0
     * @return mixed|string 格式化后的文件大小,带有相应的单位
     */
    public static function getFileSizeFormat($byte = 0)
    {
        // 初始化单位为空字符串
        $unit = '';
        // 根据文件大小,选择合适的单位和精度进行转换
        if ($byte < 1024) {
            // 小于1024字节时,单位为字节(B)
            $unit = 'B';
        } else if ($byte < 10240) {
            // 1024到10240字节,转换为千字节(KB),保留两位小数
            $byte = self::getRoundPow($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 102400) {
            // 10240到102400字节,同样转换为千字节(KB)
            $byte = self::getRoundPow($byte / 1024, 2);
            $unit = 'KB';
        } else if ($byte < 1048576) {
            // 102400到1048576字节,转换为兆字节(MB)
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 10485760) {
            // 1048576到10485760字节,转换为兆字节(MB)
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 104857600) {
            // 10485760到104857600字节,转换为兆字节(MB)
            $byte = self::getRoundPow($byte / 1048576, 2);
            $unit = 'MB';
        } else if ($byte < 1073741824) {
            // 104857600到1073741824字节,转换为吉字节(GB)
            $byte = self::getRoundPow($byte / 1073741824, 2);
            $unit = 'GB';
        }
        // 将单位附加到字节数上,形成格式化后的字符串
        $byte .= $unit;
        // 返回格式化后的文件大小
        return $byte;
    }

    /**
     * 辅助函数,用于对数字进行四舍五入到指定精度
     * 
     * 此函数设计用于在进行数学计算时,确保结果被精确地四舍五入到指定的小数位数
     * 通过将数字乘以一个适当的10的幂,然后使用PHP的round函数进行四舍五入,最后再除以相同的幂,可以实现对数字进行四舍五入而不改变其原始大小的比例
     * 
     * @param int $num 需要被四舍五入的数字
     * @param int $precision 指定的精度,即小数点后的位数
     * @return mixed 四舍五入后的结果,如果输入的$num不是数字,则返回原始输入值
     */
    public static function getRoundPow($num = 0, $precision = 2)
    {
        // 计算10的精度次幂,用于后续的四舍五入操作
        $sh = pow(10, $precision);
        // 将数字$num乘以$sh后进行四舍五入,然后再除以$sh,以得到精确到指定精度的结果
        $result = (round($num * $sh) / $sh);
        return $result;
    }

    /**
     * 获取文件扩展名
     * 
     * 该静态方法用于提取给定文件名的扩展名
     * 它通过对文件名使用pathinfo函数进行处理,然后将扩展名转换为小写后返回
     * 这样做的目的是为了获得标准化的、不区分大小写的文件扩展名,以便于后续的比较和处理
     * 
     * @param string $fileName 文件名,可以包含路径和扩展名
     * @return mixed|string 返回小写的文件扩展名.如果无法确定扩展名,则可能返回空字符串
     */
    public static function getFileExt($fileName)
    {
        // 使用pathinfo函数获取文件名的扩展名,并通过strtolower函数将其转换为小写
        $result = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return $result;
    }

    /**
     * 根据操作系统平台转换字符串的编码格式
     * 
     * 本函数主要用于处理字符串的编码转换问题,确保字符串在不同操作系统下都能正确处理
     * 它会根据当前操作系统是Windows还是其他系统(默认为UTF-8),将字符串从一个编码格式转换为UTF-8
     * 这样做的目的是为了兼容性,因为在Windows系统中,GB2312或GBK编码更为常见
     *
     * @param string $string 待转换编码的字符串
     * @return mixed|string 转换后的字符串,如果转换失败则返回原字符串
     */
    public static function getConvertEncoding($string)
    {
        // 根据操作系统类型确定源编码格式
        $encode = stristr(PHP_OS, 'WIN') ? 'GBK' : 'UTF-8';
        // 尝试将字符串从源编码转换为UTF-8
        $result = iconv($encode, 'UTF-8', $string);
        // 返回转换后的字符串
        return $result;
    }
}
