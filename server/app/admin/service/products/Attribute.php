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
use mall\utils\Data;
use mall\utils\Tool;
use app\common\models\goods\Attribute as AttributeModel;
use app\common\models\goods\AttributeData as AttributeDataModel;
use app\common\models\goods\GoodsAttribute as GoodsAttributeModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;

class Attribute extends Service {

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
        $count = AttributeModel::where($condition)->count();
        $result = AttributeModel::where($condition)->page($data["page"]??1,$data["limit"]??10)->order("id","asc")->select()->toArray();

        $list = [];
        foreach($result as $key=>$value){
            $list[] = $value;
            $children = self::getChildren($value["id"],null);
            $arr = Data::analysisTree(Data::familyProcess($children,[],$value["id"]));
            array_splice($list, count($list), 0, $arr);
        }

        foreach($list as $key=>$item){
            $list[$key]['name'] = (empty($item['level']) ? '' : $item['level']) . $item["name"];
            $list[$key]['count'] = AttributeModel::where(["pid"=>$item["id"]])->count();
        }

        return [ "count"=>$count, "data"=>$list ];
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
        $row = AttributeModel::where("id",$id)->find();
        if(!empty($row)){
            $row["attr"] = AttributeDataModel::where(["pid"=>$row["id"]])->order("sort","ASC")->select()->toArray();
        }

        return [ "ch"=>AttributeModel::where(['pid'=>0])->select()->toArray(), "data"=>$row ];
    }

    /**
     * 保存数据
     * @param array $data
     * @return bool
     */
    public static function save($data=[]){
        if(AttributeModel::where("id",$data["id"])->count()){
            AttributeModel::where("id",$data["id"])->save($data);
        }else{
            unset($data["id"]);
            $data["id"] = AttributeModel::create($data)->id;
        }

        $i = 0; $arr = [];
        if(!empty($data["attr"]["name"])){
            foreach($data["attr"]["name"] as $key=>$val){
                $attr = [ "pid"=>$data["id"], "value"=>$val, "sort"=>$i ];
                $attr_id = intval($data["attr"]["id"][$key]);
                if(AttributeDataModel::where("id",$attr_id)->count()){
                    $arr[] = $attr_id;
                    AttributeDataModel::where("id",$attr_id)->save($attr);
                }else{
                    $arr[] = AttributeDataModel::create($attr)->id;
                }

                $i++;
            }
        }

        if(!empty($arr)){
            AttributeDataModel::where('pid',$data["id"])->where('id','not in',$arr)->delete();
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delete($id){
        $row = AttributeModel::where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        AttributeModel::where("id",$id)->delete();
        AttributeDataModel::where('pid',$id)->delete();
        return true;
    }

    /**
     * @param $id
     * @param string $field
     * @param array $res
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected static function getChildren($id,$field='id',$res=[]){
        $list = AttributeModel::where(["pid"=>$id,"status"=>0])->select()->toArray();
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
     * 获取规格数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAttr($data){
        $id = $data["id"]??0;
        $goods_id = $data["goods_id"]??0;
        $result = [];

        $rs = AttributeModel::where(["pid"=>$id])->select()->toArray();
        foreach($rs as $val){
            $result[$val['id']]["data"] = AttributeModel::where(["id"=>$val["id"]])->find();
            $result[$val['id']]["item"] = AttributeDataModel::where(["pid"=>$val["id"]])->order("sort","ASC")->select()->toArray();
        }

        $attr = GoodsAttributeModel::where(["goods_id"=>$goods_id])->select()->toArray();
        $spec_id = [];
        foreach($attr as $val){
            $spec_id[] = $val["attr_data_id"];
        }

        return [ "spec_checked"=>$spec_id, "result"=>$result ];
    }

    /**
     * 生成商品规格数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAttrData($data){
        $id = $data["id"]??0;
        $t = $data["t"]??0;
        $goods_id = $data["goods_id"]??0;
        $in = array_map("intval", explode(",", $id));

        if(!$t){
            $a = [];
            $goods_attribute = GoodsAttributeModel::where(["goods_id"=>$goods_id])->select()->toArray();
            foreach($goods_attribute as $val){
                $a[] = $val["attr_data_id"];
            }

            $in = array_merge($in,$a);
        }

        $result = AttributeDataModel::where("id",'in',$in)->order("sort","ASC")->select()->toArray();

        if (empty($result)) {
            throw new \Exception("Data is empty",1);
        }

        $arr = [];
        $shop_attribute_item = [];
        foreach ($result as $val) {
            $shop_attribute_item[] = $val["pid"];
        }

        $shop_attribute = AttributeModel::where('id','in',$shop_attribute_item)->select()->toArray();
        foreach ($result as $val) {
            foreach ($shop_attribute as $item) {
                if ($val['pid'] == $item['id']) {
                    $arr[$val["pid"]][$val["id"]] = $item['name'] . ";;;" . $val["value"] . ';;;' . $val["pid"] . ';;;' . $val["id"];
                }
            }
        }

        $data = Tool::descarte($arr);
        $table_head = [];
        foreach($data as &$item){
            foreach($item as $val){
                $param = explode(";;;",$val);
                if(!in_array($param[2],$table_head)){
                    $table_head[] = $param[2];
                }
            }
        }

        $headArray = [];
        foreach ($table_head as $val) {
            $headArray[] = $val;
        }

        $head = AttributeModel::where('id','in',$headArray)->select()->toArray();
        $goods = GoodsItemModel::where(["goods_id" => $goods_id])->select()->toArray();
        $goods_temp = [];
        foreach ($goods as $val) {
            $goods_temp[$val["spec_key"]] = $val;
        }

        return [ "goods"=>$goods_temp, "head"=>$head, "data"=>$data ];
    }

}