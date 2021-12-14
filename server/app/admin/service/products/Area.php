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
use app\common\models\Area as AreaModel;

class Area extends Service {

    /**
     * 列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = AreaModel::where("pid",$data["pid"]??0)->count();
        $list = array_map(function ($res){
            $res['count'] = AreaModel::where(['pid' => $res['id']])->count();
            return $res;
        },AreaModel::where("pid",$data["pid"]??0)->page($data["page"]??1,$data["limit"]??10)->order("id","asc")->select()->toArray());

        return [ "count"=>$count,"data"=>$list ];
    }

    /**
     * 详情
     * @param $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($params){
        return [
            "data"=>AreaModel::where("id",$params["id"])->find()??[],
            "pid"=>$params["pid"]??0
        ];
    }

    /**
     * 保存数据
     * @param $data
     * @return AreaModel|bool|\think\Model
     */
    public static function save($data){
        if(AreaModel::where("id",$data["id"])->count()){
            return AreaModel::where("id",$data["id"])->save($data);
        }else{
            return AreaModel::create($data);
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        if(AreaModel::where('pid',$id)->count()){
            throw new \Exception("该地区下有子区域，请先删除！",0);
        }

        return AreaModel::where($id)->delete();
    }

    /**
     * 更新字段值
     * @return AreaModel
     */
    public static function setFields(){
        $data = self::getFields();
        return AreaModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

    /**
     * 获取省级地区列表
     * @param $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getArea($id){
        $result = AreaModel::where(['pid'=>$id])->select()->toArray();
        $string = '<option value="">请选择</option>';
        foreach($result as $val){
            $string .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
        }

        return $string;
    }

    /**
     * 获取所有地址信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getDistribution(){
        $result = AreaModel::where(['level'=>1])->order("id","ASC")->select()->toArray();
        foreach ($result as $key => $val) {
            $result[$key] = $val;
            $result[$key]['children'] = AreaModel::where(['pid' => $val["id"]])->select()->toArray();
        }

        return [ "data"=>$result ];
    }

}