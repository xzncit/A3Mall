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

class Luckdraw extends A3Mall{

    protected $name = "users_luckdraw";

    public function getList($condition=[],$size=10){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order("id","DESC")->paginate($size);

        $list = array_map(function ($res){
            $array = json_decode($res["result"],true);
            $username = getUserName(["user_id"=>$res["user_id"]]);
            $intro = "会员：" . $username . "抽中 " . $array["name"] . " 概率：" . $array["chance"];
            return [
                "intro"=>$intro,
                "time"=>$res["create_time"]
            ];
        },$data->items());
        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

}