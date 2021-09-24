<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\users;

use app\common\model\base\A3Mall;

class Group extends A3Mall{

    protected $name = "users_group";

    protected $type = [
        "id"=>"integer",
        "discount"=>"float",
        "minexp"=>"integer",
        "maxexp"=>"integer"
    ];

    public function getList($condition=[],$size=10){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

}