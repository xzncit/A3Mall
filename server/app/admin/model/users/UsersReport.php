<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\users;

use app\common\models\users\Users;
use app\common\models\goods\Goods;
use app\common\models\users\UsersReport as UsersReportModel;

class UsersReport extends UsersReportModel{

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function users(){
        return $this->hasOne(Users::class,"id","user_id")->joinType("LEFT")->bind(["username"]);
    }

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function goods(){
        return $this->hasOne(Goods::class,"id","goods_id")->joinType("LEFT")->bind(["goods_name"=>"title"]);
    }

}