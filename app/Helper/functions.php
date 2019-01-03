<?php


/**
 * 生成用户邀请码
 *
 * @param $length
 *
 * @return string
 */
function createInviteCode($length)
{
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern[mt_rand(0, 35)];
    }

    return $key;
}

/**
 * @return string 获取订单号
 */
function getOrderTradeOn()
{
    return date('ymdhis').substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
