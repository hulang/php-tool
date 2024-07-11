<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 字符串助手类
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
class StrHelper
{
    protected static $snakeCache = [];

    protected static $camelCache = [];

    protected static $studlyCache = [];

    /**
     * 检查字符串中是否包含给定的子字符串
     * 
     * 此函数用于判断一个字符串( haystack )是否包含一个或多个其他字符串( needles )
     * 支持同时检查多个子字符串,且对字符串的检查不区分大小写
     * 
     * @param string $haystack 主字符串,即被检查的字符串
     * @param string|array $needles 子字符串或子字符串数组,即需要在主字符串中检查的字符串
     * @return bool 如果主字符串包含至少一个子字符串,则返回 true;否则返回 false
     */
    public static function Contains(string $haystack, $needles)
    {
        // 默认假设不包含子字符串
        $result = false;
        // 只有当需要检查的子字符串不为空时,才进行后续操作
        if (!empty($needles)) {
            // 将可能是一个字符串或字符串数组的 $needles 转换为数组形式,以便统一处理
            foreach ((array) $needles as $needle) {
                // 忽略空的子字符串
                if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                    // 如果找到至少一个子字符串,则更新结果为 true
                    $result = true;
                }
            }
        }
        // 返回检查结果
        return $result;
    }

    /**
     * 检查字符串是否以指定的字符串结尾
     * 
     * 此函数用于确定给定的字符串( haystack)是否以任何指定的字符串( needles)结尾
     * needles 可以是一个字符串或一个字符串数组
     * 如果 haystack 以任何 needles 中的字符串结尾,则函数返回 true,否则返回 false
     * 
     * @param string $haystack 要检查的主字符串
     * @param string|array $needles 可能的结尾字符串或字符串数组
     * @return bool 如果 haystack 以任何 needles 中的字符串结尾则返回 true,否则返回 false
     */
    public static function EndsWith(string $haystack, $needles)
    {
        $result = false;
        // 当 needles 不为空时,遍历 needles 中的每个字符串
        if (!empty($needles)) {
            foreach ((array) $needles as $needle) {
                // 检查 haystack 是否以当前的 needle 结尾
                // 使用静态方法 SubStr 和 Length 获取 haystack 的末尾部分,并与当前 needle 进行比较
                if ((string) $needle === static::SubStr($haystack, -static::Length($needle))) {
                    $result = true;
                    // 如果匹配成功,则设置结果为 true,并跳出循环
                    break;
                }
            }
        }
        // 返回检查结果
        return $result;
    }

    /**
     * 检查字符串是否以给定的前缀之一开始
     * 
     * 此函数用于确定一个字符串是否以一个或多个指定字符串的任何一個开始
     * 它支持多字节字符串,确保了对不同字符集的正确处理
     * 
     * @param string $haystack 要检查的主字符串
     * @param string|array $needles 可能的前缀,可以是一个字符串或者一个字符串数组
     * @return mixed|bool 如果字符串以任何给定的前缀开始,则返回true;否则返回false
     */
    public static function StartsWith(string $haystack, $needles)
    {
        $result = false;
        // 当前针数组不为空时,遍历每个前缀进行检查
        if (!empty($needles)) {
            foreach ((array) $needles as $needle) {
                // 确保前缀不是空字符串,然后检查主字符串是否以该前缀开始
                if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                    $result = true;
                    // 如果找到匹配的前缀,立即停止检查并返回结果
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * 生成指定长度的随机字符串
     * 
     * @param int $length 生成字符串的长度,默认为6
     * @param int $type 字符串的类型,不同类型的字符串包含不同的字符集
     *                  0: 包含大小写字母和额外字符
     *                  1: 包含3遍数字字符0-9
     *                  2: 包含大写字母和额外字符
     *                  3: 包含小写字母和额外字符
     *                  4: 包含中文字符和额外字符
     *                  其他: 包含大小写字母和数字以及额外字符
     * @param string $addChars 额外字符,默认为空,可添加额外的字符到字符集中
     * @return mixed|string 生成的随机字符串
     */
    public static function Random(int $length = 6, int $type = null, string $addChars = '')
    {
        $str = '';
        // 根据$type选择不同的字符集
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书' . $addChars;
                break;
            default:
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        // 当长度大于10时,对字符集进行重复,保证生成的字符串足够长
        if ($length > 10) {
            $chars = $type == 1 ? str_repeat($chars, $length) : str_repeat($chars, 5);
        }
        // 对于非中文类型的字符串,进行随机打乱,并取前$length个字符
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $length);
        } else {
            // 对于中文类型的字符串,逐个随机选取字符,避免substr对中文字符处理的不一致性
            for ($i = 0; $i < $length; $i++) {
                $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * 将字符串转换为小写
     * 
     * 此方法用于将输入的字符串转换为小写字母形式
     * 它使用多字节字符串函数`mb_strtolower`来确保字符串转换时考虑到了多字节字符集,特别是UTF-8编码的字符串
     * 这对于处理非ASCII字符集中的字符串非常有用,比如中文、希腊文、阿拉伯文等
     * 
     * @param string $value 待转换的字符串
     * @return mixed|string 转换为小写的字符串
     */
    public static function Lower(string $value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 将字符串转换为大写
     * 
     * 此方法用于将输入的字符串转换为大写形式
     * 它使用多字节字符串函数mb_strtoupper来确保字符串转换时对多字节字符集(如UTF-8)的支持,这样可以正确处理非ASCII字符的大小写转换
     * 
     * @param string $value 待转换的字符串
     * @return mixed|string 转换为大写后的字符串
     */
    public static function Upper(string $value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     * 
     * 本函数用于获取一个字符串的长度,支持多字节字符集
     * 通过使用mb_strlen函数,能够准确地计算出字符串中字符的数量
     * 而不是简单地基于字节计算长度,这对于处理包含非ASCII字符的字符串尤为重要
     *
     * @param string $value 待测量长度的字符串
     * @return mixed|int 字符串的长度
     */
    public static function Length(string $value)
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     * 
     * 本函数用于从一个字符串中截取指定长度的子字符串
     * 它支持多字节字符集,确保了对中文等非ASCII字符的正确处理
     * 
     * @param string $string 待截取的字符串
     * @param int $start 截取的起始位置.以0为字符串开始,正数表示从前面开始截取,负数表示从字符串末尾开始截取
     * @param int|null $length 截取的长度.如果不指定,则会截取到字符串末尾.注意,长度是指字符数量,而非字节数量
     * @return mixed|string 返回截取后的子字符串.如果参数有误或操作不可行,可能返回预期外的结果
     */
    public static function SubStr(string $string, int $start, int $length = null)
    {
        $result = mb_substr($string, $start, $length, 'UTF-8');
        return $result;
    }

    /**
     * 将驼峰命名转换为下划线命名
     * 
     * 此函数旨在将类名或变量名从驼峰命名法转换为下划线分隔的命名法
     * 这在需要将PHP的驼峰命名风格转换为数据库表名或文件名等使用下划线分隔的命名风格时非常有用
     *
     * @param string $value 驼峰命名的字符串
     * @param string $delimiter 用于分隔单词的字符,默认为下划线
     * @return mixed|string 转换后的字符串,如果输入的字符串已经是下划线分隔则直接返回
     */
    public static function Snake(string $value, string $delimiter = '_')
    {
        $key = $value;
        // 检查缓存中是否已经有转换结果,如果有则直接返回,以提高效率
        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }
        // 如果字符串不是全小写,则将其转换为驼峰命名法,并准备进行下划线转换
        if (!ctype_lower($value)) {
            // 先移除字符串中的所有空格,并将每个单词的首字母大写
            $value = preg_replace('/\s+/u', '', ucwords($value));
            // 使用正则表达式在每个大写字母前插入指定的分隔符,然后转换为小写
            $value = static::Lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        // 将转换后的字符串存入缓存,并返回结果
        $result = static::$snakeCache[$key][$delimiter] = $value;
        return $result;
    }

    /**
     * 将下划线分隔的字符串转换为驼峰式(首字母小写)
     * 
     * 此方法是为了解决在命名风格转换中,需要将下划线分隔的字符串转换为驼峰式(首字母小写)的需求
     * 它首先检查是否已缓存了转换结果,如果已缓存,则直接返回缓存结果,以提高性能
     * 如果未缓存,则调用其他方法进行转换,并将结果存入缓存,然后返回转换后的字符串
     * 
     * @param string $value 下划线分隔的字符串
     * @return string 驼峰式(首字母小写)的字符串
     */
    public static function Camel(string $value)
    {
        // 检查是否已缓存了转换结果
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }
        // 进行转换并缓存结果
        $result = static::$camelCache[$value] = lcfirst(static::Studly($value));
        return $result;
    }

    /**
     * 将字符串从下划线或连字符格式转换为驼峰式(首字母大写)
     * 这个方法是设计来优化字符串的格式,使其更适合在PHP中作为类名或变量名使用
     * 
     * @param string $value 待转换的字符串,可以是下划线或连字符分隔的字符串
     * @return mixed|string 返回转换后的驼峰式字符串.如果输入的字符串已经在缓存中,则直接从缓存返回,以提高性能
     */
    public static function Studly(string $value)
    {
        // 使用原始输入字符串作为缓存键,以便在缓存中查找
        $key = $value;
        // 检查是否有缓存的驼峰式字符串,如果有,则直接返回
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        // 使用空格替换字符串中的下划线和连字符,然后将每个单词的首字母大写
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        // 从转换后的字符串中移除空格,并将结果存储在缓存中,以备将来使用
        $result = static::$studlyCache[$key] = str_replace(' ', '', $value);
        // 返回转换后的驼峰式字符串
        return $result;
    }

    /**
     * 将字符串转换为标题格式
     * 
     * 标题格式意味着字符串中的每个单词的首字母都将被大写,其余字母小写
     * 此方法专门处理多字节字符集(如UTF-8),确保字符串的正确转换
     * 
     * @param string $value 待转换的字符串
     * @return mixed|string 转换后的标题格式字符串
     */
    public static function Title(string $value)
    {
        // 使用mb_convert_case函数将字符串转换为标题格式
        // MB_CASE_TITLE指定转换为标题格式,'UTF-8'指定字符集为UTF-8
        $result = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        return $result;
    }

    /**
     * 生成一个唯一的UUID(Universally Unique Identifier)
     * 
     * UUID是一个128位的全局唯一标识符,用于在分布式系统中识别唯一实体
     * 这个方法通过结合随机数、时间戳和机器标识来生成一个UUID
     * 
     * @return mixed|string 生成的UUID字符串
     */
    public static function uuid()
    {
        // 使用md5函数对uniqid和随机数的组合进行加密,以生成更独特的UUID
        $chars = md5(uniqid(strval(mt_rand(0, 9999)), true));
        // 根据UUID的标准格式,拼接字符串
        $value = substr($chars, 0, 8) . '-' . substr($chars, 8, 4) . '-';
        $value .= substr($chars, 12, 4) . '-' . substr($chars, 16, 4) . '-';
        // 最后拼接剩余的部分,并转换为大写,以满足UUID的格式要求
        $result = strtoupper($value . substr($chars, 20, 12));
        return $result;
    }

    /**
     * 生成一个带日期信息的唯一编码
     * 
     * 该方法通过结合当前日期和时间以及随机数来生成一个唯一编码
     * 编码的长度可以通过参数定制,默认长度为16
     * 如果指定的长度小于14,将会使用默认长度14,以确保编码至少包含日期和时间信息
     * 编码的前缀可以自定义,用于特定场景下的标识
     * 
     * @param integer $size 编码的长度,默认为16
     * @param string $prefix 编码的前缀,默认为空字符串
     * @return mixed|string 生成的唯一编码
     */
    public static function uniqidDate(int $size = 16, string $prefix = '')
    {
        // 确保编码长度至少为14,以包含日期和时间信息
        if ($size < 14) {
            $size = 14;
        }
        // 构建编码的基础部分,包括前缀、当前日期和简化的时间(小时+分钟)
        $code = $prefix . date('Ymd') . (date('H') + date('i')) . date('s');
        // 通过随机数补全编码长度
        while (strlen($code) < $size) {
            $code .= rand(0, 9);
        }
        return $code;
    }

    /**
     * 生成一个唯一且包含数字的字符串
     * 
     * 该方法通过结合当前时间戳和随机数来生成一个唯一且包含数字的字符串
     * 可以通过指定字符串的长度和前缀来定制生成的字符串
     * 如果指定的长度小于10,将会默认使用长度为10的字符串
     * 
     * @param integer $size 指定生成的字符串长度
     * @param string $prefix 指定生成的字符串的前缀
     * @return mixed|string 返回生成的唯一数字字符串
     */
    public static function uniqidNumber(int $size = 12, string $prefix = '')
    {
        // 将当前时间转换为字符串
        $time = strval(time());
        // 确保生成的字符串长度至少为10
        if ($size < 10) {
            $size = 10;
        }
        // 初始化代码字符串,包含前缀、时间戳的前两位数字相加的结果、时间戳的剩余部分和一个随机数字
        $code = $prefix . (intval($time[0]) + intval($time[1])) . substr($time, 2) . rand(0, 9);
        // 循环直到代码字符串的长度达到指定大小,通过添加随机数字来扩展字符串长度
        while (strlen($code) < $size) {
            $code .= rand(0, 9);
        }
        // 返回最终生成的字符串
        return $code;
    }

    /**
     * 将文本转换为UTF-8编码
     * 
     * 此函数用于识别并转换文本的当前编码到UTF-8
     * 它支持UTF-32BE、UTF-32LE、UTF-16BE和UTF-16LE等编码的自动识别
     * 并且如果编码无法自动识别,则使用mb_detect_encoding函数尝试检测
     * 
     * @param string $text 待转换的文本
     * @param string $target 目标编码,默认为UTF-8
     * @return mixed|string 转换后的UTF-8编码文本
     */
    public static function text2utf8(string $text, string $target = 'UTF-8')
    {
        // 提取文本的前2个和前4个字节,用于编码识别
        [$first2, $first4] = [substr($text, 0, 2), substr($text, 0, 4)];
        // 根据字节序判断当前编码格式
        if ($first4 === chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF)) {
            $ft = 'UTF-32BE';
        } elseif ($first4 === chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00)) {
            $ft = 'UTF-32LE';
        } elseif ($first2 === chr(0xFE) . chr(0xFF)) {
            $ft = 'UTF-16BE';
        } elseif ($first2 === chr(0xFF) . chr(0xFE)) {
            $ft = 'UTF-16LE';
        }
        // 使用mb_convert_encoding进行编码转换,如果无法自动识别编码,则使用mb_detect_encoding尝试检测
        $result = mb_convert_encoding($text, $target, $ft ?? mb_detect_encoding($text));
        return $result;
    }

    /**
     * 加密数据处理方法
     * 本方法用于对给定的数据进行加密处理,采用AES-256-CBC加密算法,结合随机生成的初始化向量,确保加密的安全性和唯一性
     * 加密后的数据通过base64编码,方便传输或存储
     * 
     * @param mixed $data 需要加密的数据,可以是任何类型
     * @param string $skey 加密使用的安全密钥,必须是字符串
     * @return mixed|string 返回加密后的数据,以base64编码的字符串形式表示
     */
    public static function encrypt($data, string $skey)
    {
        // 生成一个随机的16字节初始化向量,用于AES加密过程,增强加密的随机性和安全性
        $iv = self::random(16, 3);
        // 使用AES-256-CBC加密算法对数据进行加密,加密后的数据序列化后进行加密,以便于处理复杂数据结构
        $value = openssl_encrypt(serialize($data), 'AES-256-CBC', $skey, 0, $iv);
        // 将加密的初始化向量和加密值一起编码为JSON格式,然后进行base64编码,方便后续处理和传输
        // 这里将加密信息和初始化向量一起传输,是为了在解密时能够使用同样的初始化向量进行解密
        $result = self::enSafe64(json_encode(['iv' => $iv, 'value' => $value]));
        return $result;
    }

    /**
     * 解密数据
     * 
     * 本函数用于对经过加密处理的数据进行解密,解密过程需要使用到加密时使用的密钥
     * 加密数据的格式为JSON,包含加密值和初始化向量(IV)
     * 使用AES-256-CBC加密算法进行解密,解密后的数据通过unserialize函数反序列化为原始对象或数组
     * 
     * @param string $data 待解密的数据,通常是经过加密和Base64编码的字符串
     * @param string $skey 解密使用的密钥,必须与加密时使用的密钥相同
     * @return mixed|string 解密后的数据,可能是字符串、数组或对象
     */
    public static function decrypt(string $data, string $skey)
    {
        // 解码加密数据,获取加密值和初始化向量
        $attr = json_decode(self::deSafe64($data), true);
        // 使用AES-256-CBC算法解密数据,并恢复原始对象或数组
        $result = unserialize(openssl_decrypt($attr['value'], 'AES-256-CBC', $skey, 0, $attr['iv']));
        return $result;
    }

    /**
     * 对文本进行Base64编码,并确保编码适用于URL环境
     * 
     * Base64Url编码是一种针对Base64编码的变体,主要用于在URL和HTTP头中使用
     * 它将标准Base64编码中的加号(+)和斜杠(/)替换为连字符(-)和下划线(_),并且去除结尾的等号(=)以避免在URL中的解析问题
     * 
     * @param string $text 待编码的文本
     * @return mixed|string 返回编码后的字符串
     */
    public static function enSafe64(string $text)
    {
        // 使用base64_encode对文本进行编码
        // 替换编码中的加号和斜杠为连字符和下划线,以适应URL环境
        // 移除结尾的等号,以避免在URL中引起问题
        $result = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        return $result;
    }

    /**
     * 对Base64Url编码的字符串进行解码
     * 
     * Base64Url编码是一种针对URL安全的Base64编码变体,它将标准Base64编码中的加号(+)和斜杠(/)替换为连字符(-)和下划线(_),并且不包含结尾的等号(=)
     * 本函数旨在将这种编码的字符串解码回原始形式
     * 
     * @param string $text 待解码的Base64Url编码字符串
     * @return mixed|string 解码后的原始字符串,如果解码失败则返回原字符串
     */
    public static function deSafe64(string $text)
    {
        // 使用strtr函数将Base64Url编码中的字符转换为标准Base64编码字符
        // 使用str_pad函数确保解码字符串的长度是4的倍数,这是Base64解码的要求
        // 最后使用base64_decode函数对调整后的字符串进行解码
        $result = base64_decode(str_pad(strtr($text, '-_', '+/'), strlen($text) % 4, '='));
        // 返回解码结果,如果解码失败则返回原字符串
        return $result;
    }

    /**
     * 压缩并序列化数据,然后使用安全的Base64编码
     * 
     * 此方法旨在提供一种机制,将可能较大的数据对象压缩并编码成字符串,以便于存储或通过网络传输
     * 使用了gzcompress进行数据压缩,以减少数据大小,并使用serialize将PHP对象转换为可存储的字符串表示
     * 最后,使用enSafe64函数对压缩和序列化的数据进行Base64编码,以确保数据在传输过程中的安全性
     * 
     * @param mixed $data 要进行压缩、序列化和编码的数据对象或值
     * @return mixed|string 返回编码后的字符串,如果操作失败则返回原始数据
     */
    public static function enzip($data)
    {
        // 将数据压缩、序列化后进行安全的Base64编码
        $result = self::enSafe64(gzcompress(serialize($data)));
        return $result;
    }

    /**
     * 解压数据对象
     * 该方法用于解析经过特定处理的压缩字符串,以恢复原始数据
     * 具体流程包括:先对字符串进行Base64解码,然后进行gzip解压缩,最后反序列化以得到原始对象
     * 
     * @param string $string 经过Base64编码和gzip压缩的字符串
     * @return mixed|string 返回解压并反序列化后的原始数据,可以是任何类型
     */
    public static function dezip(string $string)
    {
        // 先解密Base64编码的字符串,再进行gzip解压缩,最后反序列化以恢复原始数据
        $result = unserialize(gzuncompress(self::deSafe64($string)));
        return $result;
    }
}
