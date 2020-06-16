<?php
namespace mall\basic;

class Goods {

    public static function goods_number($number = '', $date = 'YmdHis') {
        $arr = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $arr[0]), 0, 2) . rand(10, 99);
        return $number . date($date) . $usec;
    }

}