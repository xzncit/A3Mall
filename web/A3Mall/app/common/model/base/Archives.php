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

class Archives extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "hits"=>"integer",
        "status"=>"integer",
        "sort"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer"
    ];

    public function profile(){
        return $this->hasOne(Category::class,'id','pid')
            ->joinType("LEFT")->bind([
                "cat_name"=>"title"
            ]);
    }

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin("profile")->where($condition)->count();
        $data = $this->withJoin("profile")->where($condition)->order('id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public static function aaaaa(){

    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhotoAttr($value){
        return strip_tags(trim($value));
    }

    public function setIntroAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function getPhotoAttr($value){
        return Tool::thumb($value,"small",true);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}