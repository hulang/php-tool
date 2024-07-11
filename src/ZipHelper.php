<?php

declare(strict_types=1);

namespace hulang\tool;

use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;

/**
 * Zip压缩和解压助手类
 * @see \hulang\tool\ZipHelper
 * @package hulang\tool\ZipHelper
 * @mixin \hulang\tool\ZipHelper
 * @method static mixed|string|bool|Exception compress($dir = '') 压缩|备份[目录]
 * @method static mixed|string|bool|Exception unzip($package, $dir = '') 解压
 */
class ZipHelper
{
    /**
     * 压缩指定目录到一个ZIP文件
     * 
     * 此函数用于将给定的目录及其内容压缩到一个ZIP文件中,用于备份或传输目的
     * 如果目录不存在,它将尝试创建该目录
     * 压缩文件的命名格式为:<目录名>-backup-<当前时间戳>.zip,除非提供了自定义名称
     * 
     * @param string $dir 需要被压缩的目录路径.如果不提供,默认为空,表示使用函数内部定义的路径
     * @return mixed|array|string|bool|Exception 如果操作成功,返回包含压缩结果信息的数组；如果发生错误,返回异常
     * @throws Exception 如果在压缩过程中遇到错误,将抛出异常
     */
    public static function compress($dir = '')
    {
        // 初始化返回数组,代码状态默认为0,表示未开始
        $arr = [];
        $arr['code'] = 0;
        $arr['msg'] = '';
        // 创建ZipFile对象,用于后续的文件压缩操作
        $zipFile = new ZipFile();
        try {
            // 检查目录是否存在,如果不存在则尝试创建
            if (!is_dir($dir)) {
                // 创建目录
                FileHelper::mkDir($dir);
            }
            // 构造备份文件名,包含目录名、backup标识、当前时间戳和.zip后缀
            $file = $dir . '-backup-' . date('YmdHis') . '.zip';
            // 调用ZipFile对象的方法,将指定目录递归添加到ZIP文件中,然后保存ZIP文件并关闭对象
            $zipFile->addDirRecursive($dir)->saveAsFile($file)->close();
            // 如果操作成功,修改返回数组中的状态码为1
            $arr['code'] = 1;
        } catch (ZipException $e) {
            // 如果在压缩过程中发生错误,将错误信息存入返回数组中
            $arr['msg'] = $e->getMessage();
        } finally {
            // 无论操作成功与否,都确保关闭ZipFile对象
            $zipFile->close();
        }
        // 返回操作结果数组
        return $arr;
    }

    /**
     * 解压ZIP格式的压缩包到指定目录
     * 
     * 该方法首先检查压缩包是否为空和是否存在,然后尝试打开压缩包并检查是否能够成功
     * 如果目标目录不存在,方法会尝试创建该目录
     * 接着,方法会尝试解压压缩包到指定目录,并在完成操作后关闭压缩包
     * 如果在任何一步中出现错误,都会抛出异常并返回错误信息
     * 
     * @param string $package 压缩包的文件路径
     * @param string $dir 解压的目标目录,如果不指定,则使用空字符串
     * @return mixed 如果操作成功,返回包含代码(1表示成功)和消息的数组；如果失败,返回包含错误代码和消息的数组
     * @throws Exception 如果操作过程中出现任何异常,都会抛出
     */
    public static function unzip($package, $dir = '')
    {
        // 初始化返回数组,默认操作失败
        $arr = [];
        $arr['code'] = 0;
        $arr['msg'] = '';
        try {
            // 检查压缩包是否为空
            if (empty($package)) {
                throw new \Exception('压缩包不能为空');
            }
            // 检查压缩包文件是否存在
            if (!is_file($package)) {
                throw new \Exception('压缩包不存在');
            }
            // 创建ZipFile对象,用于操作压缩包
            $zip = new ZipFile();
            try {
                // 尝试打开压缩包文件
                $zip->openFile($package);
            } catch (ZipException $e) {
                // 如果打开失败,关闭压缩包并重新抛出异常
                $zip->close();
                throw new \Exception($e->getMessage());
            }
            // 检查目标目录是否存在,如果不存在则尝试创建
            if (!is_dir($dir)) {
                // 创建目录
                FileHelper::mkDir($dir);
            }
            // 尝试解压压缩包到指定目录
            try {
                $zip->extractTo($dir);
                // 如果解压成功,修改返回数组中的代码表示成功
                $arr['code'] = 1;
            } catch (ZipException $e) {
                // 如果解压失败,抛出异常
                throw new \Exception($e->getMessage());
            } finally {
                // 确保在方法结束前关闭压缩包
                $zip->close();
            }
        } catch (\Exception $e) {
            // 如果在处理过程中发生异常,将异常消息存入返回数组
            $arr['msg'] = $e->getMessage();
        }
        // 返回操作结果数组
        return $arr;
    }
}
