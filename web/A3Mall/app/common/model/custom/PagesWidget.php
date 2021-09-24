<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\custom;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class PagesWidget extends A3Mall {

    protected $type = [
        "id"=>"integer",
    ];

    public function getWidgetData($condition=[]){
        $array = [
            "setting"=>[],
            "media"=>[],
            "shop"=>[],
            "utils"=>[]
        ];

        $row = PagesRelation::where($condition)->find();
        if(!empty($row)){
            $list = $this->where("id","in",$row["relation_id"])->select()->toArray();
        }else{
            $list = $this->select()->toArray();
        }
        foreach($list as $k=>$v){
            $v['value'] = json_decode($v['value'],true);
            $array[$v['cat']][] = $v;
        }

        foreach($array as $k=>$v){
            $array[$k] = json_encode($v,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
        }

        return $array;
    }

    public function setCatAttr($value){
        return strip_tags(trim($value));
    }

    public function setIconAttr($value){
        return strip_tags(trim($value));
    }

    public function setTypeAttr($value){
        return strip_tags(trim($value));
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setValueAttr($value){
        return Tool::editor($value);
    }

}