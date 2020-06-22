<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\promotion;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Second extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $key = Request::get("key/a","","trim,strip_tags");

            $condition = [];
            if(!empty($key["title"])){
                $condition[] = ["title","like",'%'.$key["title"].'%'];
            }

            $count = Db::name("promotion_seckill")
                ->alias("r")
                ->join("goods g","r.goods_id=g.id","LEFT")
                ->where($condition)->count();

            $data = Db::name("promotion_seckill")
                ->alias("r")
                ->field("r.*,g.photo")
                ->join("goods g","r.goods_id=g.id","LEFT")
                ->where($condition)->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                $list[$key]["id"] = $item["id"];
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
                $list[$key]["title"] = $item["title"];
                $list[$key]["photo"] = Tool::thumb($item["photo"]);
                $list[$key]['create_time'] = Date::format($item["create_time"]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $row = empty($id) ? [] : Db::name("promotion_seckill")->where("id",$id)->find();

            if(!empty($row)){
                $row = Db::name("promotion_seckill")->where(["id"=>$row["id"]])->find();
                if(empty($row)){
                    $this->error("您查找的数据不存在！");
                }

                $row["start_time"] = date("Y-m-d H:i:s",$row["start_time"]);
                $row["end_time"] = date("Y-m-d H:i:s",$row["end_time"]);
                $goods = Db::name("goods")->where(["id"=>$row["goods_id"]])->find();
                $row["goods"] = $goods;
                if(!empty($row["product_id"])){
                    $product_id = explode(",",$row["product_id"]);
                    $products = Db::name("goods_item")->where(["goods_id"=>$row["goods_id"]])->select()->toArray();

                    $temp = array();
                    foreach($products as $key=>$item){
                        $temp[$key] = $item;
                        $temp[$key]["checked"] = in_array($item["id"],$product_id) ? true : false;
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

                    $row["products"] = $temp;
                }

            }

            return View::fetch("",[
                "data"=>$row
            ]);
        }

        $data = Request::post();
        $row = Db::name("promotion_seckill")->where(["goods_id"=>$data["goods_id"]])->find();
        if(!empty($row)){
            if($row["id"] != $data["id"]){
                return Response::returnArray("该商品己添加过秒杀活动",0);
            }
        }

        if(!empty($data["product_id"])){
            $data["product_id"] = implode(",",$data["product_id"]);
        }

        if(empty($data["start_time"])){
            return Response::returnArray("请填写开始时间",0);
        }

        if(empty($data["end_time"])){
            return Response::returnArray("请填写结束时间",0);
        }

        $data["start_time"] = strtotime($data["start_time"]);
        $data["end_time"] = strtotime($data["end_time"]);

        if($data["start_time"] > $data["end_time"]){
            return Response::returnArray("开始时间不能大于结束时间",0);
        }

        if(empty($data["id"])){
            $data["create_time"] = time();
            if(!Db::name("promotion_seckill")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            Db::name("promotion_seckill")->strict(false)->where(["id"=>$data["id"]])->update($data);
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("promotion_seckill")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("promotion_seckill")->delete($id);
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
            Db::name("promotion_seckill")->where("id",$id)->update([$name=>$value]);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试！",0);
        }

        return Response::returnArray("ok");
    }

}