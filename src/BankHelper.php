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
     * 银行号归属地
     * @param string $card_number 银行卡号
     * @return mixed|array
     */
    public static function getData($card_number = '')
    {
        $result = [];
        if (!empty($card_number)) {
            $obj = new Bank;
            $obj->setCartId($card_number);
            $dt = $obj->getBankCardInfo();
            $result = $dt;
        }
        return $result;
    }
}
