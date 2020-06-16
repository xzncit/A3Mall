<?php
namespace mall\utils;

class Date {

    public static function format($time='',$timestamp="Y-m-d H:i:s",$default="N/A"){
        if(empty($time)) {
            return $default;
        }

        return date($timestamp,$time);
    }

}