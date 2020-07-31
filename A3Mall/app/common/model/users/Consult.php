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
use app\common\model\goods\Goods;
use mall\utils\Tool;

class Consult extends A3Mall{

    protected $name = "users_consult";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "admin_id"=>"integer",
        "user_id"=>"integer",
        "goods_id"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer",
        "reply_time"=>"integer"
    ];

    public function users(){
        return $this->hasOne(Users::class,'id','user_id')
            ->joinType("LEFT")->bind([
                "username"
            ]);
    }

    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id')
            ->joinType("LEFT")->bind([
                "goods_name"=>"title"
            ]);
    }

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin(['goods','users'])->where($condition)->count();
        $data = $this->withJoin(['goods','users'])->where($condition)->order('consult.id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getReplyTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}