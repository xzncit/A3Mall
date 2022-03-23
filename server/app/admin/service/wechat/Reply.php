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
use app\admin\model\wechat\WechatKeys as WechatKeysModel;

class Reply extends Service {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = WechatKeysModel::withSearch(["keys"],[
            'keys'=>$data['key']["title"]??''
        ])->count();

        $result = WechatKeysModel::withSearch(["keys"],[
            'keys'=>$data['key']["title"]??''
        ])->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        return [ "count"=>$count, "data"=>$result ];
    }

    /**
     * 详情
     * @param array $condition
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($condition=[]){
        return [ "data"=>WechatKeysModel::where($condition)->find() ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function save($data){
        if($obj = WechatKeysModel::where(["id"=>$data["id"]])->find()){
            $k = WechatKeysModel::where(["keys"=>$data["keys"]])->find();
            if($k["id"] != $obj["id"]){
                throw new \Exception("关键字已存在！",0);
            }

            WechatKeysModel::where(["id"=>$data["id"]])->save($data);
        }else{
            if(WechatKeysModel::where(["keys"=>$data["keys"]])->count()){
                throw new \Exception("关键字已存在！",0);
            }

            WechatKeysModel::save($data);
        }

        return true;
    }

    /**
     * 保存单条数据
     * @param $data
     * @return bool
     */
    public static function saveKeys($data){
        if(WechatKeysModel::where(["keys"=>$data["keys"]])->count()){
            WechatKeysModel::where(["keys"=>$data["keys"]])->save($data);
        }else{
            unset($data["id"]);
            WechatKeysModel::create($data);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return WechatKeysModel::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return bool
     */
    public static function setFields(){
        $data = self::getFields();
        WechatKeysModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
        return true;
    }

}