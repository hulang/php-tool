<?php

declare(strict_types=1);

namespace hulang\tool;

/**
 * 银行号归属地查询
 * @see \hulang\tool\BankHelper
 * @package hulang\tool\BankHelper
 * @mixin \hulang\tool\BankHelper
 * @method static mixed|array getData($card_number = '') 银行号归属地
 */
class BankHelper
{
    /**
     * 根据银行卡号获取银行相关信息
     * 
     * 本函数旨在通过银行卡号查询并返回该银行卡所属的银行信息
     * 使用者可以传入一个银行卡号,函数将尝试解析该号码并返回相关银行的信息
     * 如果银行卡号为空或无法识别,则返回一个空数组
     * 
     * @param string $card_number 银行卡号
     * @return mixed|array 返回包含银行信息的数组,如果无法识别则返回空数组
     */
    public static function getData($card_number = '')
    {
        // 初始化结果数组,用于存储查询到的银行信息
        $result = [];
        // 检查银行卡号是否为空,非空时进行后续操作
        if (!empty($card_number)) {
            // 创建Bank类的实例,用于查询银行卡信息
            $obj = new Bank;
            // 设置银行卡号
            $obj->setCartId($card_number);
            // 调用方法获取银行卡信息
            $dt = $obj->getBankCardInfo();
            // 将获取的银行信息存储到结果数组中
            $result = $dt;
        }
        // 返回结果数组,可能为空
        return $result;
    }
}
