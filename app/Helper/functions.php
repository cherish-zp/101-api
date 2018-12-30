<?php


/**
 * 生成用户邀请码
 * @param $length
 * @return string
 */
function createInviteCode($length)
{
    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{mt_rand(0, 35)};
    }
    return $key;
}