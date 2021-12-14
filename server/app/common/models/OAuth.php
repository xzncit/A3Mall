<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models;


class OAuth extends Model {

    protected $name = "oauth";

    /**
     * 获取配置
     * @param string $name
     * @param string $value
     * @return array|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSetting($name,$value=""){
        $row = self::where(["name"=>$name])->find();
        if(empty($row["config"])) return null;

        $array = json_decode($row["config"],true);
        if(empty($array)){
            return $array;
        }

        if(isset($array[$value])){
            return trim($array[$value]);
        }

        return null;
    }

    public function getConfigAttr($value){
        if(empty($value)) return "";
        return json_decode($value,true);
    }

}