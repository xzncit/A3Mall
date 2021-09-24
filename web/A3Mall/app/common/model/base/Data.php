<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;


class Data extends A3Mall {

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id',"desc")->paginate($size);

        $list = [];
        foreach ($data->items() as $key=>$value){
            $value["time"] = $value->create_time;
            $list[] = $value;
        }

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setSignAttr($value){
        return strip_tags(trim($value));
    }

    public function setDescriptionAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}