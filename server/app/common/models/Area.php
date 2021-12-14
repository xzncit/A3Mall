<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models;

class Area extends Model {

    protected $name = "area";

    public static function getArea($data,$join=''){
        $arr = [];
        foreach ($data as $item){
            $arr[] = self::where(['id' => $item])->value("name");
        }

        if(!empty($join)){
            return implode($join, $arr);
        }

        return $arr;
    }

}