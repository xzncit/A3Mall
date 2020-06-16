<?php
namespace mall\library\wechat\chat\lib;

class Utils {

    public static function createRandString($length = 32, $str = ""){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

}