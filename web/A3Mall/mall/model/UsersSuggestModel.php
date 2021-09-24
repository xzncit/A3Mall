<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\model;

use mall\model\Model;
use think\facade\Db;

class UsersSuggestModel extends Model {

    protected $name = "users_suggest";

    public function getList($condition=[],$size=10,$page=1){
        $count = Db::name("users_suggest")->alias("r")
            ->join("users u","r.user_id=u.id","LEFT")
            ->where($condition)
            ->count();

        $result = Db::name("users_suggest")->alias("r")
            ->field("r.*,u.username")
            ->join("users u","r.user_id=u.id","LEFT")
            ->where($condition)
            ->paginate();

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