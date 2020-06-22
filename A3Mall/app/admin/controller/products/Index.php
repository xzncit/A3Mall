<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\basic\Attachments;
use mall\utils\Data;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Index extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit","1","intval");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(isset($key["cat_id"]) && $key["cat_id"] != '-1'){
                $condition["g.cat_id"] = $key["cat_id"];
            }

            if(isset($key["status"]) && $key["status"] != '-1'){
                $condition["g.status"] = $key["status"];
            }

            if(isset($key["brand_id"]) && $key["brand_id"] != '-1'){
                $condition["g.brand_id"] = $key["brand_id"];
            }

            if(!empty($key["title"])){
                $condition[] = ["g.title","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("goods")
                ->alias("g")
                ->join("category c","g.cat_id=c.id","LEFT")
                ->where($condition)->count();

            $data = Db::name("goods")
                ->field("g.*,c.title as cat_name")
                ->alias("g")
                ->join("category c","g.cat_id=c.id","LEFT")
                ->where($condition)->order('g.id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key]['create_time'] = Date::format($item['create_time']);
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]['photo'] = Tool::thumb($item["photo"],"small");
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        $cat = Db::name("category")->where(["status"=>0,"module"=>"goods"])->select()->toArray();
        return View::fetch("",[
            "cat"=>Data::analysisTree(Data::familyProcess($cat)),
            "brand"=>Db::name("products_brand")->where("status",0)->select()->toArray()
        ]);
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("goods")->where("id",$id)->find();

            $cat = Db::name("category")->where(["status"=>0,"module"=>"goods"])->select()->toArray();

            $goods_extends = Db::name("goods_extends")->where(['goods_id'=>$id])->select()->toArray();
            $goods_attribute = [];
            foreach($goods_extends as $val){
                $goods_attribute[] = $val["attribute"];
            }

            return View::fetch("",[
                "cat"=>Data::analysisTree(Data::familyProcess($cat)),
                "photo"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"goods","method"=>"photo"
                ])->select()->toArray(),
                "images"=>Db::name("attachments")->where([
                    'pid'=>$id,"module"=>"goods","method"=>"image"
                ])->select()->toArray(),
                "brand"=>Db::name("products_brand")->where("status",0)->select()->toArray(),
                "distribution"=>Db::name("distribution")->where("status",0)->select()->toArray(),
                "attribute"=>Db::name("products_attribute")->where(["pid"=>0])->select()->toArray(),
                "model"=>Db::name("products_model")->select()->toArray(),
                "goods_extends"=>$goods_attribute,
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        if(Db::name("distribution")->where(["id"=>$data["delivery_id"]])->count() <=0){
            return Response::returnArray("请设置运费模板",0);
        }

        $post = $data;
        $post['sell_price'] = $data['product_sell_price'];
        $post['market_price'] = $data['product_market_price'];
        $post['cost_price'] = $data['product_cost_price'];
        $post['goods_weight'] = $data['product_weight'];
        $post['store_nums'] = $data['product_store_nums'];
        if(!empty($data["id"])){
            try {
                $post['update_time'] = time();
                Db::name("goods")->strict(false)->where("id",$data['id'])->update($post);
                $data['goods_number'] = Db::name("goods")->where("id",$data['id'])->value("goods_number");
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $post['create_time'] = time();
            $post['upper_time'] = time();
            $post['goods_number'] = \mall\basic\Goods::goods_number();
            if(!Db::name("goods")->strict(false)->insert($post)){
                return Response::returnArray("操作失败，请重试。",0);
            }

            $data["id"] = Db::name("goods")->getLastInsID();
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

        Db::name('goods_attribute')->where(["goods_id" => $data["id"]])->delete();
        $shop_goods_attribute = [];
        foreach ($spec_temp_data as $item) {
            $shop_goods_attribute[] = $item;
        }

        if(!empty($shop_goods_attribute)){
            Db::name('goods_attribute')->insertAll($shop_goods_attribute);
        }

        $order_no = 1;
        Db::name("goods_item")->where(["goods_id" => $data["id"]])->delete();
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
            Db::name("goods_item")->insertAll($shop_goods_item);
        }

        Db::name("goods_extends")->where(['goods_id' => $data['id']])->delete();
        $data['goods_extends'] = !empty($data['goods_extends']) ? $data['goods_extends'] : [];
        foreach ($data['goods_extends'] as $val) {
            Db::name('goods_extends')->insert([
                'attribute' => $val, 'goods_id' => $data['id']
            ]);
        }

        $attr = array();
        foreach ($_POST as $key => $val) {
            if (strpos($key, 'attr_id_') !== false) {
                $attr[ltrim($key, 'attr_id_')] = $val;
            }
        }

        Db::name('goods_model')->where(['goods_id' => $data["id"]])->delete();
        $shop_goods_module = [];
        if ($data['model_id'] > 0 && !empty($attr)) {
            $sort = 0;
            foreach ($attr as $key => $val) {
                $shop_goods_module[] = array(
                    'goods_id' => $data["id"],
                    'model_id' => $data['model_id'],
                    'attribute_id' => $key,
                    'attribute_value' => is_array($val) ? join(',', $val) : $val,
                    'sort' => $sort
                );

                $sort++;
            }

            if(!empty($shop_goods_module)){
                Db::name('goods_model')->insertAll($shop_goods_module);
            }
        }

        $data["attachment_id"] = !empty($data["attachment_id"]) ? $data["attachment_id"] : [];
        Attachments::handle($data["attachment_id"],$data['id']);
        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("goods")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("goods")->delete($id);
            Db::name("goods_attribute")->where(['goods_id'=>$id])->delete();
            Db::name("goods_extends")->where(['goods_id'=>$id])->delete();
            Db::name("goods_item")->where(['goods_id'=>$id])->delete();
            Db::name("goods_model")->where(['goods_id'=>$id])->delete();
            Attachments::clear(["pid"=>$id,"module"=>"goods","method"=>"photo"]);
            Attachments::clear(["pid"=>$id,"module"=>"goods","method"=>"image"]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

    public function field(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        $name = strip_tags(trim(Request::get("name")));
        $value = (int)Request::get("value");

        try {
            Db::name("goods")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}