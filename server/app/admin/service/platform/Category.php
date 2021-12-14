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
use app\common\models\Category as CategoryModel;
use app\common\models\goods\Goods;
use mall\utils\Data;

class Category extends Service {

    /**
     * 获取分类树结构
     * @param array $condition
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTree($condition=[]){
        $result = CategoryModel::where($condition)->select()->toArray();
        return Data::analysisTree(Data::familyProcess($result));
    }

    /**
     * 获取分类列表
     * @param $data
     * @param array $condition
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$condition=[]){
        $count = CategoryModel::where($condition)->count();
        $result = CategoryModel::where($condition)->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray();

        $list = [];
        foreach($result as $key=>$value){
            $list[] = $value;
            $children = self::getChildren($value["id"],null);
            $arr = Data::analysisTree(Data::familyProcess($children,[],$value["id"]));
            array_splice($list, count($list), 0, $arr);
        }

        foreach($list as $k=>$item){
            $list[$k]['title'] = (empty($item['level']) ? '' : $item['level']) . $item["title"];
        }

        return ["count"=>$count, "data"=>$list];
    }

    /**
     * 获取分类信息
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id,$condition=[]){
        return [
            "data"=>CategoryModel::where(["id"=>$id])->find(),
            "cat"=>self::getTree($condition)
        ];
    }

    /**
     * 保存
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function save($data=[]){
        if(CategoryModel::where("id",$data["id"])->count()){
            if(!self::checkTree(CategoryModel::where(["module"=>$data["module"]])->select()->toArray(),$data)){
                throw new \Exception("{$data['title']} 是 ID {$data['pid']} 的父栏目,不能修改！", 0);
            }

            CategoryModel::where("id",$data["id"])->save($data);
        }else{
            CategoryModel::create($data);
        }

        return true;
    }

    /**
     * 删除文章分类
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function deleteArchives($id){
        // 检测是否有子分类
        if(CategoryModel::where("pid",$id)->count() > 0){
            throw new \Exception("该分类下有子分类，请先删除子分类。",0);
        }

        // 检测是否有文章
        if(CategoryModel::where("pid",$id)->count() > 0){
            throw new \Exception("该分类下有文章，请先删除文章。",0);
        }

        $row = CategoryModel::where("id",$id)->find();
        if(empty($row)){
            throw new \Exception("您要删除的内容不存在",0);
        }else if($row["is_lock"] == 1){
            throw new \Exception("该内容您无权删除！",0);
        }

        CategoryModel::where("id",$id)->delete();
        return true;
    }

    /**
     * 删除商品分类
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function deleteGoods($id){
        // 检测是否有子分类
        if(CategoryModel::where("pid",$id)->count() > 0){
            throw new \Exception("该分类下有子分类，请先删除子分类。",0);
        }

        // 检测是否有商品
        if(Goods::where("cat_id",$id)->count() > 0){
            return Response::returnArray("该分类下有商品，请先删除商品。",0);
        }

        $row = CategoryModel::where("id",$id)->find();
        if(empty($row)){
            throw new \Exception("您要删除的内容不存在",0);
        }else if($row["is_lock"] == 1){
            throw new \Exception("该内容您无权删除！",0);
        }

        CategoryModel::where("id",$id)->delete();
        return true;
    }

    /**
     * 更新字段值
     * @return CategoryModel
     */
    public static function setFields(){
        $data = self::getFields();
        return CategoryModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

    /**
     * 查找下一级分类
     * @param $id
     * @param string $field
     * @param array $res
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getChildren($id,$field='id',$res=[]){
        $list = CategoryModel::where(["pid"=>$id,"status"=>0])->select()->toArray();
        foreach($list as $key=>$value){
            if(!empty($field) && isset($value[$field])){
                $res[] = $value[$field];
                $res = array_merge($res,self::getChildren($value['id'],$field));
            }else{
                $res[] = $value;
                $res = array_merge($res,self::getChildren($value['id'],$field));
            }
        }

        return $res;
    }

    /**
     * 查找上一级分类
     * @param $id
     * @param array $res
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function get_parent($id,$res=[]){
        $row = CategoryModel::where("id",$id)->find();
        if(empty($row)){
            return $res;
        }

        $res[] = $row;
        if($row["pid"] != 0){
            $res[] = array_merge($res,self::get_parent($res["pid"]));
        }

        return $res;
    }

    public static function getTreeData($data,$id=0) {
        $tree = [];
        while ($id > 0) {
            foreach ($data as $v) {
                if ($v['id'] == $id) {
                    $tree[] = $v;
                    $id = $v['pid'];
                    break;
                }
            }
        }

        return $tree;
    }

    public static function checkTree($result, $data) {
        $tree = self::getTreeData($result, $data["pid"]);

        $flag = true;
        foreach ($tree as $v) {
            if ($v['pid'] == $data["id"]) {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

}