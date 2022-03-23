<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use think\facade\Config;
use mall\basic\Users;
use app\common\exception\BaseException;
use app\common\models\promotion\PromotionBonus as BonusModel;
use app\common\models\users\UsersBonus as UsersBonusModel;

class Bonus extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [
            ["type","=",0],
            ["status","=",0],
            ["end_time",">",time()]
        ];

        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = BonusModel::where($condition)->count();
        $result = BonusModel::where($condition)->order("id","desc")->page($page,$size)->select()->toArray();

        $data = [];
        foreach($result as $key=>$value){
            $data[$key] = [
                "id"=>$value["id"],
                "name"=>$value["name"],
                "amount"=>number_format($value["amount"]),
                "price"=>$value["order_amount"],
                "end_time"=>date('Y-m-d',strtotime($value["end_time"]))
            ];

            if($value["giveout"] != 0 && ($value["used"] >= $value["giveout"])){
                $data[$key]['is_receive'] = 2;
            }else{
                $data[$key]['is_receive'] = UsersBonusModel::where("bonus_id",$value["id"])->where("user_id",Users::get("id"))->count();
            }
        }

        $array = [ "list"=>$data??[], "page"=>$page, "total"=>0, "size"=>$size ];
        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        return $array;
    }

    /**
     * 领劵
     * @param $id
     * @return int
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function receive($id){
        if(!$row=BonusModel::where([
            ["id","=",$id],
            ["type","=",0],
            ["status","=",0],
            ["end_time",">",time()],
        ])->find()){
            throw new BaseException("优惠劵已过期",0,2);
        }

        if($row["giveout"] != 0 && ($row["used"] >= $row["giveout"])){
            throw new BaseException("优惠劵已领完",0,1);
        }

        if(UsersBonusModel::where(["user_id"=>Users::get("id"),"bonus_id"=>$id])->count()){
            throw new BaseException("该优惠劵您已领取过了",0,1);
        }

        UsersBonusModel::create([ "user_id"=>Users::get("id"), "type"=>$row["type"], "bonus_id"=>$id, "create_time"=>time() ]);
        BonusModel::where("id",$id)->inc("used")->update();
        return 1;
    }

}