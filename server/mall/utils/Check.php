<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\utils;

class Check {

    /**
     * 验证某个字段的值是否为有效的手机
     * @param string $string
     * @return bool
     * */
    public static function mobile($string) {
        return preg_match('/^1[3-9]\d{9}$/', $string);
    }

    /**
     * 是否为合法的用户名
     * @param $string
     * @param int $start
     * @param int $end
     * @return int
     */
    public static function username($string,$start=4,$end=12){
        $pattern = '/[0-9a-zA-Z_]{'.$start.','.$end.'}$/';
        return (int)preg_match($pattern, $string);
    }

    /**
     * 验证密码
     * @param $string
     * @param int $min
     * @param int $max
     * @return false|int
     */
    public static function password($string,$min=6,$max=18){
        $pattern = '/[a-zA-Z0-9_\~\!\@\#\$\%\^\&\*\(\)\-\_\=\+\[\]\{\}\;\:\'\"\\\|\,\<\>\.\/\?]{'.$min.','.$max.'}/';
        return preg_match($pattern, $string);
    }

    /**
     * 检查字符长度
     * @param string $content
     * @return false|int
     */
    public static function strlen($content=""){
        return mb_strlen($content,"UTF8");
    }

    /**
     * 验证某个字段的值是否为纯字母
     * @param $string
     * @return false|int
     */
    public static function alpha($string) {
        return preg_match('/^[A-Za-z]+$/', $string);
    }

    /**
     * 验证某个字段的值是否为字母和数字
     * @param $string
     * @return false|int
     */
    public static function alphaNum($string) {
        return preg_match('/^[A-Za-z0-9]+$/', $string);
    }

    /**
     * 验证某个字段的值是否为字母和数字，下划线_及破折号-
     * @param $string
     * @return false|int
     */
    public static function alphaDash($string) {
        return preg_match('/^[A-Za-z0-9\-\_]+$/', $string);
    }

    /**
     * 验证某个字段的值只能是汉字
     * @param $string
     * @return false|int
     */
    public static function chs($string) {
        return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $string);
    }

    /**
     * 验证某个字段的值只能是汉字、字母
     * @param $string
     * @return false|int
     */
    public static function chsAlpha($string) {
        return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u', $string);
    }

    /**
     * 验证某个字段的值只能是汉字、字母和数字
     * @param $string
     * @return false|int
     */
    public static function chsAlphaNum($string) {
        return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u', $string);
    }

    /**
     * 验证某个字段的值只能是汉字、字母、数字和下划线_及破折号-
     * @param $string
     * @return false|int
     */
    public static function chsDash($string) {
        return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-\s]+$/u', $string);
    }

    /**
     * 验证某个字段的值是否为有效的身份证格式
     * @param $string
     * @return false|int
     */
    public static function idCard($string) {
        return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $string);
    }

    /**
     * 验证某个字段的值是否为有效的邮政编码
     * @param $string
     * @return false|int
     */
    public static function zip($string) {
        return preg_match('/\d{6}/', $string);
    }

    /**
     * 验证某个字段的值是否为email地址
     * @param string $value
     * @return bool
     */
    public static function email($value=""){
        return false !== filter_var($value,FILTER_VALIDATE_EMAIL);
    }

    /**
     * 验证某个字段的值是否为有效的IP地址
     * @param string $value
     * @param int $rule [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6],
     * @return mixed
     */
    public static function ip($value="",$rule=FILTER_VALIDATE_IP){
        return false !== filter_var($value,$rule);
    }

    /**
     * 验证某个字段的值是否为整数
     * @param string $value
     * @return bool
     */
    public static function integer($value=""){
        return false !== filter_var($value,FILTER_VALIDATE_INT);
    }

    /**
     * 验证某个字段的值是否为有效的URL地址
     * @param string $value
     * @return bool
     */
    public static function url($value=""){
        return false !== filter_var($value,FILTER_VALIDATE_URL);
    }

    /**
     * 验证某个字段的值是否为有效的MAC地址
     * @param string $value
     * @return bool
     */
    public static function macAddr($value=""){
        return false !== filter_var($value,FILTER_VALIDATE_MAC);
    }

    /**
     * 验证某个字段的值是否为浮点数字
     * @param string $value
     * @return bool
     */
    public static function float($value=""){
        return false !== filter_var($value,FILTER_VALIDATE_FLOAT);
    }

}