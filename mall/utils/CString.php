<?php
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

        return $newstr;
    }

    /*
     * 删除多余空白字符
     */
    public static function removeEmpty($str) {
        $string = str_replace(["&nbsp;", "　"], [' ', ' '], $str);
        return trim(preg_replace("/[\r\n\t ]{1,}/", '', $string));
    }
}