<?php

declare(strict_types=1);

namespace hulang\tool;

use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;

/**
 * Php Zip压缩和解压
 * @see \hulang\tool\ZipHelper
 * @package hulang\tool\ZipHelper
 * @mixin \hulang\tool\ZipHelper
 * @method static mixed|string|bool|Exception compress($dir = '') 压缩|备份[目录]
 * @method static mixed|string|bool|Exception unzip($package, $dir = '') 解压
 */
class ZipHelper
{
    /**
     * 压缩|备份[目录]
     *
     * @param string $dir 压缩|备份[目录]
     * @return mixed|string|bool|Exception
     * @throws Exception
     */
    public static function compress($dir = '')
    {
        $result = false;
        $zipFile = new ZipFile();
        try {
            if (!is_dir($dir)) {
                // 创建目录
                FileHelper::mkDir($dir);
            }
            // 设置[压缩包名称]
            $file = $dir . '-backup-' . date('YmdHis') . '.zip';
            if (!empty($name)) {
                $file = $name;
            }
            $zipFile->addDirRecursive($dir)->saveAsFile($file)->close();
            $result = true;
        } catch (ZipException $e) {
            $result = $e->getMessage();
        } finally {
            $zipFile->close();
        }
        return $result;
    }

    /**
     * 解压
     *
     * @param string $package 压缩包
     * @param string $dir 解压目录
     * @return mixed|string|bool|Exception
     * @throws Exception
     */
    public static function unzip($package, $dir = '')
    {
        $result = false;
        try {
            if (empty($package)) {
                throw new \Exception('压缩包不能为空');
            }
            if (!is_file($package)) {
                throw new \Exception('压缩包不存在');
            }
            // 打开压缩包
            $zip = new ZipFile();
            try {
                $zip->openFile($package);
            } catch (ZipException $e) {
                $zip->close();
                throw new \Exception($e->getMessage());
            }
            if (!is_dir($dir)) {
                // 创建目录
                FileHelper::mkDir($dir);
            }
            // 解压压缩包
            try {
                $zip->extractTo($dir);
                $result = true;
            } catch (ZipException $e) {
                throw new \Exception($e->getMessage());
            } finally {
                $zip->close();
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $result;
    }
}
