<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 字符串操作类
 * @see \hulang\tool
 * @package hulang\tool
 * @mixin \hulang\tool
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
class Str
{
    protected static $snakeCache = [];

    protected static $camelCache = [];

    protected static $studlyCache = [];

    /**
     * 检查字符串中是否包含某些字符串
     * @param string $haystack
     * @param string|array $needles
     * @return mixed|bool
     */
    public static function Contains(string $haystack, $needles)
    {
        $result = false;
        if (!empty($needles)) {
            foreach ((array) $needles as $needle) {
                if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     *
     * @param string $haystack
     * @param string|array $needles
     * @return mixed|bool
     */
    public static function EndsWith(string $haystack, $needles)
    {
        $result = false;
        if (!empty($needles)) {
            foreach ((array) $needles as $needle) {
                if ((string) $needle === static::SubStr($haystack, -static::Length($needle))) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 检查字符串是否以某些字符串开头
     *
     * @param string $haystack
     * @param string|array $needles
     * @return mixed|bool
     */
    public static function StartsWith(string $haystack, $needles)
    {
        $result = false;
        if (!empty($needles)) {
            foreach ((array) $needles as $needle) {
                if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 生成指定长度的随机字母数字组合的字符串
     *
     * @param int $length
     * @param int $type
     * @param string $addChars
     * @return mixed|string
     */
    public static function Random(int $length = 6, int $type = null, string $addChars = '')
    {
        $str = '';
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
        if ($length > 10) {
            $chars = $type == 1 ? str_repeat($chars, $length) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $length);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * 字符串转小写
     *
     * @param string $value
     * @return mixed|string
     */
    public static function Lower(string $value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     *
     * @param string $value
     * @return mixed|string
     */
    public static function Upper(string $value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     *
     * @param string $value
     * @return mixed|int
     */
    public static function Length(string $value)
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     *
     * @param string $string
     * @param int $start
     * @param int|null $length
     * @return mixed|string
     */
    public static function SubStr(string $string, int $start, int $length = null)
    {
        $result = mb_substr($string, $start, $length, 'UTF-8');
        return $result;
    }

    /**
     * 驼峰转下划线
     *
     * @param string $value
     * @param string $delimiter
     * @return mixed|string
     */
    public static function Snake(string $value, string $delimiter = '_')
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = static::Lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        $result = static::$snakeCache[$key][$delimiter] = $value;
        return $result;
    }

    /**
     * 下划线转驼峰(首字母小写)
     *
     * @param string $value
     * @return mixed|string
     */
    public static function Camel(string $value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }
        $result = static::$camelCache[$value] = lcfirst(static::Studly($value));
        return $result;
    }

    /**
     * 下划线转驼峰(首字母大写)
     *
     * @param string $value
     * @return mixed|string
     */
    public static function Studly(string $value)
    {
        $key = $value;
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        $result = static::$studlyCache[$key] = str_replace(' ', '', $value);
        return $result;
    }

    /**
     * 转为首字母大写的标题格式
     *
     * @param string $value
     * @return mixed|string
     */
    public static function Title(string $value)
    {
        $result = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        return $result;
    }

    /**
     * 生成 UUID 编码
     * @return mixed|string
     */
    public static function uuid()
    {
        $chars = md5(uniqid(strval(mt_rand(0, 9999)), true));
        $value = substr($chars, 0, 8) . '-' . substr($chars, 8, 4) . '-';
        $value .= substr($chars, 12, 4) . '-' . substr($chars, 16, 4) . '-';
        $result = strtoupper($value . substr($chars, 20, 12));
        return $result;
    }

    /**
     * 生成日期编码
     * @param integer $size 编码长度
     * @param string $prefix 编码前缀
     * @return mixed|string
     */
    public static function uniqidDate(int $size = 16, string $prefix = '')
    {
        if ($size < 14) {
            $size = 14;
        }
        $code = $prefix . date('Ymd') . (date('H') + date('i')) . date('s');
        while (strlen($code) < $size) {
            $code .= rand(0, 9);
        }
        return $code;
    }

    /**
     * 生成数字编码
     * @param integer $size 编码长度
     * @param string $prefix 编码前缀
     * @return mixed|string
     */
    public static function uniqidNumber(int $size = 12, string $prefix = '')
    {
        $time = strval(time());
        if ($size < 10) {
            $size = 10;
        }
        $code = $prefix . (intval($time[0]) + intval($time[1])) . substr($time, 2) . rand(0, 9);
        while (strlen($code) < $size) {
            $code .= rand(0, 9);
        }
        return $code;
    }

    /**
     * 文本转码
     * @param string $text 文本内容
     * @param string $target 目标编码
     * @return mixed|string
     */
    public static function text2utf8(string $text, string $target = 'UTF-8')
    {
        [$first2, $first4] = [substr($text, 0, 2), substr($text, 0, 4)];
        if ($first4 === chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF)) {
            $ft = 'UTF-32BE';
        } elseif ($first4 === chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00)) {
            $ft = 'UTF-32LE';
        } elseif ($first2 === chr(0xFE) . chr(0xFF)) {
            $ft = 'UTF-16BE';
        } elseif ($first2 === chr(0xFF) . chr(0xFE)) {
            $ft = 'UTF-16LE';
        }
        $result = mb_convert_encoding($text, $target, $ft ?? mb_detect_encoding($text));
        return $result;
    }

    /**
     * 数据解密处理
     * @param mixed $data 加密数据
     * @param string $skey 安全密钥
     * @return mixed|string
     */
    public static function encrypt($data, string $skey)
    {
        $iv = self::random(16, 3);
        $value = openssl_encrypt(serialize($data), 'AES-256-CBC', $skey, 0, $iv);
        $result = self::enSafe64(json_encode(['iv' => $iv, 'value' => $value]));
        return $result;
    }

    /**
     * 数据加密处理
     * @param string $data 解密数据
     * @param string $skey 安全密钥
     * @return mixed|mixed
     */
    public static function decrypt(string $data, string $skey)
    {
        $attr = json_decode(self::deSafe64($data), true);
        $result = unserialize(openssl_decrypt($attr['value'], 'AES-256-CBC', $skey, 0, $attr['iv']));
        return $result;
    }

    /**
     * Base64Url 安全编码
     * @param string $text 待加密文本
     * @return mixed|string
     */
    public static function enSafe64(string $text)
    {
        $result = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        return $result;
    }

    /**
     * Base64Url 安全解码
     * @param string $text 待解密文本
     * @return mixed|string
     */
    public static function deSafe64(string $text)
    {
        $result = base64_decode(str_pad(strtr($text, '-_', '+/'), strlen($text) % 4, '='));
        return $result;
    }

    /**
     * 压缩数据对象
     * @param mixed $data
     * @return mixed|string
     */
    public static function enzip($data)
    {
        $result = self::enSafe64(gzcompress(serialize($data)));
        return $result;
    }

    /**
     * 解压数据对象
     * @param string $string
     * @return mixed|mixed
     */
    public static function dezip(string $string)
    {
        $result = unserialize(gzuncompress(self::deSafe64($string)));
        return $result;
    }
}
