<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\models;


class Setting extends Model {

    protected $name = "setting";

    /**
     * 保存数据
     * @param $name
     * @param null $value
     * @return bool
     */
    public static function saveData($name,$value=null){
        if(is_array($name)){
            foreach($name as $key=>$item){
                $item = self::getFilterSettingData($item);
                if(self::where("name",$key)->count()){
                    self::where("name",$key)->save([ "value"=>$item ]);
                }else{
                    self::create([ "name"=>$key, "value"=>$item ]);
                }
            }

            return true;
        }

        $value = self::getFilterSettingData($value);
        if(self::where("name",$name)->count()){
            self::where("name",$name)->save([ "value"=>$value ]);
        }else{
            self::create([ "name"=>$name, "value"=>$value ]);
        }

        return true;
    }

    protected static function getFilterSettingData($data){
        $value = self::filterSpace($data);
        return is_array($value) ? json_encode($value,JSON_UNESCAPED_UNICODE) : $value;
    }

    /**
     * 过滤空格
     * @param $data
     * @return array|false|mixed|string
     */
    protected static function filterSpace($data){
        if(is_array($data)){
            foreach($data as $k=>$v){
                $data[$k] = is_array($v) ? self::filterSpace($v) : trim($v);
            }
        }else{
            $data = trim($data);
        }

        return $data;
    }

    /**
     * 该方法用于获取字符串配置内容
     * @param string|null $name
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getStringData($name){
        $row = self::where("name",$name)->find();
        return $row["value"];
    }

    /**
     * 该方法用于获取数组配置内容
     * @param $name
     * @return array|mixed|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getArrayData($name){
        $keys = explode(".",$name);
        $name = array_shift($keys);
        $row = self::where("name",$name)->find();
        $array = json_decode($row["value"],true);

        if(empty($array) || empty($keys)){
            return $array ?? null;
        }

        $string = $_string = null;
        for($i=0; $i<count($keys);$i++){
            if(empty($string)){
                $string = empty($array[$keys[$i]]) ? null : $array[$keys[$i]];
            }else{
                $_string = $string[$keys[$i]];
                $string = $_string;
            }
        }

        return $string;
    }

}