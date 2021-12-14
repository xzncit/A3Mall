<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\products;

use app\admin\service\Service;
use app\admin\model\order\OrderGroup as OrderGroupModel;

class GroupOrder extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @param array $condition
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        $count = OrderGroupModel::withJoin("users")->where($condition)->count();
        $result = array_map(function ($res){
            $res['people_count'] = OrderGroupModel::where("pid",$res["id"])->count()+1;
            $res["username"] = getUserName($res);
            return $res;
        },OrderGroupModel::withJoin("users")->where($condition)->order("order_group.id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        if(!$row = OrderGroupModel::where("id",$id)->find()){
            throw new \Exception("您查找的内容不存在！");
        }

        $row["username"] = getUserName($row);
        $row["create_time"] = date("Y-m-d H:i:s",$row["create_time"]);
        $row['url'] = createUrl('order.index/detail',['id'=>$row['order_id']]);

        $list = OrderGroupModel::withJoin("users")->where("pid",$id)->select()->toArray();
        foreach($list as $k=>$v){
            $list[$k]['url'] = createUrl('order.index/detail',['id'=>$v['order_id']]);
        }

        return [ "data"=>array_merge([$row],$list) ];
    }

}