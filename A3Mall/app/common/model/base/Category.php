<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

use mall\utils\Tool;

class Category extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "status"=>"integer",
        "sort"=>"integer",
        "hits"=>"integer",
        "is_menu"=>"integer",
        "is_hot"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order("id DESC")->paginate($size);

        $list = [];
        foreach($data->items() as $key=>$value){
            $list[] = $value;
            $children = $this->get_children($value["id"],null);
            $arr = $this->analysis_tree($this->family_process($children,[],$value["id"]));
            array_splice($list, count($list), 0, $arr);
        }

        foreach($list as $k=>$item){
            $list[$k]['title'] = (empty($item['level']) ? '' : $item['level']) . $item["title"];
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

    public function get_parent($id,$res=[]){
        $row = $this->where("id",$id)->find();
        if(empty($row)){
            return $res;
        }

        $res[] = $row;
        if($row["pid"] != 0){
            $res[] = array_merge($res,$this->get_parent($res["pid"]));
        }

        return $res;
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhotoAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function setModuleAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}