<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\utils;

class CString {

    /**
     * 截取UTF-8编码下字符串的函数
     * @param   string      $str        被截取的字符串
     * @param   int         $length     截取的长度
     * @param   bool        $append     是否附加省略号
     * @return  string
     */
    public static function msubstr($str, $length = 0, $append = true) {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length);
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length);
        } else {
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return self::removeEmojiChar($newstr);
    }

    /*
     * 删除多余空白字符
     */
    public static function removeEmpty($str) {
        $string = str_replace(["&nbsp;", "　"], [' ', ' '], $str);
        return trim(preg_replace("/[\r\n\t ]{1,}/", '', $string));
    }

    public static function removeEmojiChar($str){
        $mbLen = mb_strlen($str);

        $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) {
            $mbSubstr = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($mbSubstr) >= 4) {
                continue;
            }
            $strArr[] = $mbSubstr;
        }

        return implode('', $strArr);
    }
}