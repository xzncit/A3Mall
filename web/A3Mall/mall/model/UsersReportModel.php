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

class UsersReportModel extends Model {

    protected $name = "users_report";

    public function getList($condition=[],$size=10,$page=1){
        $count = Db::name("users_report")->alias("r")
        ->join("goods g","r.goods_id=g.id","LEFT")
        ->join("users u","r.user_id=u.id","LEFT")
        ->where($condition)
        ->count();

        $result = Db::name("users_report")->alias("r")
            ->field("r.*,u.username,g.title as goods_name")
            ->join("goods g","r.goods_id=g.id","LEFT")
            ->join("users u","r.user_id=u.id","LEFT")
            ->where($condition)
            ->paginate();

        $data = array_map(function($res){
            $res["username"] = getUserName($res);
            return $res;
        },$result->items());

        return [
            "count"=>$count,
            "data"=>$data
        ];
    }

}