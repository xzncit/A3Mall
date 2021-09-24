<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;


class Area extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "level"=>"integer",
        "sort"=>"integer",
        "is_lock"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','asc')->paginate($size);

        $list = array_map(function ($res){
            $res['count'] = $this->where(['pid' => $res['id']])->count();
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setZipAttr($value){
        return strip_tags(trim($value));
    }

    public function setLngAttr($value){
        return strip_tags(trim($value));
    }

    public function setLatAttr($value){
        return strip_tags(trim($value));
    }
}