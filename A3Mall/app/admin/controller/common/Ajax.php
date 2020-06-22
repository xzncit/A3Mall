<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\common;

use app\admin\controller\Auth;
use mall\utils\Data;
use mall\utils\Date;
use mall\response\Response;
use think\facade\Request;
use think\facade\Db;
use mall\utils\Tool;
use think\facade\View;

class Ajax extends Auth {

    public function get_goods(){
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
                $condition["g.title"] = ["like",'%'.$key["title"].'%'];
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
        return View::fetch("common/get_goods",[
            "cat"=>Data::analysisTree(Data::familyProcess($cat)),
            "brand"=>Db::name("products_brand")->where("status",0)->select()->toArray()
        ]);
    }

    public function get_goods_data(){
        $goods_id = Request::param("id", "0","intval");
        if(($row=Db::name("goods")->where(["id"=>$goods_id])->find()) == false){
            return Response::returnArray("您要查找的数据不存在",0);
        }

        $products = Db::name("goods_item")->where(["goods_id"=>$goods_id])->select()->toArray();

        $temp = array();
        foreach($products as $key=>$item){
            $temp[$key] = $item;
            $arr = explode(",",$item["spec_key"]);
            foreach($arr as $value){
                $param = explode(":",$value);
                $name = Db::name("products_attribute")->where(["id"=>$param[0]])->value("name");
                $value = Db::name("products_attribute_data")->where(["id"=>$param[1],"pid"=>$param[0]])->value("value");
                $temp[$key]['spec_item'][] = $name . ':' . $value;
            }
            if(!empty($temp[$key]['spec_item'])){
                $temp[$key]['spec_item'] = implode(",", $temp[$key]['spec_item']);
            }
        }

        $row['item'] = $temp;
        return Response::returnArray("ok",1,$row);
    }

    public function get_area(){
        $id = Request::get("id","0","intval");
        
        if($id <= 0){
            return Response::returnArray('请求出错！',0);
        }
        
        $result = Db::name('area')->where(['pid'=>$id])->select()->toArray();
        
        $string = '<option value="">请选择</option>';
        foreach($result as $val){
            $string .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
        }
        
        return Response::returnArray('ok',1,$string);
    }
    
    public function get_distribution(){
        $result = Db::name('area')->where(['level'=>1])->order('id ASC')->select()->toArray();
        foreach ($result as $key => $val) {
            $result[$key] = $val;
            $result[$key]['children'] = Db::name('area')->where(['pid' => $val["id"]])->select()->toArray();
        }
        
        return View::fetch('common/get_distribution',['data'=>$result]);
    }
    
    public function get_attr(){
        $id = Request::get("id",'0',"intval");
        $goods_id = Request::get("goods_id","0","intval");
        $result = [];
        
        if(empty($id)){
            return Response::returnArray("ok",1);
        }
        
        $rs = Db::name("products_attribute")->where(["pid"=>$id])->select()->toArray();
        
        foreach($rs as $val){
            $result[$val['id']]["data"] = Db::name("products_attribute")->where(["id"=>$val["id"]])->find();
            $result[$val['id']]["item"] = Db::name("products_attribute_data")->where(["pid"=>$val["id"]])->order("sort ASC")->select()->toArray();
        }
        
        $attr = Db::name("goods_attribute")->where(["goods_id"=>$goods_id])->select()->toArray();
        $spec_id = [];
        foreach($attr as $val){
            $spec_id[] = $val["attr_data_id"];
        }
        
        $html = View::fetch("common/get_attr",[
            "spec_checked"=>$spec_id,"result"=>$result
        ]);
        
        return Response::returnArray("ok",1,$html);
    }
    
    public function get_attr_data(){
        $id = Request::post("id","0","strip_tags");
        $t = Request::post("t","0","intval");
        $goods_id = Request::post("goods_id","0","intval");
        $in = array_map("intval", explode(",", $id));
        
        if(!$t){
            $a = [];
            $goods_attribute = Db::name("goods_attribute")->where(["goods_id"=>$goods_id])->select()->toArray();
            foreach($goods_attribute as $val){
                $a[] = $val["attr_data_id"];
            }

            $in = array_merge($in,$a);
        }
        
        $result = Db::name("products_attribute_data")->where("id",'in',$in)->order("sort ASC")->select()->toArray();

        if (empty($result)) {
            return Response::returnArray("ok", 1,"");
        }


        $arr = [];
        $shop_attribute_item = [];
        foreach ($result as $val) {
            $shop_attribute_item[] = $val["pid"];
        }

        $shop_attribute = Db::name("products_attribute")->where('id','in',$shop_attribute_item)->select()->toArray();
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
        $head = Db::name("products_attribute")->where('id','in',$headArray)->select()->toArray();

        $goods = Db::name("goods_item")->where(["goods_id" => $goods_id])->select()->toArray();
        $goods_temp = [];
        foreach ($goods as $val) {
            $goods_temp[$val["spec_key"]] = $val;
        }
        
        $html = View::fetch("common/get_attr_data",[
            "goods"=>$goods_temp,
            "head"=>$head,
            "data"=>$data
        ]);
        
        return Response::returnArray("ok", 1, $html);
    }
    
    public function get_model(){
        $id = Request::get("id","0","intval");
        $goods_id = Request::get("goods_id","0","intval");
        
        $result = Db::name("products_model_data")->where(['pid'=>$id])->order("sort ASC")->select()->toArray();
        if(empty($result)){
            return Response::returnArray("您要查找的模型内容不存在！",0);
        }
        
        $goods_attr = [];
        $module = Db::name("goods_model")->where(["model_id"=>$id,"goods_id"=>$goods_id])->order("sort ASC")->select()->toArray();
        if ($module) {
            foreach ($module as $item) {
                $goods_attr[$item['attribute_id']] = $item['attribute_value'];
            }
        }
        
        $html = View::fetch("common/get_model",[
            "goods_attr"=>$goods_attr,
            "result"=>$result
        ]);
        
        return Response::returnArray("ok",1,$html);
    }
}