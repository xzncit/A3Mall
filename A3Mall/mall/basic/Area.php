<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use think\facade\Db;

class Area {

    public static function get_area($data,$join=''){
        $arr = [];
        foreach ($data as $item) {
            $arr[] = Db::name('area')->where(['id' => $item])->value("name");
        }

        if(!empty($join)){
            return implode($join, $arr);
        }

        return $arr;
    }

}