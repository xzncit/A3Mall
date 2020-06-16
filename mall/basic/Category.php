<?php
namespace mall\basic;

use think\facade\Db;

class Category {

    public static function getCategoryChildren($id,$table="category",$res=[]){
        $row = Db::name($table)->where(array("pid"=>$id))->select()->toArray();
        foreach($row as $val){
            $res[] = $val;
            $res = array_merge($res,self::getCategoryChildren($val["id"],$table));
        }

        return $res;
    }

}