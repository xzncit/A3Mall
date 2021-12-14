<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\products;

use app\admin\service\platform\Category as CategoryService;
use app\admin\service\Service;
use app\admin\model\goods\Goods as GoodsModel;
use app\common\models\goods\GoodsItem as GoodsItemModel;
use app\common\models\goods\ProductsBrand as BrandModel;
use app\common\models\goods\Attribute as AttributeModel;
use app\common\models\goods\AttributeData as AttributeDataModel;
use app\common\models\goods\GoodsExtends as GoodsExtendsModel;
use app\common\models\goods\GoodsModel as GoodsParamsModel;
use app\common\models\goods\GoodsImage as GoodsImageModel;
use app\common\models\goods\GoodsAttribute as GoodsAttributeModel;
use app\common\models\promotion\PromotionGroup as PromotionGroupModel;
use app\common\models\promotion\PromotionGroupItem as PromotionGroupItemModel;
use app\common\models\promotion\PromotionRegiment as PromotionRegimentModel;
use app\common\models\promotion\PromotionRegimentItem as PromotionRegimentItemModel;
use app\common\models\promotion\PromotionPoint as PromotionPointModel;
use app\common\models\promotion\PromotionPointItem as PromotionPointItemModel;
use app\common\models\promotion\PromotionSecond as PromotionSecondModel;
use app\common\models\promotion\PromotionSecondItem as PromotionSecondItemModel;
use app\common\models\promotion\PromotionPrice as PromotionPriceModel;
use app\common\models\promotion\PromotionPriceItem as PromotionPriceItemModel;
use app\common\models\goods\Distribution as DistributionModel;
use app\common\models\goods\ProductsModel;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Env;

/**
 * 商品服务类
 * Class Goods
 * @package app\admin\service\products
 */
class Goods extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @param string $goodsType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data,$goodsType="0"){
        $fields = ["cat_id","status","brand_id","title","goods_type"];
        $searchData = [
            'cat_id'=>$data['key']["cat_id"]??'',
            'status'=>$data['key']["status"]??'',
            'brand_id'=>$data['key']["brand_id"]??'',
            'title'=>$data['key']["title"]??'',
            'goods_type'=>$goodsType
        ];

