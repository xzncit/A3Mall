<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

class Navigation extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "sort"=>"integer",
        "target"=>"integer",
        "status"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setUrlAttr($value){
        return strip_tags(trim($value));
    }
}