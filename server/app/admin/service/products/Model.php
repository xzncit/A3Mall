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
use app\common\models\goods\GoodsModel;
use app\common\models\goods\ProductsModel;
use app\common\models\goods\ProductsModelData;

/**
 * 商品属性服务类
 * Class Model
 * @package app\admin\service\products
 */
class Model extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = ProductsModel::count();
        $list = array_map(function ($res){
            $res['count'] = ProductsModelData::where(['pid' => $res['id']])->count();
            return $res;
        },ProductsModel::page($data["page"]??1,$data["limit"]??10)->order("id","asc")->select()->toArray());

        return [ "count"=>$count,"data"=>$list ];
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
        $row = ProductsModel::where("id",$id)->find();
        if(!empty($row)){
            $row["attr"] = ProductsModelData::where("")->where(["pid"=>$row["id"]])->order("sort","ASC")->select()->toArray();
        }

        return [ "data"=>$row ];
    }

    /**
     * 保存数据
     * @param array $data
     * @return bool
     */
    public static function save($data=[]){
        if(ProductsModel::where("id",$data["id"])->count()){
            ProductsModel::where("id",$data["id"])->save($data);
        }else{
            unset($data["id"]);
            $data["id"] = ProductsModel::create($data)->id;
        }

        if(empty($data["attr"]["name"])){
            return true;
        }

        $i = 0; $arr = [];
        foreach($data["attr"]["name"] as $key=>$val){
            $attr = [
                "pid"=>$data["id"],
                "name"=>$val,
                "value"=>$data["attr"]["value"][$key],
                "type"=>$data["attr"]["type"][$key],
                "sort"=>$i
            ];

            $model_id = intval($data["attr"]["id"][$key]);
            if(ProductsModelData::where("id",$model_id)->count()){
                $arr[] = $model_id;
                ProductsModelData::where("id",$model_id)->save($attr);
            }else{
                $arr[] = ProductsModelData::create($attr)->id;
            }

            $i++;
        }

        if(!empty($arr)){
            ProductsModelData::where('pid',$data["id"])->where("id","not in",$arr)->delete();
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    public static function delete($id){
        if(ProductsModel::where("id",$id)->delete()){
            return ProductsModelData::where('pid',$id)->delete();
        }

        return false;
    }

    /**
     * 获取商品属性数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getModel($data){
        $id = $data["id"]??0;
        $goods_id = $data["goods_id"]??0;

        $result = ProductsModelData::where(['pid'=>$id])->order("sort","ASC")->select()->toArray();
        if(empty($result)){
            throw new \Exception("您要查找的模型内容不存在！",0);
        }

        $goods_attr = [];
        $module = GoodsModel::where(["model_id"=>$id,"goods_id"=>$goods_id])->order("sort","ASC")->select()->toArray();
        if ($module) {
            foreach ($module as $item) {
                $goods_attr[$item['attribute_id']] = $item['attribute_value'];
            }
        }

        return [ "goods_attr"=>$goods_attr, "result"=>$result ];
    }

}