<?php

declare(strict_types=1);

namespace hulang\tool;

use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;

/**
 * PHP PhpZip压缩和解压
 * Class ZipHelper
 * URL https://packagist.org/packages/nelexa/zip
 * @package nelexa
 */
class ZipHelper
{
    /**
     * 压缩
     *
     * @param string $name 名称
     * @param string $dir 压缩目录
     * @return string
     * @throws Exception
     */
    public static function compress($name = '', $dir = '')
    {
    }
    /**
     * 解压
     *
     * @param string $name 压缩包名称
     * @return string
     * @throws Exception
     */
    public static function unzip($name)
    {
    }
}
