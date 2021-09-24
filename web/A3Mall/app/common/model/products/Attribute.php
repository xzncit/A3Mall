<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\products;

use app\common\model\base\A3Mall;


class Attribute extends A3Mall {

    protected $name = "products_attribute";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        $list = [];
        foreach($data->items() as $key=>$value){
            $list[] = $value;
            $children = $this->get_children($value["id"],null);
            $arr = $this->analysis_tree($this->family_process($children,[],$value["id"]));
            array_splice($list, count($list), 0, $arr);
        }

        foreach($list as $key=>$item){
            $list[$key]['name'] = (empty($item['level']) ? '' : $item['level']) . $item["name"];
            $list[$key]['count'] = $this->where(["pid"=>$item["id"]])->count();
        }

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function get_children($id,$field='id',$res=[]){
        $list = $this->where(["pid"=>$id,"status"=>0])->select()->toArray();
        foreach($list as $key=>$value){
            if(!empty($field) && isset($value[$field])){
                $res[] = $value[$field];
                $res = array_merge($res,$this->get_children($value['id'],$field));
            }else{
                $res[] = $value;
                $res = array_merge($res,$this->get_children($value['id'],$field));
            }
        }

        return $res;
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}