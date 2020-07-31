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

class Attachments extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "sort"=>"integer",
        "time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        $list = array_map(function ($res){
            $res["size"] = Tool::convert($res["size"]);
            $res["create_time"] = date("Y-m-d H:i:s",$res["time"]);
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setModuleAttr($value){
        return strip_tags(trim($value));
    }

    public function setMethodAttr($value){
        return strip_tags(trim($value));
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setPathAttr($value){
        return strip_tags(trim($value));
    }

    public function setSuffixAttr($value){
        return strip_tags(trim($value));
    }

    public function setSizeAttr($value){
        return strip_tags(trim($value));
    }

    public function setOptionsAttr($value){
        return strip_tags(trim($value));
    }

}