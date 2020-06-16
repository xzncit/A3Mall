<?php
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