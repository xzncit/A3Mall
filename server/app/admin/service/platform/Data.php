<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\platform;

use app\admin\service\Service;
use app\common\models\Data as DataModel;
use app\common\models\DataItem as DataItemModel;

class Data extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = DataModel::count();
        $result = DataModel::page($data["page"]??1,$data["limit"]??10)->order('id','desc')->select()->toArray();
        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 获取详情数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        return [
            "data"=>DataModel::where(["id"=>$id])->find() ?? [],
            "marketing"=>DataItemModel::where('pid',$id)->order('sort','ASC')->select()->toArray() ?? []
        ];
    }

    /**
     * 保存数据
     * @param array $data
     * @return bool
     */
    public static function save($data=[]){
        if(DataModel::where("id",$data["id"])->count()){
            DataModel::where("id",$data["id"])->save($data);
        }else{
            unset($data["id"]);
            $data["id"] = DataModel::create($data)->id;
        }

        $marketing = $data['marketing']; $i=0;
        foreach($marketing['id'] as $key=>$value){
            $arr = [
                "pid"=>$data['id'],
                "name"=>!empty($marketing["name"][$key]) ? $marketing["name"][$key] : "",
                "url"=>!empty($marketing["url"][$key]) ? $marketing["url"][$key] : "",
                "photo"=>!empty($marketing["photo"][$key]) ? $marketing["photo"][$key] : "",
                "sort"=>$i,
                "target"=>!empty($marketing["target"][$key]) ? $marketing["target"][$key] : 0
            ];

            if(DataItemModel::where("id",$value)->count()){
                DataItemModel::where("id",$value)->save($arr);
            }else{
                DataItemModel::create($arr)->id;
            }

            $i++;
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        if(!DataModel::where("id",$id)->delete()){
            throw new \Exception("删除失败，请稍后重试！",0);
        }

        DataItemModel::where(["pid"=>$id])->delete();
        return true;
    }

}