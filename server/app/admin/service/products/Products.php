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
use app\common\models\goods\GoodsExtends as GoodsExtendsModel;
use app\common\models\goods\GoodsModel as GoodsParamsModel;
use app\common\models\goods\GoodsImage as GoodsImageModel;
use app\common\models\goods\GoodsCard as GoodsCardModel;
use app\common\models\promotion\PromotionPrice as PromotionPriceModel;
use app\common\models\promotion\PromotionPriceItem as PromotionPriceItemModel;
use app\common\models\goods\Distribution as DistributionModel;
use app\common\models\goods\ProductsModel;
use mall\utils\Tool;
use think\facade\Db;
use think\facade\Env;

/**
 * 商品服务类
 * Class Products
 * @package app\admin\service\products
 */
class Products extends Service {

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
            "model"=>ProductsModel::select()->toArray(),
            "card"=>GoodsCardModel::select()->toArray(),
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
                GoodsParamsModel::saveAll($shop_goods_module);
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
                GoodsExtendsModel::where(['goods_id' => $id])->delete();
                GoodsItemModel::where(['goods_id' => $id])->delete();
                GoodsParamsModel::where(['goods_id' => $id])->delete();
                GoodsImageModel::where(['goods_id' => $id])->delete();

                if ($promotion_price = PromotionPriceModel::where("goods_id", $id)->find()) {
                    PromotionPriceModel::where("id",$promotion_price["id"])->delete();
                    PromotionPriceItemModel::where("pid",$promotion_price['id'])->delete();
                }
            }

            GoodsModel::commit();
            return true;
        }catch (\Exception $ex){
            GoodsModel::rollback();
            throw new \Exception($ex->getMessage(). " file: " . $ex->getFile() . " line: " . $ex->getLine(),$ex->getCode());
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

    /**
     * 导出数据
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function export(){
        $data = $title = [];

        $prefix = Env::get("DATABASE_PREFIX");
        $fields = Db::query("show COLUMNS FROM {$prefix}goods");
        foreach($fields as $item){
            $title[] = $item["Field"];
        }

        $r = GoodsModel::select()->toArray();
        foreach($r as $key=>$item){
            $arr = [];
            foreach ($item as $k=>$v){
                $arr[$k] = ' ' . $v;
            }
            $data[] = $arr;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($title as $key => $value) {
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }

        $row = 2;
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        $titCol = 'A';
        foreach ($title as $key => $value) {
            $sheet->setCellValue($titCol . '1', $value);
            $titCol++;
        }

        $row = 2;
        foreach ($data as $item) {
            $dataCol = 'A';
            foreach ($item as $value) {
                $sheet->setCellValue($dataCol . $row, $value);
                $dataCol++;
            }
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="goods.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}