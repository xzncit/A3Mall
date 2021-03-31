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

class Version extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->paginate($size);

        $result = array_map(function ($res){
            $res["create_time"] = date("Y-m-d H:i:s",$res["create_time"]);
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$result
        ];
    }

    public function setApiAttr($value){
        return strip_tags(trim($value));
    }

    public function setApiUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setAndroidUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setIosUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

}