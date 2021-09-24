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

class Rechange extends A3Mall {

    protected $name = "users_rechange";

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(['username'])->joinType("LEFT");
    }

    public function getList($condition,$size=10,$page=1){
        $count = $this->withJoin(["users"])->where($condition)->count();
        $result = $this->withJoin(["users"])->where($condition)->order('rechange.id','DESC')->paginate($size);

        $data = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result->items());

        return [
            "count"=>$count,
            "data"=>$data
        ];
    }

}