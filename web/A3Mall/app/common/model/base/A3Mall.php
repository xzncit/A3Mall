<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model\base;

use think\Model;

class A3Mall extends Model {

    public function family_process($data,$res=[],$pid='0',$level=1){
        foreach($data as $item){
            if($item['pid'] == $pid){
                $res[$item['id']] = $item;
                $res[$item['id']]['level'] = $item['pid'] == 0 ? '' : str_repeat('&nbsp;&nbsp;', $level*4) . ' ├ ';
                $res[$item['id']]['children'] = $this->family_process($data,array(),$item['id'],$level+1);
            }
        }
        return $res;
    }

    public function analysis_tree($data,$res=[]){
        foreach($data as $item){
            $value = $item;
            unset($value["children"]);
            $res[] = $value;
            if(!empty($item["children"])){
                $res = array_merge($res,$this->analysis_tree($item["children"],[]));
            }
        }

        return $res;
    }

    public function get_tree($data,$id=0) {
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

    public function check_tree($result, $data) {
        $tree = $this->get_tree($result, $data["pid"]);
        $flag = true;
        foreach ($tree as $v) {
            if ($v['pid'] == $data["id"]) {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

}