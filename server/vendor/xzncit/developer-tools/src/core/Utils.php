<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core;


class Utils {

    /**
     * 生成随机字符串
     * @param int $length
     * @param string $str
     * @return mixed|string
     */
    public static function getRandString($length = 32, $str = ""){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    /**
     * 数组KEY全部转小写
     * @param $data
     * @return array|mixed
     */
    public static function lowerCase($data){
        if(!is_array($data)) return strtolower($data);
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $key => $vo) {
            if (is_array($vo)) {
                $data[$key] = self::lowerCase($vo);
            }
        }

        return $data;
    }
}