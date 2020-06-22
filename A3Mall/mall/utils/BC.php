<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\utils;

class BC {

    /**
     * 加法
     * @param $left 被加数
     * @param $right 总数
     * @param int $scale 保留几位小数
     * @return string
     */
    public static function add($left, $right, $scale=2){
        return bcadd($left,$right,$scale);
    }

    /**
     * 减法
     * @param $left 被减数
     * @param $right 总数
     * @param int $scale 保留几位小数
     * @return string
     */
    public static function sub($left, $right, $scale=2){
        return bcsub($left,$right,$scale);
    }

    /**
     * 乘法
     * @param $left 被乘数
     * @param $right 总数
     * @param int $scale 保留几位小数
     * @return string
     */
    public static function mul($left, $right, $scale=2){
        return bcmul($left,$right,$scale);
    }

    /**
     * 相除 左操作数除以右操作数
     * @param $dividend
     * @param $divisor
     * @param int $scale
     * @return string|null
     */
    public static function div($dividend, $divisor, $scale = 2){
        return bcdiv($dividend, $divisor, $scale);
    }

    // 比较
    public static function comp($left, $right, $scale=2){
        return bccomp($left,$right,$scale);
    }

}