<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\wechat;


use app\admin\service\Service;
use app\common\models\wechat\SubscribeMessage as SubscribeMessageModel;

class Subscribe extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        $count = SubscribeMessageModel::where($condition)->count();
        $result = SubscribeMessageModel::where($condition)->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=>$result ];
    }

    /**
     * 详情
     * @param $id
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        $row = SubscribeMessageModel::where("id",$id)->find();
        if(!empty($row)){
            $row["attr"] = json_decode($row["content"],true);
        }

        return ["data"=>$row??[]];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     */
    public static function save($data){
        $data["content"] = !empty($data["attr"]) ? json_encode($data["attr"],JSON_UNESCAPED_UNICODE) : "";
        if(SubscribeMessageModel::where("id",$data["id"])->count()){
            SubscribeMessageModel::where("id",$data["id"])->save($data);
        }else{
            SubscribeMessageModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return SubscribeMessageModel::where("id",$id)->delete();
    }

}