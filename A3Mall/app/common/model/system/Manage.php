<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class Manage extends A3Mall{

    protected $name = "system_manage";

    protected $type = [
        "id"=>"integer",
        "status"=>"integer",
        "lock"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','DESC')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setPurviewAttr($value){
        return json_encode($value,JSON_UNESCAPED_UNICODE);
    }

    public function getPurviewAttr($value){
        if($value == '-1'){
            return $value;
        }

        return json_decode($value,true);
    }
}