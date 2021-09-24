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

class Comment extends A3Mall{

    protected $name = "users_comment";

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "user_id"=>"integer",
        "admin_id"=>"integer",
        "point"=>"integer",
        "describes"=>"integer",
        "service"=>"integer",
        "logistics"=>"integer",
        "status"=>"integer",
        "comment_time"=>"integer",
        "reply_time"=>"integer",
        "create_time"=>"integer",
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
        $data = $this->withJoin(['goods','users'])->where($condition)->order('comment.id','desc')->paginate($size);

        $data = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$data
        ];
    }

    public function setOrderNoAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentsAttr($value){
        return Tool::editor($value);
    }

    public function setReplyContentAttr($value){
        return Tool::editor($value);
    }

    public function getCommentTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getReplyTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}