        $count = GoodsModel::withSearch($fields,$searchData)->withJoin("category")->count();
        $result = array_map(function ($res){
            $res["photo"] = Tool::thumb($res["photo"]);
            return $res;
        },GoodsModel::withSearch($fields,$searchData)->withJoin("category")->order("goods.id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 获取指定商品规格列表
     * @param $goods_id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getGoodsItemData($goods_id){
        if(!$row=GoodsModel::where(["id"=>$goods_id])->find()){
            throw new \Exception("您要查找的数据不存在",0);
        }

        $products = GoodsItemModel::where(["goods_id"=>$goods_id])->select()->toArray();

        $temp = [];
        foreach($products as $key=>$item){
            $temp[$key] = $item;
            $arr = explode(",",$item["spec_key"]);
            foreach($arr as $value){
                $param = explode(":",$value);
                $name = AttributeModel::where(["id"=>$param[0]])->value("name");
                $value = AttributeDataModel::where(["id"=>$param[1],"pid"=>$param[0]])->value("value");
                $temp[$key]['spec_item'][] = $name . ':' . $value;
            }
            if(!empty($temp[$key]['spec_item'])){
                $temp[$key]['spec_item'] = implode(",", $temp[$key]['spec_item']);
            }
        }

        $row['item'] = $temp;
        return $row;
    }

    /**
     * 获取列表搜索分类和品牌数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSearchData(){
        return [
            "cat"=>CategoryService::getTree(["status"=>0,"module"=>"goods"]),
            "brand"=>BrandModel::where("status",0)->select()->toArray()
        ];
    }

    public static function getGoodsListData($goods_id){
        $result = GoodsModel::where("id","in",$goods_id)->select()->toArray();

        $array = [];
        foreach($result as $value){
            $array[] = $value["id"];
        }

        return [ "data"=>$result,"goods_id"=>$array ];
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
        $goods = GoodsModel::where("id",$id)->find();

        $goodsExtends = GoodsExtendsModel::where(['goods_id'=>$id])->select()->toArray();
        $goodsAttribute = [];
        foreach($goodsExtends as $val){
            $goodsAttribute[] = $val["attribute"];
        }

        return [
            "cat"=>CategoryService::getTree(["status"=>0,"module"=>"goods"]),
            "photo"=>GoodsImageModel::where(['goods_id'=>$id])->select()->toArray(),
            "brand"=>BrandModel::where("status",0)->select()->toArray(),
            "distribution"=>DistributionModel::where("status",0)->select()->toArray(),
            "attribute"=>AttributeModel::where(["pid"=>0])->select()->toArray(),
            "model"=>ProductsModel::select()->toArray(),
            "goods_extends"=>$goodsAttribute,
            "data"=>$goods??[]
        ];
    }

    /**
     * 保存数据
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data=[]){
        if(DistributionModel::where(["id"=>$data["delivery_id"]])->count() <=0){
            throw new \Exception("请设置运费模板",0);
        }

        $post = $data;
        $post['sell_price'] = $data['product_sell_price'];
        $post['market_price'] = $data['product_market_price'];
        $post['cost_price'] = $data['product_cost_price'];
        $post['goods_weight'] = $data['product_weight'];
        $post['store_nums'] = $data['product_store_nums'];

        if(empty($post['goods_number'])){
            $data['goods_number'] = $post['goods_number'] = self::goodsNumber();
        }

        if(GoodsModel::where("id",$data["id"])->count()){
            GoodsModel::where("id",$data["id"])->save($post);
        }else{
            unset($data["id"],$post["id"]);
            $data["id"] = GoodsModel::create($post)->id;
        }

        $i = 0;
        $data['spec_list_key'] = !empty($data['spec_list_key']) ? $data['spec_list_key'] : [];
        $spec_temp = array();
        foreach ($data['spec_list_key'] as $val) {
            $arr = explode(',', $val);
            foreach ($arr as $item) {
                $a = explode(':', $item);
                $spec_temp[$i]['goods_id'] = $data['id'];
                $spec_temp[$i]['attr_id'] = $a[0];
                $spec_temp[$i]['attr_data_id'] = $a[1];
                $i++;
            }
        }

        $j = 0;
        $data['spec_list_data'] = !empty($data['spec_list_data']) ? $data['spec_list_data'] : [];
        foreach ($data['spec_list_data'] as $val) {
            $arr = explode(',', $val);
            foreach ($arr as $item) {
                $a = explode(':', $item);
                $spec_temp[$j]['name'] = $a[0];
                $spec_temp[$j]['value'] = $a[1];
                $j++;
            }
        }

        $spec_temp_data = [];
        foreach($spec_temp as $value){
            $spec_temp_data[$value['goods_id'] . '_' . $value['attr_id'] . '_' . $value["attr_data_id"]] = $value;
        }

        GoodsAttributeModel::where(["goods_id" => $data["id"]])->delete();
        $shop_goods_attribute = [];
        foreach ($spec_temp_data as $item) {
            $shop_goods_attribute[] = $item;
        }

        if(!empty($shop_goods_attribute)){
            GoodsAttributeModel::insertAll($shop_goods_attribute);
        }

        $order_no = 1;
        GoodsItemModel::where(["goods_id" => $data["id"]])->delete();
        $shop_goods_item = [];
        $data['sell_price'] = !empty($data['sell_price']) ? $data['sell_price'] : [];
        foreach ($data['sell_price'] as $key => $item) {
            $shop_goods_item[] = [
                "goods_id" => $data["id"],
                "spec_key" => $data['spec_list_key'][$key],
                "goods_number" => $data['goods_number'] . '-' . $order_no,
                "store_nums" => $data['store_nums'][$key],
                "market_price" => $data['market_price'][$key],
                "sell_price" => $item,
                "cost_price" => $data['cost_price'][$key],
                "goods_weight" => $data['goods_weight'][$key]
            ];

            $order_no++;
        }

        if(!empty($shop_goods_item)){
            GoodsItemModel::insertAll($shop_goods_item);
        }

        GoodsExtendsModel::where(['goods_id' => $data['id']])->delete();
        $data['goods_extends'] = !empty($data['goods_extends']) ? $data['goods_extends'] : [];
        foreach ($data['goods_extends'] as $val) {
            GoodsExtendsModel::create(['attribute' => $val, 'goods_id' => $data['id']]);
        }

        $attr = [];
        foreach ($_POST as $key => $val) {
            if (strpos($key, 'attr_id_') !== false) {
                $attr[ltrim($key, 'attr_id_')] = $val;
            }
        }

        GoodsParamsModel::where(['goods_id' => $data["id"]])->delete();
        $shop_goods_module = [];
        if ($data['model_id'] > 0 && !empty($attr)) {
            $sort = 0;
            foreach ($attr as $key => $val) {
                $shop_goods_module[] = [
                    'goods_id' => $data["id"],
                    'model_id' => $data['model_id'],
                    'attribute_id' => $key,
                    'attribute_value' => is_array($val) ? join(',', $val) : $val,
                    'sort' => $sort
                ];

                $sort++;
            }

            if(!empty($shop_goods_module)){
                GoodsParamsModel::insertAll($shop_goods_module);
            }
        }

        $images = [];
        if(!empty($data["images"])){
            foreach($data["images"] as $value){
                $images[] = [
                    "goods_id"      => $data["id"],
                    "path"          => $value,
                    "create_time"   => time()
                ];
            }
        }

        GoodsImageModel::where("goods_id",$data["id"])->delete();
        if(!empty($images)){
            GoodsImageModel::insertAll($images);
        }

        return true;
    }

    /**
     * 删除商品
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public static function delete($params){
        try{
            GoodsModel::startTrans();

            $array = array_map("intval",explode(",",$params));
            foreach($array as $id) {
                $row = GoodsModel::where('id', $id)->find();
                if(empty($row)) continue;

                GoodsModel::where("id",$id)->delete();
                AttributeModel::where(['goods_id' => $id])->delete();
                GoodsExtendsModel::where(['goods_id' => $id])->delete();
                GoodsItemModel::where(['goods_id' => $id])->delete();
                GoodsParamsModel::where(['goods_id' => $id])->delete();
                GoodsImageModel::where(['goods_id' => $id])->delete();

                if ($promotion_group = PromotionGroupModel::where("goods_id", $id)->find()) {
                    PromotionGroupModel::where('id',$promotion_group["id"])->delete();
                    PromotionGroupItemModel::where('pid', $promotion_group["id"])->delete();
                }

                if ($promotion_regiment = PromotionRegimentModel::where("goods_id", $id)->find()) {
                    PromotionRegimentModel::where("id",$promotion_regiment["id"])->delete();
                    PromotionRegimentItemModel::where("pid", $promotion_regiment["id"])->delete();
                }

                if ($promotion_second = PromotionSecondModel::where("goods_id", $id)->find()) {
                    PromotionSecondModel::where("id",$promotion_second["id"])->delete();
                    PromotionSecondItemModel::where("pid", $promotion_second["id"])->delete();
                }

                if ($promotion_point = PromotionPointModel::where("goods_id", $id)->find()) {
                    PromotionPointModel::where("id",$promotion_point["id"])->delete();
                    PromotionPointItemModel::where('pid', $promotion_point["id"])->delete();
                }

                if ($promotion_price = PromotionPriceModel::where("goods_id", $id)->find()) {
                    PromotionPriceModel::where("id",$promotion_price["id"])->delete();
                    PromotionPriceItemModel::where("pid",$promotion_price['id'])->delete();
                }
            }

            GoodsModel::commit();
            return true;
        }catch (\Exception $ex){
            GoodsModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 更新字段值
     * @return GoodsModel
     */
    public static function setFields(){
        $data = self::getFields();
        return GoodsModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
    }

}