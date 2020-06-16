<?php
namespace mall\utils;

class Data {

    public static function checkTree($result, $data) {
        $tree = self::getTree($result, $data["pid"]);

        $flag = true;
        foreach ($tree as $v) {
            if ($v['pid'] == $data["id"]) {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

    public static function getTree($data,$id=0) {
        $tree = [];
        while ($id > 0) {
            foreach ($data as $v) {
                if ($v['id'] == $id) {
                    $tree[] = $v;
                    $id = $v['pid'];
                    break;
                }
            }
        }

        return $tree;
    }

    public static function familyProcess($data,$res=[],$pid='0',$level=1){
        foreach($data as $item){
            if($item['pid'] == $pid){
                $res[$item['id']] = $item;
                $res[$item['id']]['level'] = $item['pid'] == 0 ? '' : str_repeat('&nbsp;&nbsp;', $level*4) . ' ├ ';
                $res[$item['id']]['children'] = self::familyProcess($data,array(),$item['id'],$level+1);
            }
        }
        return $res;
    }

    public static function analysisTree($data,$res=[]){
        foreach($data as $item){
            $value = $item;
            unset($value["children"]);
            $res[] = $value;
            if(!empty($item["children"])){
                $res = array_merge($res,self::analysisTree($item["children"],[]));
            }
        }

        return $res;
    }

}