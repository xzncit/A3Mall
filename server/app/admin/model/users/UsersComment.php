<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\users;

use app\common\models\users\UsersComment as UsersCommentModel;
use app\common\models\users\Users;
use app\common\models\goods\Goods;

class UsersComment extends UsersCommentModel {

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

    public function getCommentTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getReplyTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}