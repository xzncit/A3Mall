<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\order;

use app\admin\service\Service;
use app\admin\model\order\OrderCollection as OrderCollectionModel;
use app\common\models\system\Users as UsersModel;

class Collection extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $condition = [];
        $key = $data["key"]??[];
        $arr = ["order.order_no","users.username"];
        if((isset($key["type"]) && isset($arr[$key["type"]])) && !empty($key["title"])){
            $condition[] = [$arr[$key["type"]],"like",'%'.$key["title"].'%'];
        }

        $count = OrderCollectionModel::withJoin(["order","users","payment"])->where($condition)->count();
        $result = array_map(function ($res){
            $res["username"] = getUserName($res);
            return $res;
        },OrderCollectionModel::withJoin(["order","users","payment"])->where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 详情
     * @param $id
     * @return array
     * @throws \Exception
     */
    public static function detail($id){
        $data = OrderCollectionModel::alias("c")
            ->field('o.order_no,c.user_id,p.name as pname,o.create_time,o.pay_type,u.username,c.amount,o.pay_time,c.admin_id,c.note')
            ->join("order o","c.order_id=o.id","LEFT")
            ->join("users u","u.id=c.user_id","LEFT")
            ->join("payment p","c.payment_id=p.id","LEFT")->where('c.id',$id)->find();

        if(empty($data)){
            throw new \Exception("您要查找的内容不存在！");
        }

        $data["username"]    = getUserName($data);
        $data["pay_time"]    = date("Y-m-d H:i:s",$data['pay_time']);

        if($data["admin_id"] == "-1"){
            $data['admin_name'] = 'system';
        }else{
            $data['admin_name'] = UsersModel::where(["id"=>$data["admin_id"]])->value("username");
        }

        return [ "data"=>$data ];
    }

}